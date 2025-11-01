<?php
namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\Product;
use App\Models\backend\StockCurrent;
use App\Models\backend\StockLedger;
use App\Models\backend\Warehouse;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    public function index()
    {
        return view('backend.modules.inventory.stockAdjustment.index');
    }

    // public function listAjax(Request $request)
    // {
    //     $columns   = ['txn_date', 'warehouse', 'product', 'qty', 'by', 'reason'];
    //     $draw      = (int) $request->input('draw');
    //     $start     = (int) $request->input('start', 0);
    //     $length    = (int) $request->input('length', 10);
    //     $orderIdx  = (int) $request->input('order.0.column', 0);
    //     $orderDir  = strtolower($request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
    //     $searchVal = trim($request->input('search.value', ''));

    //     $base = StockLedger::query()
    //         ->from('stock_ledgers as sl')
    //         ->where('sl.ref_type', 'adjustment')
    //         ->leftJoin('warehouses as w', 'w.id', '=', 'sl.warehouse_id')
    //         ->leftJoin('products as p', 'p.id', '=', 'sl.product_id')
    //         ->leftJoin('users as u', 'u.id', '=', 'sl.created_by')
    //         ->selectRaw("sl.id, sl.txn_date, sl.quantity, sl.direction, sl.unit_cost, sl.note,
    //              p.name as product_name, p.sku as product_sku,
    //              w.name as warehouse, u.name as created_by_name");

    //     if (function_exists('current_branch_id') && current_branch_id() !== null) {
    //         $base->where('sl.branch_id', current_branch_id()); // <- এখানে filter
    //     }

    //     $total = (clone $base)->count();

    //     if ($searchVal !== '') {
    //         $base->where(function ($q) use ($searchVal) {
    //             $q->where('p.name', 'like', "%{$searchVal}%")
    //                 ->orWhere('p.sku', 'like', "%{$searchVal}%")
    //                 ->orWhere('w.name', 'like', "%{$searchVal}%")
    //                 ->orWhere('sl.note', 'like', "%{$searchVal}%");
    //         });
    //     }

    //     $filtered = (clone $base)->count();

    //     $orderMap = [
    //         'txn_date'  => 'sl.txn_date',
    //         'warehouse' => 'w.name',
    //         'product'   => 'p.name',
    //         'direction' => 'sl.direction',
    //         'qty'       => 'sl.quantity',
    //         'by'        => 'u.name',
    //         'note'      => 'sl.note',
    //     ];

    //     $orderCol = $columns[$orderIdx] ?? 'txn_date';
    //     $base->orderBy($orderMap[$orderCol] ?? 'sl.txn_date', $orderDir);

    //     $rows = $base->skip($start)->take($length)->get();
    //     // dd($rows);

    //     $data = [];
    //     foreach ($rows as $r) {
    //         $prod = '<div><strong>' . e($r->product_name ?? '—') . '</strong><br><code>' . e($r->product_sku ?? '') . '</code></div>';
    //         $qty  = ($r->direction === 'IN' ? '+' : '-') . number_format((float) $r->quantity, 3);

    //         $editUrl = route('inventory.adjustments.edit', $r->id);
    //         $delUrl  = route('inventory.adjustments.destroy', $r->id);

    //         $actions = '<div class="d-inline-flex justify-content-end gap-1 w-100">
    //                         <a href="' . $editUrl . '" class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center
    //                             bg-success-focus text-success-main"
    //                             title="Edit">
    //                             <iconify-icon icon="lucide:edit"></iconify-icon>
    //                         </a>
    //                         <a href="#" class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center
    //                             bg-danger-focus text-danger-main btn-adjust-delete"
    //                             data-url="' . $delUrl . '"
    //                             title="Delete">
    //                             <iconify-icon icon="mdi:delete"></iconify-icon>
    //                         </a>
    //                     </div>';
    //         $data[] = [
    //             '',
    //             optional($r->txn_date)->format('Y-m-d H:i'),
    //             e($r->warehouse ?? '—'),
    //             $prod,
    //             $qty,
    //             e($r->created_by_name ?? '—'),
    //             e($r->note ?? '—'),
    //             $actions,
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
        $columns   = ['parent', 'sku', 'variants', 'action'];
        $draw      = (int) $request->input('draw', 1);
        $start     = (int) $request->input('start', 0);
        $length    = (int) $request->input('length', 10);
        $orderIdx  = (int) $request->input('order.0.column', 0);
        $orderDir  = strtolower($request->input('order.0.dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        $searchVal = trim($request->input('search.value', ''));

        $base = Product::query()
            ->from('products as pp')
            ->whereNull('pp.parent_id')
            ->select([
                'pp.id', 'pp.name', 'pp.sku', 'pp.image',
                DB::raw('(select count(1)
                  from products c
                  where c.parent_id = pp.id
                    and c.is_sellable = 1) as variants_count'),
            ]);
        // ->withCount(['children as variants_count' => fn($q) => $q->where('is_sellable', 1)]);

        if ($searchVal !== '') {
            $base->where(function ($q) use ($searchVal) {
                $q->where('pp.name', 'like', "%{$searchVal}%")
                    ->orWhere('pp.sku', 'like', "%{$searchVal}%");
            });
        }

        $total = (clone $base)->count();

        $orderMap = ['parent' => 'pp.name', 'sku' => 'pp.sku', 'variants' => 'variants_count', 'action' => 'pp.id'];
        $orderCol = $columns[$orderIdx] ?? 'parent';
        $rows     = $base->orderBy($orderMap[$orderCol] ?? 'pp.name', $orderDir)
            ->skip($start)->take($length)->get();

        $data = [];
        foreach ($rows as $p) {
            $editUrl = route('inventory.adjustments.parent.edit', $p->id);
            $delUrl  = route('inventory.adjustments.destroy', $p->id);

            $parentCol = '<div class="d-flex gap-10">
                        <img src="' . e(image($p->image ?? asset('images/placeholder.png'))) . '" style="width:36px;height:36px;object-fit:cover;border-radius:6px">
                        <div>
                            <div class="fw-semibold">' . e($p->name) . '</div>
                            <small class="text-muted">' . e($p->sku ?? '') . '</small>
                        </div>
                      </div>';
            $action = '<div class="d-inline-flex justify-content-end gap-1 w-100">
                            <a href="' . $editUrl . '" class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center
                                bg-success-focus text-success-main"
                                title="Edit">
                                <iconify-icon icon="lucide:edit"></iconify-icon>
                            </a>
                            <a href="#" data-url="' . $delUrl . '" class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center
                                bg-danger-focus text-danger-main btn-adjust-delete"
                                title="Delete">
                                <iconify-icon icon="mdi:delete"></iconify-icon>
                        </div>';

            $data[] = [
                '',
                $parentCol,
                e($p->sku ?? '—'),
                (int) $p->variants_count,
                $action,
            ];
        }

        return response()->json([
            'draw'                 => $draw,
            'iTotalRecords'        => $total,
            'iTotalDisplayRecords' => $total,
            'aaData'               => $data,
        ]);
    }

    public function createModal()
    {
        return view('backend.modules.inventory.stockAdjustment.modal');
    }

    public function create()
    {
        return view('backend.modules.inventory.stockAdjustment.create');
    }

    // public function store(Request $r, StockService $stock)
    // {
    //     $data = $r->validate([
    //         'warehouse_id'        => 'required|exists:warehouses,id',
    //         'rows'                => 'required|array|min:1',
    //         'rows.*.product_id'   => 'required|exists:products,id',
    //         'rows.*.qty'          => 'required|numeric', // + => IN, - => OUT
    //         'rows.*.reason'       => 'nullable|string|max:255',
    //     ]);

    //     foreach ($data['rows'] as $row) {
    //         $qty = (float)$row['qty'];
    //         if ($qty == 0) continue;

    //         $stock->apply(
    //             (int)$row['product_id'],
    //             (int)$data['warehouse_id'],
    //             $qty > 0 ? 'IN' : 'OUT',
    //             abs($qty),
    //             null,
    //             ['type' => 'adjustment', 'id' => null],
    //             auth()->id(),
    //             $row['reason'] ?? null
    //         );
    //     }

    //     return response()->json(['success'=>true,'msg'=>'Adjustment saved']);
    // }

    public function store(Request $r, StockService $stock)
    {
        $data = $r->validate([
            'warehouse_id'      => ['required', 'integer', 'exists:warehouses,id'],
            'global_reason'     => ['nullable', 'string', 'max:255'],
            'rows'              => ['required', 'array', 'min:1'],
            'rows.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'rows.*.qty'        => ['required', 'numeric', 'not_in:0'],
            'rows.*.reason'     => ['nullable', 'string', 'max:255'],
            'when'              => ['nullable', 'date'], // চাইলে পাঠাতে পারেন
        ]);

        // warehouse belongs to current branch?
        $wh = Warehouse::select('id', 'branch_id')->findOrFail($data['warehouse_id']);
        if (function_exists('current_branch_id')) {
            $curr = current_branch_id();
            if ($curr !== null && (int) $curr !== (int) $wh->branch_id) {
                abort(403, 'Selected warehouse does not belong to current branch.');
            }
        }

        $when         = ! empty($data['when']) ? Carbon::parse($data['when']) : now();
        $globalReason = $data['global_reason'] ?? null;

        // (optional) master record তৈরি করলে তার id রেফারেন্স হিসেবে পাঠাতে পারেন
        $ref = ['type' => 'adjustment', 'id' => null];

        foreach ($data['rows'] as $row) {
            $qty       = (float) $row['qty'];
            $direction = $qty >= 0 ? 'IN' : 'OUT';

            $stock->apply(
                productId: (int) $row['product_id'],
                warehouseId: (int) $wh->id,
                direction: $direction,
                qty: abs($qty),
                unitCost: null, // adjustment-এ সাধারণত null
                ref: $ref,
                userId: auth()->id(),
                note: $row['reason'] ?: $globalReason,
                when: $when
            );
        }

        return response()->json(['success' => true, 'msg' => 'Adjustment saved']);
    }

    // public function editModal(StockLedger $ledger)
    // {
    //     // guard: শুধুই adjustment লাইনে এডিট
    //     abort_unless($ledger->ref_type === 'adjustment', 404);

    //     // product/warehouse দরকার হলে eager
    //     $ledger->load(['product:id,name,sku,image', 'warehouse:id,name']);
    //     return view('backend.modules.inventory.stockAdjustment.modal_edit', compact('ledger'));
    // }

    // public function editParent(int $parentId)
    // {
    //     $parent = Product::query()
    //         ->whereNull('parent_id')
    //         ->where('is_sellable', 0)
    //         ->select('id','name','sku','is_sellable','image')
    //         ->findOrFail($parentId);

    //     return view('backend.modules.inventory.stockAdjustment.edit', compact('parent'));
    // }

    public function editParent(int $id)
    {
        $root = Product::query()
            ->whereNull('parent_id')
            ->select('id', 'name', 'sku', 'is_sellable', 'image')
            ->findOrFail($id);

        return view('backend.modules.inventory.stockAdjustment.edit', [
            'parent' => $root,
        ]);
    }

    public function ajaxParentVariants(Request $r)
    {
        $r->validate([
            'parent_id'    => ['required', 'integer', 'exists:products,id'],
            'warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
        ]);

        $parent = Product::select('id', 'is_sellable', 'name', 'sku', 'image', 'parent_id', 'cost_price')
            ->whereNull('parent_id')->findOrFail((int) $r->parent_id);

        $parentId    = (int) $r->parent_id;
        $warehouseId = (int) $r->warehouse_id;

        $wh = Warehouse::select('id', 'branch_id')->findOrFail($warehouseId);
        if (function_exists('current_branch_id') && current_branch_id() !== null) {
            abort_if((int) $wh->branch_id !== (int) current_branch_id(), 403);
        }

        if ((int) $parent->is_sellable === 1) {
            $ids      = collect([$parent->id]);
            $variants = collect([(object) [
                'id'         => $parent->id,
                'name'       => $parent->name,
                'sku'        => $parent->sku,
                'image'      => $parent->image,
                'cost_price' => $parent->cost_price,

            ]]);
        } else {

            $variants = Product::query()
                ->where('parent_id', $parent->id)
                ->where('is_sellable', 1)
                ->select('id', 'name', 'sku', 'image', 'cost_price')
                ->orderBy('name')->get();
            $ids = $variants->pluck('id');
        }

        $currents = StockCurrent::query()
            ->where('warehouse_id', $warehouseId)
            ->when(function_exists('current_branch_id') && current_branch_id() !== null, fn($q) => $q->where('branch_id', current_branch_id()))
            ->whereIn('product_id', $variants->pluck('id'))
            ->get(['product_id', 'quantity'])->keyBy('product_id');

        $last = StockLedger::query()
            ->where('ref_type', 'adjustment')
            ->where('warehouse_id', $warehouseId)
            ->when(function_exists('current_branch_id') && current_branch_id() !== null, fn($q) => $q->where('branch_id', current_branch_id()))
            ->whereIn('product_id', $variants->pluck('id'))
            ->orderByDesc('txn_date')->orderByDesc('id')
            ->get()->groupBy('product_id')->map->first();

        $data = [];
        foreach ($variants as $v) {
            $l      = $last->get($v->id);
            $signed = $l ? ($l->direction === 'IN' ? +$l->quantity : -$l->quantity) : null;

            $lastRecvCost = StockLedger::query()
                ->where('product_id', $v->id)
                ->when($warehouseId, fn($q) => $q->where('warehouse_id', $warehouseId))
                ->whereIn('ref_type', ['receive', 'opening'])
                ->whereNotNull('unit_cost')
                ->orderByDesc('txn_date')->orderByDesc('id')
                ->value('unit_cost');

            $prefillUnitCost = $l->unit_cost ?? $lastRecvCost ?? (is_null($v->cost_price) ? null : (float) $v->cost_price);

            $data[] = [
                'product_id'  => $v->id,
                'name'        => $v->name,
                'sku'         => $v->sku,
                'image'       => '' . image(($v->image)) . '',
                'current_qty' => (float) ($currents[$v->id]->quantity ?? 0),
                'ledger_id'   => $l->id ?? null,
                'qty_signed'  => $signed,
                'unit_cost'   => $prefillUnitCost ?? null,
                'reason'      => $l->note ?? null,
            ];
        }

        return response()->json(['success' => true, 'variants' => $data]);
    }
    // public function update(Request $r, StockLedger $ledger, StockService $stock)
    // {
    //     abort_unless($ledger->ref_type === 'adjustment', 404);

    //     $data = $r->validate([
    //         'qty'       => ['required', 'numeric'], // signed (+/-) allowed
    //         'unit_cost' => ['nullable', 'numeric'],
    //         'note'      => ['nullable', 'string', 'max:500'],
    //     ]);

    //     // পুরনো impact reverse + নতুনটা apply (atomic)
    //     $stock->reapplyAdjustment($ledger, $data['qty'], $data['unit_cost'] ?? null, $data['note'] ?? null, auth()->id());

    //     return response()->json(['success' => true, 'msg' => 'Adjustment updated']);
    // }

    public function updateParent(int $parentId, Request $r, StockService $stock)
    {
        $data = $r->validate([
            'warehouse_id'      => ['required', 'integer', 'exists:warehouses,id'],
            'when'              => ['nullable', 'date'],
            'rows'              => ['required', 'array', 'min:1'],
            'rows.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'rows.*.ledger_id'  => ['nullable', 'integer', 'exists:stock_ledgers,id'],
            'rows.*.qty_signed' => ['required', 'numeric'],
            'rows.*.unit_cost'  => ['nullable', 'numeric', 'min:0'],
            'rows.*.reason'     => ['nullable', 'string', 'max:255'],
        ]);

        $wh = Warehouse::select('id', 'branch_id')->findOrFail($data['warehouse_id']);
        if (function_exists('current_branch_id') && current_branch_id() !== null) {
            abort_if((int) $wh->branch_id !== (int) current_branch_id(), 403);
        }

        $when = ! empty($data['when']) ? \Illuminate\Support\Carbon::parse($data['when']) : now();

        foreach ($data['rows'] as $row) {
            $qtySigned = (float) ($row['qty_signed'] ?? 0);
            if ($qtySigned == 0) {
                continue;
            }
            $dir      = $qtySigned >= 0 ? 'IN' : 'OUT';
            $absQty   = abs($qtySigned);
            $unitCost = $row['unit_cost'] ?? null;
            $reason   = $row['reason'] ?? null;

            if (! empty($row['ledger_id'])) {
                // overwrite existing
                $ledger = StockLedger::where('id', $row['ledger_id'])
                    ->where('ref_type', 'adjustment')->firstOrFail();

                abort_if((int) $ledger->warehouse_id !== (int) $wh->id, 422, 'Warehouse mismatch');

                $stock->reapplyAdjustment(
                    ledger: $ledger,
                    newQty: $qtySigned, // signed
                    newCost: $unitCost,
                    newNote: $reason,
                    userId: auth()->id()
                );

                $ledger->update(['txn_date' => $when]); // সময়ও আপডেট

            } else {
                // first-time adjustment for this variant
                $stock->apply(
                    productId: (int) $row['product_id'],
                    warehouseId: (int) $wh->id,
                    direction: $dir,
                    qty: $absQty,
                    unitCost: $unitCost,
                    ref: ['type' => 'adjustment', 'id' => null],
                    userId: auth()->id(),
                    note: $reason,
                    when: $when
                );
            }
        }

        return response()->json(['success' => true, 'msg' => 'Adjustment updated (parent-wide overwrite)']);
    }
 
    public function destroy(StockLedger $ledger, StockService $stock)
    {
        abort_unless($ledger->ref_type === 'adjustment', 404);

        $stock->deleteAdjustment($ledger, auth()->id()); // reverse effect then delete
        return response()->json(['success' => true, 'msg' => 'Adjustment deleted']);
    }

}
