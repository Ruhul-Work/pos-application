<?php
namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\Category;
use App\Models\backend\PaymentType;
use App\Models\backend\Product;
use App\Models\backend\Sale;
use App\Models\backend\SaleItem;
use App\Models\backend\SalePayment;
use App\Models\backend\StockCurrent;
use App\Models\backend\StockLedger;
use App\Models\backend\Account;
use App\Services\StockLedgerService;
use App\Support\BranchScope;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $branchId   = auth()->user()->branch_id;

        $accounts = Account::where('is_active', 1)
            ->whereHas('branchAccounts', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->orderBy('name')
            ->get();

        return view('backend.modules.pos.index', compact(['categories' , 'accounts'] ));
    }

    /**
     * Store POS Sale
     */
    public function store(Request $request)
    {
        // -----------------------------
        // 1️⃣ Validate Request
        // -----------------------------

        $data = $request->validate([

            'customer_id'                => 'nullable|exists:customers,id',
            'sale_type'                  => 'nullable|string',
            'status'                     => 'required|string',

            'subtotal'                   => 'required|numeric|min:0',
            'discount'                   => 'nullable|numeric|min:0',
            'tax_amount'                 => 'nullable|numeric|min:0',
            'shipping_charge'            => 'nullable|numeric|min:0',
            'total'                      => 'required|numeric|min:0',

            'sale_note'                  => 'nullable|string',

            // items
            'items'                      => 'required|array|min:1',
            'items.*.product_id'         => 'required|exists:products,id',
            'items.*.product_variant_id' => 'nullable|exists:product_variants,id',
            'items.*.unit_id'            => 'nullable|exists:units,id',
            'items.*.quantity'           => 'required|numeric|min:0.001',
            'items.*.unit_price'         => 'required|numeric|min:0',
            'items.*.discount_amount'    => 'nullable|numeric|min:0',
            'items.*.tax_amount'         => 'nullable|numeric|min:0',
            'items.*.lot_number'         => 'nullable|string',
            'items.*.expiry_date'        => 'nullable|date',

            // payments
            'payments'                   => 'nullable|array',
            'payments.*.payment_type_id' => 'nullable|exists:payment_types,id',
            'payments.*.payment_type'    => 'nullable|string',
            'payments.*.amount'          => 'required|numeric|min:0.01',
            'payments.*.reference'       => 'nullable|string',
            'payments.*.received_by'     => 'nullable|string',
            'payments.*.note'            => 'nullable|string',
        ]);

        \Log::info('BRANCH DEBUG', [
            'payload_branch' => $request->branch_id,
            'session_branch' => BranchScope::currentId(),
            'user_branch'    => auth()->user()->branch_id,
        ]);

        // Validate branch access

        $branchId    = current_branch_id();
        $warehouseId = current_warehouse_id();

        if (auth()->user()->isSuper()) {
            abort_if(
                ! $branchId || ! $warehouseId,
                422,
                'Please select a branch before making a POS sale.'
            );
        } else {
            abort_if(
                ! $branchId || ! $warehouseId,
                422,
                'Branch/Warehouse context missing.'
            );
        }

        // -----------------------------
        // 2️⃣ DB Transaction
        // -----------------------------
        try {
            return DB::transaction(function () use ($data, $request, $branchId, $warehouseId) {

                // -----------------------------
                // `RESUME SALE` handling
                // -----------------------------
                if ($request->resume_sale_id) {
                    // UPDATE EXISTING HOLD SALE
                    $sale = Sale::where('id', $request->resume_sale_id)
                        ->where('status', 'hold')
                        ->lockForUpdate()
                        ->firstOrFail();

                    $sale->update([
                        'status'          => 'delivered',
                        'subtotal'        => $request->subtotal,
                        'discount'        => $request->discount,
                        'shipping_charge' => $request->shipping_charge,
                        'total'           => $request->total,
                        'paid_amount'     => $request->amount ?? 0,
                        'due_amount'      => 0,
                        'payment_status'  => 'paid',
                    ]);

                    // clear old items & payments
                    $sale->items()->delete();
                    $sale->payments()->delete();

                } else {

                    // -----------------------------
                    // 3️⃣ Create Sale
                    // -----------------------------
                    $sale = Sale::create([
                        'invoice_no'      => $this->generateInvoiceNo(),
                        'branch_id'       => $branchId,
                        'warehouse_id'    => $warehouseId,
                        'customer_id'     => $data['customer_id'] ?? null,
                        'user_id'         => auth()->id(),
                        'pos_session_id'  => $request->header('X-POS-SESSION') ?? null,

                        'sale_type'       => $data['sale_type'] ?? 'retail',
                        'status'          => $data['status'],

                        'subtotal'        => $data['subtotal'],
                        'discount'        => $data['discount'] ?? 0,
                        'tax_amount'      => $data['tax_amount'] ?? 0,
                        'shipping_charge' => $data['shipping_charge'] ?? 0,
                        'total'           => $data['total'],

                        'paid_amount'     => 0,
                        'due_amount'      => $data['total'],
                        'payment_status'  => 'due',

                        'sale_note'       => $data['sale_note'] ?? null,
                    ]);
                }
                // -----------------------------
                // 4️⃣ Insert Sale Items
                // -----------------------------
                foreach ($data['items'] as $row) {

                    $lineTotal =
                        ($row['unit_price'] * $row['quantity'])
                         - ($row['discount_amount'] ?? 0)
                         + ($row['tax_amount'] ?? 0);

                    SaleItem::create([
                        'sale_id'            => $sale->id,
                        'product_id'         => $row['product_id'],
                        'product_variant_id' => $row['product_variant_id'] ?? null,
                        'unit_id'            => $row['unit_id'] ?? null,
                        'quantity'           => $row['quantity'],
                        'unit_price'         => $row['unit_price'],
                        'discount_amount'    => $row['discount_amount'] ?? 0,
                        'tax_amount'         => $row['tax_amount'] ?? 0,
                        'line_total'         => round($lineTotal, 2),
                        'lot_number'         => $row['lot_number'] ?? null,
                        'expiry_date'        => $row['expiry_date'] ?? null,
                    ]);

                }

                // -----------------------------
                // STOCK LEDGER (WAREHOUSE WISE)
                // -----------------------------
                StockLedgerService::deductForSale([
                    'sale_id'      => $sale->id,
                    'warehouse_id' => $warehouseId,
                    'branch_id'    => $branchId,
                    'user_id'      => auth()->id(),
                    'items'        => collect($data['items'])->map(function ($row) {
                        return [
                            'product_id' => $row['product_id'],
                            'quantity'   => $row['quantity'],
                            'unit_price' => $row['unit_price'],
                        ];
                    })->toArray(),
                ]);

                // -----------------------------
                // 5️⃣ Insert Payments (Multi-payment)
                // -----------------------------
                $paidAmount = 0;

                if (! empty($data['payments'])) {
                    foreach ($data['payments'] as $pay) {

                        // Resolve payment_type_id from enum if needed
                        $paymentTypeId = $pay['payment_type_id'] ?? null;

                        if (! $paymentTypeId && ! empty($pay['payment_type'])) {
                            $pt = PaymentType::where('slug', $pay['payment_type'])
                                ->orWhere('name', $pay['payment_type'])
                                ->first();
                            $paymentTypeId = $pt?->id;
                        }

                        SalePayment::create([
                            'sale_id'         => $sale->id,
                            'payment_type_id' => $paymentTypeId,
                            'payment_type'    => $pay['payment_type'] ?? null,
                            'amount'          => $pay['amount'],
                            'reference'       => $pay['reference'] ?? null,
                            'received_by'     => $pay['received_by'] ?? auth()->user()->name,
                            'note'            => $pay['note'] ?? null,
                            'paid_at'         => now(),
                        ]);

                        $paidAmount += $pay['amount'];
                    }
                }

                // -----------------------------
                // 6️⃣ Update Paid / Due / Status
                // -----------------------------
                $sale->paid_amount = round($paidAmount, 2);
                $sale->due_amount  = round($sale->total - $paidAmount, 2);
                $sale->recalcPaymentStatus();
                $sale->save();

                // -----------------------------
                // 7️⃣ Response
                // -----------------------------
                return response()->json([
                    'success' => true,
                    'message' => 'Sale completed successfully',
                    'id'      => $sale->id,
                    'sale_id' => $sale->id,
                    'invoice' => $sale->invoice_no,
                ], 201);
            });

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'type'    => 'STOCK_ERROR',
            ], 409); // 409 Conflict = business rule fail
        }
    }

    /**
     * Generate Invoice Number
     */
    protected function generateInvoiceNo(): string
    {
        return 'POS-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
    }

    public function hold(Request $request)
    {
        $data = $request->validate([
            'branch_id'          => 'required|exists:branches,id',
            'warehouse_id'       => 'required|exists:warehouses,id',
            'customer_id'        => 'nullable|exists:customers,id',

            'subtotal'           => 'required|numeric|min:0',
            'discount'           => 'nullable|numeric|min:0',
            'tax_amount'         => 'nullable|numeric|min:0',
            'shipping_charge'    => 'nullable|numeric|min:0',
            'total'              => 'required|numeric|min:0',

            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|numeric|min:0.001',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($data) {

            $sale = Sale::create([
                'invoice_no'      => 'HOLD-' . now()->format('Ymd-His'),
                'branch_id'       => $data['branch_id'],
                'warehouse_id'    => $data['warehouse_id'],
                'customer_id'     => $data['customer_id'] ?? null,
                'user_id'         => auth()->id(),
                'status'          => 'hold',
                'sale_type'       => 'retail',

                'subtotal'        => $data['subtotal'],
                'discount'        => $data['discount'] ?? 0,
                'tax_amount'      => $data['tax_amount'] ?? 0,
                'shipping_charge' => $data['shipping_charge'] ?? 0,
                'total'           => $data['total'],

                'paid_amount'     => 0,
                'due_amount'      => $data['total'],
                'payment_status'  => 'due',
            ]);

            foreach ($data['items'] as $row) {
                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $row['product_id'],
                    'quantity'   => $row['quantity'],
                    'unit_price' => $row['unit_price'],
                    'line_total' => ($row['unit_price'] * $row['quantity']),
                ]);
            }

            return response()->json([
                'success' => true,
                'sale_id' => $sale->id,
                'message' => 'Sale put on hold',
            ]);
        });
    }

    //*** Fetch Hold Sales ***

    public function holdList()
    {
        $user = auth()->user();

        $query = Sale::with(['customer', 'items'])
            ->where('status', 'hold');

        // -----------------------------
        // 🔐 Branch-wise access control
        // -----------------------------
        if (! $user->isSuperAdmin()) {
            $query->where('branch_id', $user->branch_id);
        }

        $sales = $query
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        return response()->json([
            'data' => $sales->map(function ($sale) {
                return [
                    'id'         => $sale->id,
                    'invoice_no' => $sale->invoice_no,
                    'customer'   => optional($sale->customer)->name ?? 'Walk In',
                    'quantity'   => $sale->items->sum('quantity'),
                    'unit_price' => $sale->items->first()?->unit_price,
                    'total'      => $sale->total,
                ];
            }),
        ]);
    }

    //*** Resume Hold Sale ***
    // public function resume(Sale $sale)
    // {
    //     if ($sale->status !== 'hold') {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'This sale is not on hold',
    //         ], 422);
    //     }

    //     $sale->load('items.product', 'customer:id,name');

    //     return response()->json([
    //         'success' => true,
    //         'sale'    => $sale,
    //     ]);
    // }

    // PosController.php

    public function resume(Sale $sale)
    {
        // 🔐 Branch security
        if (! auth()->user()->isSuperAdmin() &&
            $sale->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        // 🔒 Only HOLD sale editable
        if ($sale->status !== 'hold') {
            abort(403, 'Only HOLD sales can be edited');
        }

        $sale->load([
            'items.product',
            'customer',
        ]);
        return response()->json([
            'success' => true,
            'sale'    => [
                'id'              => $sale->id,
                'customer_id'     => $sale->customer_id,
                'customer_name'   => $sale->customer?->name ?? 'Walk In',
                'subtotal'        => $sale->subtotal,
                'discount'        => $sale->discount,
                'shipping_charge' => $sale->shipping_charge,

                'items'           => $sale->items->map(function ($i) {
                    return [
                        'product_id' => $i->product_id,
                        'quantity'   => $i->quantity,
                        'unit_price' => $i->unit_price,
                        'product'    => [
                            'name'      => $i->product->name,
                            'mrp'       => $i->product->mrp,
                            'parent_id' => $i->product->parent_id,
                        ],
                    ];
                }),
            ],
        ]);
    }

    //*** Fetch Today's Orders ***

    public function todayOrders()
    {
        $user = auth()->user();

        $start = Carbon::today()->startOfDay();
        $end   = Carbon::today()->endOfDay();

        $query = Sale::with('customer')
            ->whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['delivered', 'hold']);

        // -----------------------------
        // 🔐 Branch-wise access control
        // -----------------------------
        if (! $user->isSuperAdmin()) {
            $query->where('branch_id', $user->branch_id);
        }

        $sales = $query
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($sale) {
                return [
                    'id'       => $sale->id,
                    'invoice'  => $sale->invoice_no,
                    'customer' => $sale->customer?->name ?? 'Walk In',
                    'total'    => $sale->total,
                    'status'   => $sale->status,
                    'time'     => $sale->created_at->format('H:i'),
                ];
            });

        return response()->json([
            'success' => true,
            'sales'   => $sales,
        ]);
    }

    //*** Void a Sale ***
    public function void(Sale $sale, Request $request)
    {
        // safety checks
        if (! in_array($sale->status, ['delivered', 'hold'])) {
            return response()->json([
                'success' => false,
                'message' => 'This sale cannot be voided',
            ], 422);
        }

        DB::transaction(function () use ($sale, $request) {

            // 1️⃣ Reverse stock
            foreach ($sale->items as $item) {
                StockLedger::create([
                    'txn_date'     => now(),
                    'product_id'   => $item->product_id,
                    'warehouse_id' => $sale->warehouse_id,
                    'branch_id'    => $sale->branch_id,
                    'ref_type'     => 'sale_void',
                    'ref_id'       => $sale->id,
                    'direction'    => 'IN',
                    'quantity'     => $item->quantity,
                    'unit_cost'    => $item->unit_price,
                    'note'         => 'Sale voided',
                    'created_by'   => auth()->id(),
                ]);

                // update stock_currents
                StockCurrent::where('product_id', $item->product_id)
                    ->where('warehouse_id', $sale->warehouse_id)
                    ->where('branch_id', $sale->branch_id)
                    ->increment('quantity', $item->quantity);
            }

            // 2️⃣ Update sale
            $sale->update([
                'status'         => 'void',
                'payment_status' => 'Refund',
                'sale_note'      => trim(($sale->sale_note ?? '') . ' | VOID'),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Sale voided successfully',
        ]);
    }

    //*** Show Sale Details ***

    // public function show(Sale $sale)
    // {
    //     $sale->load([
    //         'customer',
    //         'items.product',
    //         'payments.paymentType',
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'sale'    => [
    //             'invoice'  => $sale->invoice_no,
    //             'customer' => $sale->customer?->name ?? 'Walk In',
    //             'status'   => $sale->status,
    //             'subtotal' => $sale->subtotal,
    //             'discount' => $sale->discount,
    //             'shipping' => $sale->shipping_charge,
    //             'total'    => $sale->total,
    //             'paid'     => $sale->paid_amount,
    //             'items'    => $sale->items->map(fn($i) => [
    //                 'name'  => $i->product->name,
    //                 'qty'   => $i->quantity,
    //                 'price' => $i->unit_price,
    //                 'total' => $i->line_total,
    //             ]),
    //             'payments' => $sale->payments->map(fn($p) => [
    //                 'method' => $p->payment_type,
    //                 'amount' => $p->amount,
    //             ]),
    //         ],
    //     ]);
    // }

    public function show(Sale $sale)
    {

        if (! auth()->user()->isSuperAdmin() &&
            $sale->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        $sale->load([
            'customer',
            'items.product',
            'payments.paymentType',
            'user',
        ]);

        return view('backend.modules.pos.saleDetailsModal', compact('sale'));
    }

    //*** Generate Invoice ***
    public function invoice(Sale $sale)
    {
        $sale->load([
            'items.product',
            'payments',
            'customer',
            'branch',
            'warehouse',
        ]);

        return view('backend.modules.pos.invoice', compact('sale'));
    }

    //*** Fetch Today's Transactions ***

    public function todayTransactions()
    {
        $user = auth()->user();

        $query = SalePayment::with(['sale.user'])
            ->whereDate('paid_at', Carbon::today());

        // -----------------------------------
        // 🔐 Branch-wise access control
        // -----------------------------------
        if (! $user->isSuperAdmin()) {
            $query->whereHas('sale', function ($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            });
        }

        //  Exclude void / refunded sales
        $query->whereHas('sale', function ($q) {
            $q->where('status', 'delivered'); // only completed sales
        });

        // Fetch + Format
        $transactions = $query
            ->orderByDesc('paid_at')
            ->get()
            ->map(function ($p) {
                return [
                    'invoice' => $p->sale?->invoice_no ?? '-',
                    'method'  => strtolower($p->payment_type), // cash/card/bkash
                    'amount'  => (float) $p->amount,
                    'time'    => optional($p->paid_at)->format('H:i'),
                    'user'    => $p->sale?->user?->name ?? 'System',
                    'branch'  => $p->sale?->branch?->name ?? '-',
                ];
            });

        return response()->json([
            'success'      => true,
            'transactions' => $transactions,
        ]);
    }

    //*** Fetch Product by Barcode ***
    public function productByBarcode(Request $request)
    {
        $product = Product::where('barcode', $request->barcode)
            ->where('is_sellable', 1)
            ->first();

        if (! $product) {
            return response()->json([
                'success' => false,
            ]);
        }

        return response()->json([
            'success' => true,
            'product' => [
                'id'      => $product->id,
                'name'    => $product->name,
                'price'   => $product->price,
                'mrp'     => $product->mrp,
                'unit_id' => $product->unit_id,
            ],
        ]);
    }

    //--------------------------------
    //*** Sales List View & Ajax ***
    //--------------------------------

    public function list()
    {
        return view('backend.modules.pos.list');
    }

    public function listAjax(Request $request)
    {
        $columns = [
            'id',
            'invoice_no',
            'customer_name',
            'total',
            'paid_amount',
            'due_amount',
            'status',
            'payment_status',
            'created_at',
        ];

        $draw     = (int) $request->input('draw');
        $start    = (int) $request->input('start', 0);
        $length   = (int) $request->input('length', 10);
        $orderIdx = (int) $request->input('order.0.column', 0);
        $orderDir = strtolower($request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $search   = trim($request->input('search.value', ''));

        $user = auth()->user();

        $base = Sale::with(['customer', 'user'])
            ->select([
                'sales.id',
                'sales.invoice_no',
                'sales.customer_id',
                'sales.total',
                'sales.paid_amount',
                'sales.due_amount',
                'sales.status',
                'sales.payment_status',
                'sales.created_at',
                'sales.branch_id',
                'sales.user_id',
            ]);

        // 🔐 Branch restriction
        if (! $user->isSuperAdmin()) {
            $base->where('sales.branch_id', $user->branch_id);
        }

        $total = (clone $base)->count();

        if ($search !== '') {
            $base->where(function ($q) use ($search) {
                $q->where('sales.invoice_no', 'like', "%{$search}%")
                    ->orWhereHas('customer', fn($c) =>
                        $c->where('name', 'like', "%{$search}%")
                    );
            });
        }

        $filtered = (clone $base)->count();

        $orderCol = $columns[$orderIdx] ?? 'id';
        $base->orderBy($orderCol, $orderDir);

        $rows = $base->skip($start)->take($length)->get();

        $data = [];

        foreach ($rows as $r) {

            $statusBadge = match ($r->status) {
                'hold'      => '<span class="badge text-sm fw-semibold bg-warning-600 px-20 py-9 radius-4 text-white">Hold</span>',
                'delivered' => '<span class="badge text-sm fw-semibold bg-success-600 px-20 py-9 radius-4 text-white">Delivered</span>',
                'void'      => '<span class="badge text-sm fw-semibold bg-danger-600 px-20 py-9 radius-4 text-white">Void</span>',
                default     => '<span class="badge text-sm fw-semibold bg-lilac-600 px-20 py-9 radius-4 text-white">' . e($r->status) . '</span>',
            };

            $payBadge = match ($r->payment_status) {
                'paid'  => '<span class="badge text-sm fw-semibold rounded-pill bg-success-600 px-20 py-9 radius-4 text-white">Paid</span>',
                'due'   => '<span class="badge text-sm fw-semibold rounded-pill bg-warning-600 px-20 py-9 radius-4 text-white">Due</span>',
                default => '<span class="badge text-sm fw-semibold rounded-pill bg-lilac-600 px-20 py-9 radius-4 text-white">' . e($r->payment_status) . '</span>',
            };

            // $actions = '
            // <div class="d-inline-flex justify-content-end gap-1">
            //     <a href="' . route('pos.sales.invoice', $r->id) . '" target="_blank"
            //        class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center
            //        bg-info-focus text-info-main" title="Invoice">
            //        <iconify-icon icon="mdi:printer-outline"></iconify-icon>
            //     </a>

            //     <a href="#"
            //     class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center
            //             bg-info-focus text-info-main AjaxViewModal"
            //     title="View Sale"
            //     data-size="lg"
            //     data-ajax-modal="' . route('pos.sales.show', $r->id) . '">
            //         <iconify-icon icon="lucide:eye"></iconify-icon>
            //     </a>
            // </div>';

            $actions = '<div class="d-inline-flex justify-content-end gap-1">';

            /* 🖨 Invoice */
            $actions .= '
                    <a href="' . route('pos.sales.invoice', $r->id) . '" target="_blank"
                    class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center
                    bg-info-focus text-info-main" title="Invoice">
                    <iconify-icon icon="mdi:printer-outline"></iconify-icon>
                    </a>';

            /* 👁 View (Ajax Modal) */
            $actions .= '
                    <a href="#"
                    class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center
                            bg-info-focus text-info-main AjaxViewModal"
                    title="View Sale"
                    data-size="lg"
                    data-ajax-modal="' . route('pos.sales.show', $r->id) . '">
                        <iconify-icon icon="lucide:eye"></iconify-icon>
                    </a>';

            /* ✏️ Edit / Resume (if on HOLD) */
            if ($r->status === 'hold') {
                $actions .= '
                        <a href="#"
                        class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center
                                bg-warning-focus text-warning-main btn-resume-sale"
                        title="Edit / Resume Sale"
                        data-id="' . $r->id . '">
                            <iconify-icon icon="mdi:pencil-outline"></iconify-icon>
                        </a>';
            }
            $actions .= '</div>';

            $data[] = [
                $r->id,
                '<strong>' . $r->invoice_no . '</strong>',
                e($r->customer?->name ?? 'Walk In'),
                number_format($r->total, 2),
                number_format($r->paid_amount, 2),
                number_format($r->due_amount, 2),
                $statusBadge,
                $payBadge,
                $r->created_at->format('Y-m-d H:i'),
                e($r->user?->name ?? '-'),
                $actions,
            ];
        }

        return response()->json([
            'draw'                 => $draw,
            'iTotalRecords'        => $total,
            'iTotalDisplayRecords' => $filtered,
            'aaData'               => $data,
        ]);
    }

}
