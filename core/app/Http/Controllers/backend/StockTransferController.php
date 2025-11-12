<?php
namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\StockLedger;
use App\Models\backend\StockTransfer;
use App\Models\backend\StockTransferItem;
use App\Models\backend\Warehouse;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockTransferController extends Controller
{
    public function index()
    {
        return view('backend.modules.inventory.stockTransfer.index');
    }

    // public function listAjax(Request $request)
    // {
    //     $columns   = ['txn_date', 'from_wh', 'to_wh', 'product', 'qty', 'by', 'note'];
    //     $draw      = (int) $request->input('draw');
    //     $start     = (int) $request->input('start', 0);
    //     $length    = (int) $request->input('length', 10);
    //     $orderIdx  = (int) $request->input('order.0.column', 0);
    //     $orderDir  = strtolower($request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
    //     $searchVal = trim($request->input('search.value', ''));

    //     // আমরা transfer লেনদেনকে pair হিসেবে রাখি: OUT + IN (same ref)
    //     // লিস্টে দেখানোর জন্য pair JOIN/aggregate করতে পারেন।
    //     // সহজে: OUT লাইনকে ধরুন (direction='OUT'), সাথে matching IN warehouse দেখান।
    //     $base = StockLedger::query()
    //         ->from('stock_ledgers as outl')
    //         ->where('outl.ref_type', 'transfer')
    //         ->where('outl.direction', 'OUT')
    //         ->leftJoin('stock_ledgers as inl', function ($j) {
    //             $j->on('inl.ref_type', '=', DB::raw("'transfer'"))
    //                 ->on('inl.ref_id', '=', 'outl.ref_id')
    //                 ->on('inl.product_id', '=', 'outl.product_id')
    //                 ->on('inl.direction', '=', DB::raw("'IN'"));
    //         })
    //         ->leftJoin('warehouses as wfrom', 'wfrom.id', '=', 'outl.warehouse_id')
    //         ->leftJoin('warehouses as wto', 'wto.id', '=', 'inl.warehouse_id')
    //         ->leftJoin('products as p', 'p.id', '=', 'outl.product_id')
    //         ->leftJoin('users as u', 'u.id', '=', 'outl.created_by')
    //         ->selectRaw("
    //             outl.id,
    //             outl.txn_date,
    //             outl.quantity,
    //             outl.note,
    //             p.name as product_name,
    //             p.sku  as product_sku,
    //             wfrom.name as from_wh,
    //             wto.name   as to_wh,
    //             u.name     as created_by_name
    //         ");

    //     $total = (clone $base)->count();

    //     if ($searchVal !== '') {
    //         $base->where(function ($q) use ($searchVal) {
    //             $q->where('p.name', 'like', "%{$searchVal}%")
    //                 ->orWhere('p.sku', 'like', "%{$searchVal}%")
    //                 ->orWhere('wfrom.name', 'like', "%{$searchVal}%")
    //                 ->orWhere('wto.name', 'like', "%{$searchVal}%")
    //                 ->orWhere('outl.note', 'like', "%{$searchVal}%");
    //         });
    //     }

    //     $filtered = (clone $base)->count();

    //     $orderMap = [
    //         'txn_date' => 'outl.txn_date',
    //         'from_wh'  => 'wfrom.name',
    //         'to_wh'    => 'wto.name',
    //         'product'  => 'p.name',
    //         'qty'      => 'outl.quantity',
    //         'by'       => 'u.name',
    //         'note'     => 'outl.note',
    //     ];
    //     $orderCol = $columns[$orderIdx] ?? 'txn_date';
    //     $base->orderBy($orderMap[$orderCol] ?? 'outl.txn_date', $orderDir);

    //     $rows = $base->skip($start)->take($length)->get();

    //     $data = [];
    //     foreach ($rows as $r) {
    //         $prod   = '<div><strong>' . e($r->product_name ?? '—') . '</strong><br><code>' . e($r->product_sku ?? '') . '</code></div>';
    //         $data[] = [
    //             optional($r->txn_date)->format('Y-m-d H:i'),
    //             e($r->from_wh ?? '—'),
    //             e($r->to_wh ?? '—'),
    //             $prod,
    //             number_format((float) $r->quantity, 3),
    //             e($r->created_by_name ?? '—'),
    //             e($r->note ?? '—'),
    //             '<div class="text-end"></div>',
    //         ];
    //     }

    //     return response()->json([
    //         'draw'                 => $draw,
    //         'iTotalRecords'        => $total,
    //         'iTotalDisplayRecords' => $filtered,
    //         'aaData'               => $data,
    //     ]);
    // }

    public function listAjax(Request $request)
    {
        $columns   = ['txn_date', 'from_wh', 'to_wh', 'product', 'qty', 'status', 'by', 'note'];
        $draw      = (int) $request->input('draw');
        $start     = (int) $request->input('start', 0);
        $length    = (int) $request->input('length', 10);
        $orderIdx  = (int) $request->input('order.0.column', 0);
        $orderDir  = strtolower($request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $searchVal = trim($request->input('search.value', ''));

        // Base Eloquent query: pulls StockTransfer and eager loads relations we need
        $base = StockTransfer::with([
            'fromWarehouse:id,name',
            'toWarehouse:id,name',
            'creator:id,name',
            'items.product:id,name,sku',
        ]);

        // only show transfers that have related ledger entries?
        // (optional) if you want only posted transfers, uncomment:
        // $base->where('status', 'POSTED');

        // global search across product name/sku, warehouses, note
        if ($searchVal !== '') {
            $base->where(function ($q) use ($searchVal) {
                $q->where('reference_no', 'like', "%{$searchVal}%")
                    ->orWhere('note', 'like', "%{$searchVal}%")
                    ->orWhereHas('fromWarehouse', function ($qq) use ($searchVal) {
                        $qq->where('name', 'like', "%{$searchVal}%");
                    })
                    ->orWhereHas('toWarehouse', function ($qq) use ($searchVal) {
                        $qq->where('name', 'like', "%{$searchVal}%");
                    })
                    ->orWhereHas('items.product', function ($qq) use ($searchVal) {
                        $qq->where('name', 'like', "%{$searchVal}%")
                            ->orWhere('sku', 'like', "%{$searchVal}%");
                    })
                    ->orWhereHas('creator', function ($qq) use ($searchVal) {
                        $qq->where('name', 'like', "%{$searchVal}%");
                    });
            });
        }

        // total & filtered counts
        $total    = StockTransfer::count();
        $filtered = (clone $base)->count();

        // ordering: map datatable column to actual column
        $orderMap = [
            'txn_date' => 'transfer_date',
            'from_wh'  => 'from_warehouse_id',
            'to_wh'    => 'to_warehouse_id',
            'product'  => null, // will handle ordering by product name via join-like approach if needed
            'qty'      => null, // qty is aggregated from items, ordering here is not trivial
            'status'   => 'status',
            'by'       => 'created_by',
            'note'     => 'note',
        ];
        $orderColKey = $columns[$orderIdx] ?? 'txn_date';
        $orderCol    = $orderMap[$orderColKey] ?? null;

        if ($orderCol) {
            $base->orderBy($orderCol, $orderDir);
        } else {
            // fallback ordering — by transfer_date
            $base->orderBy('transfer_date', $orderDir);
        }

        // pagination & fetch
        $transfers = $base->skip($start)->take($length)->get();

        $data = [];
        foreach ($transfers as $t) {
            // product display: list first product or aggregated summary
            $productHtml = '';
            if ($t->items->isNotEmpty()) {
                // show first line product prominently, and indicate total lines
                $first       = $t->items->first()->product;
                $prodName    = $first ? e($first->name) : '—';
                $prodSku     = $first ? e($first->sku) : '';
                $lines       = $t->items->count();
                $productHtml = "<div><strong>{$prodName}</strong><br><code>{$prodSku}</code>";
                if ($lines > 1) {
                    $productHtml .= "<div class='text-muted small'>+ " . ($lines - 1) . " more</div>";
                }
                $productHtml .= "</div>";
            } else {
                $productHtml = '—';
            }

            // total qty (sum of items)
            $totalQty = $t->items->sum(function ($it) {
                return (float) ($it->quantity ?? 0);
            });
            $status = e($t->status ?? '—');
            $status = strtoupper($t->status) === 'DRAFT' ? '<span class="border px-24 py-4 radius-4 fw-medium text-sm  border-warning-main bg-warning-focus text-warning-600">DRAFT</span>' : '<span class="border px-24 py-4 radius-4 fw-medium text-sm  border-success-main bg-success-focus text-success-600">POSTED</span>';

            // action buttons example: View / Post (if DRAFT) / Delete (if allowed)
            $actions = '<div class="text-end gap-1 d-flex justify-content-end">';

            $actions .= '<a href="' . route('inventory.transfers.show', $t->id) . '" class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center
                                bg-info-focus text-info-main" title="view">
                                <iconify-icon icon="material-symbols:undereye-outline"  class="text-lg"></iconify-icon></a>';
            // If DRAFT → show Edit and Post buttons
            if (strtoupper($t->status) === 'DRAFT') {
                // Edit button
                $actions .= '<a href="' . route('inventory.transfers.edit', $t->id) . '"
                    class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center
                                bg-success-focus text-success-main" title="Edit">
                    <iconify-icon icon="lucide:edit"  class="text-lg"></iconify-icon>
                 </a>';

                // Post button
                $actions .= '<a href="#" data-url="' . route('inventory.transfers.post', $t->id) . '"
                    class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center
                           bg-success-focus text-success-main btn-transfer-post"
                    title="Approve & Post">
                    <iconify-icon icon="solar:check-circle-outline" class="text-xl"></iconify-icon>
                 </a>';

                // Delete button
                $actions .= '<a href="#" data-url="' . route('inventory.transfers.destroy', $t->id) . '"
                    class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center
                           bg-danger-focus text-danger-main btn-transfer-delete"
                    title="Delete Transfer">
                    <iconify-icon icon="solar:trash-bin-trash-outline" class="text-lg"></iconify-icon>
                 </a>';
            } else {
                $actions .= '';
            }
            $actions .= '</div>';

            $data[] = [
                // columns order must match DataTables column definitions
                optional($t->transfer_date)->format('Y-m-d H:i'),
                e(optional($t->fromWarehouse)->name ?? '—'),
                e(optional($t->toWarehouse)->name ?? '—'),
                $productHtml,
                number_format((float) $totalQty, 3),
                $status,
                e(optional($t->creator)->name ?? '—'),
                e($t->note ?? '—'),
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

    public function create()
    {
        $warehouses = Warehouse::select('id', 'name')->orderBy('name')->get();

        $userBranchId = optional(Auth::user())->branch_id ?? null;

        return view('backend.modules.inventory.stockTransfer.create', compact('warehouses', 'userBranchId'));
    }

    // public function store(Request $r, StockService $stock)
    // {
    //     $data = $r->validate([
    //         'from_warehouse_id' => 'required|different:to_warehouse_id|exists:warehouses,id',
    //         'to_warehouse_id'   => 'required|exists:warehouses,id',
    //         'rows'              => 'required|array|min:1',
    //         'rows.*.product_id' => 'required|exists:products,id',
    //         'rows.*.qty'        => 'required|numeric|min:0.001',
    //         'note'              => 'nullable|string|max:500',
    //     ]);

    //     DB::transaction(function () use ($data, $stock) {
    //         // চাইলে এখানে একটি transfer header row বানিয়ে তার id কে ref_id দিন
    //         $ref = ['type' => 'transfer', 'id' => null];

    //         foreach ($data['rows'] as $row) {
    //             $pid = (int) $row['product_id'];
    //             $qty = (float) $row['qty'];

    //             // OUT from source
    //             $stock->apply($pid, (int) $data['from_warehouse_id'], 'OUT', $qty, null, $ref, auth()->id(), $data['note'] ?? null);

    //             // IN to target
    //             $stock->apply($pid, (int) $data['to_warehouse_id'], 'IN', $qty, null, $ref, auth()->id(), $data['note'] ?? null);
    //         }
    //     });

    //     return response()->json(['success' => true, 'msg' => 'Transfer completed']);
    // }

    // public function store(Request $request)
    // {

    //     $data = $request->validate([
    //         'from_warehouse_id' => 'required|integer|exists:warehouses,id',
    //         'to_warehouse_id'   => 'required|integer|exists:warehouses,id',
    //         'transfer_date'     => 'nullable|date',
    //         'reference_no'      => 'nullable|string|max:50',
    //         'note'              => 'nullable|string|max:500',
    //         'rows'              => 'required|array|min:1',
    //         'rows.*.product_id' => 'required|integer|exists:products,id',
    //         'rows.*.quantity'   => 'required|numeric|min:0.001',
    //         'rows.*.unit_cost'  => 'nullable|numeric',
    //     ]);

    //     $userId = auth()->id();
    //     $now    = now();

    //     DB::beginTransaction();
    //     try {
    //         // create header (Eloquent)
    //         $transfer = StockTransfer::create([
    //             'reference_no'      => $data['reference_no'] ?? null,
    //             'from_warehouse_id' => $data['from_warehouse_id'],
    //             'to_warehouse_id'   => $data['to_warehouse_id'],
    //             'transfer_date'     => $data['transfer_date'] ? $data['transfer_date'] : $now,
    //             'note'              => $data['note'] ?? null,
    //             'status'            => 'DRAFT',
    //             'created_by'        => $userId,
    //         ]);

    //         // items
    //         $items = [];
    //         foreach ($data['rows'] as $r) {
    //             $items[] = new StockTransferItem([
    //                 'product_id' => (int) $r['product_id'],
    //                 'quantity'   => (float) $r['quantity'],
    //                 'unit_cost'  => isset($r['unit_cost']) ? (float) $r['unit_cost'] : null,
    //             ]);
    //         }
    //         // save via relation
    //         $transfer->items()->saveMany($items);

    //         DB::commit();

    //         // optionally post immediately
    //         if (! empty($request->input('post_now'))) {
    //             $this->stockService->postTransfer($transfer->id, $userId);
    //             return $request->wantsJson() ? response()->json(['ok' => true, 'msg' => 'Transfer saved & posted']) : redirect()->route('inventory.transfers.index')->with('success', 'Saved & posted');
    //         }

    //         return $request->wantsJson() ? response()->json(['ok' => true, 'msg' => 'Transfer saved', 'transfer_id' => $transfer->id]) : redirect()->route('inventory.transfers.index')->with('success', 'Transfer saved');

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         \Log::error('Transfer store error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
    //         return $request->wantsJson() ? response()->json(['ok' => false, 'msg' => $e->getMessage()], 500) : back()->withInput()->with('error', 'Save failed: ' . $e->getMessage());
    //     }

    // }

    public function store(Request $request)
    {
        $data = $request->validate([
            'from_warehouse_id' => 'required|integer|exists:warehouses,id',
            'to_warehouse_id'   => 'required|integer|exists:warehouses,id',
            'transfer_date'     => 'nullable|date',
            'reference_no'      => 'nullable|string|max:50',
            'note'              => 'nullable|string|max:500',
            'from_branch_id'    => 'nullable|integer|exists:branches,id',
            'to_branch_id'      => 'nullable|integer|exists:branches,id',
            'rows'              => 'required|array|min:1',
            'rows.*.product_id' => 'required|integer|exists:products,id',
            'rows.*.quantity'   => 'required|numeric|min:0.001',
            'rows.*.unit_cost'  => 'nullable|numeric',
            'rows.*.note'       => 'nullable|string',
        ]);

        $userId = auth()->id();
        $now    = now();

        DB::beginTransaction();
        try {
            // derive warehouses & their branches as fallback
            $fromWh = Warehouse::find($data['from_warehouse_id']);
            $toWh   = Warehouse::find($data['to_warehouse_id']);

            $fromBranch = $data['from_branch_id'] ?? ($fromWh->branch_id ?? null);
            $toBranch   = $data['to_branch_id'] ?? ($toWh->branch_id ?? null);

            $transfer = StockTransfer::create([
                'reference_no'      => $data['reference_no'] ?? null,
                'from_warehouse_id' => $data['from_warehouse_id'],
                'to_warehouse_id'   => $data['to_warehouse_id'],
                'from_branch_id'    => $fromBranch,
                'to_branch_id'      => $toBranch,
                'transfer_date'     => $data['transfer_date'] ?? $now,
                'note'              => $data['note'] ?? null,
                'status'            => 'DRAFT',
                'created_by'        => $userId,
            ]);

            // prepare items
            $items = [];
            foreach ($data['rows'] as $r) {
                $items[] = new StockTransferItem([
                    'product_id' => (int) $r['product_id'],
                    'quantity'   => (float) $r['quantity'],
                    'unit_cost'  => isset($r['unit_cost']) ? (float) $r['unit_cost'] : null,
                    'note'       => $r['note'] ?? null,
                ]);
            }

            $transfer->items()->saveMany($items);

            DB::commit();

            return redirect()->route('inventory.transfers.index')->with('success', 'Transfer saved (DRAFT).');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Transfer store error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Save failed: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $transfer = StockTransfer::with([
            'items.product',
            'fromWarehouse.branch',
            'toWarehouse.branch',
            'creator',
        ])->findOrFail($id);

        // fetch related ledger entries for this transfer (eager load product & branch)
        $ledgers = StockLedger::with(['product', 'branch'])
            ->where('ref_type', 'TRANSFER')
            ->where('ref_id', $transfer->id)
            ->orderBy('txn_date')
            ->get();

        return view('backend.modules.inventory.stockTransfer.show', compact('transfer', 'ledgers'));
    }

    public function post($id, StockService $stock)
    {
        $userId = auth()->id();

        try {
            $stock->postTransfer($id, $userId);
            return response()->json(['ok' => true, 'msg' => 'Transfer posted']);
        } catch (\Exception $e) {
            \Log::error('Transfer post error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['ok' => false, 'msg' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $transfer = StockTransfer::with([
            'items.product',
            'fromWarehouse',
            'toWarehouse',
        ])->findOrFail($id);

        $warehouses = Warehouse::select('id', 'name')->get();

        // Prevent editing posted transfers
        if ($transfer->status !== 'DRAFT') {
            return redirect()->route('inventory.transfers.show', $transfer->id)
                ->with('error', 'Only DRAFT transfers can be edited.');
        }

        return view('backend.modules.inventory.stockTransfer.edit', compact('transfer', 'warehouses'));
    }

    public function update(Request $request, $id)
    {
        $transfer = StockTransfer::with('items')->findOrFail($id);

        if ($transfer->status !== 'DRAFT') {
            return redirect()->route('inventory.transfers.show', $id)
                ->with('error', 'Posted transfers cannot be edited.');
        }

        $data = $request->validate([
            'from_warehouse_id' => 'required|integer|exists:warehouses,id',
            'to_warehouse_id'   => 'required|integer|exists:warehouses,id',
            'transfer_date'     => 'nullable|date',
            'reference_no'      => 'nullable|string|max:50',
            'note'              => 'nullable|string|max:500',
            'rows'              => 'required|array|min:1',
            'rows.*.product_id' => 'required|integer|exists:products,id',
            'rows.*.quantity'   => 'required|numeric|min:0.001',
            'rows.*.unit_cost'  => 'nullable|numeric',
            'rows.*.note'       => 'nullable|string',
        ]);

        if (empty($data['transfer_date'])) {
            $data['transfer_date'] = now();
        }

        DB::beginTransaction();
        try {
            $transfer->update([
                'from_warehouse_id' => $data['from_warehouse_id'],
                'to_warehouse_id'   => $data['to_warehouse_id'],
                'transfer_date'     => $data['transfer_date'],
                'reference_no'      => $data['reference_no'],
                'note'              => $data['note'] ?? null,
            ]);

            // Purge old items and reinsert (simplest & clean)
            $transfer->items()->delete();

            $items = [];
            foreach ($data['rows'] as $r) {
                $items[] = new StockTransferItem([
                    'product_id' => (int) $r['product_id'],
                    'quantity'   => (float) $r['quantity'],
                    'unit_cost'  => isset($r['unit_cost']) ? (float) $r['unit_cost'] : null,
                    'note'       => $r['note'] ?? null,
                ]);
            }
            $transfer->items()->saveMany($items);

            DB::commit();

            return redirect()->route('inventory.transfers.show', $transfer->id)
                ->with('success', 'Transfer updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Transfer update error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }


    public function destroy(Request $request, $id)
    {
        try {
            $transfer = StockTransfer::findOrFail($id);

            if (strtoupper($transfer->status) !== 'DRAFT') {
                $msg = 'Only DRAFT transfers can be deleted.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['ok' => false, 'msg' => $msg], 400);
                }
                return back()->with('error', $msg);
            }

            DB::beginTransaction();
            $transfer->items()->delete();
            $transfer->delete();
            DB::commit();

            $msg = 'Transfer deleted successfully.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['ok' => true, 'msg' => $msg]);
            }
            return redirect()->route('inventory.transfers.index')->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Transfer delete error: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['ok' => false, 'msg' => 'Delete failed: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

}
