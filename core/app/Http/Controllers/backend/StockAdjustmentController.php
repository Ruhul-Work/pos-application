<?php
namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\StockLedger;
use App\Models\backend\Warehouse;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StockAdjustmentController extends Controller
{
    public function index()
    {
        return view('backend.modules.inventory.stockAdjustment.index');
    }

    public function listAjax(Request $request)
    {
        $columns   = ['txn_date', 'warehouse', 'product', 'qty', 'by', 'reason'];
        $draw      = (int) $request->input('draw');
        $start     = (int) $request->input('start', 0);
        $length    = (int) $request->input('length', 10);
        $orderIdx  = (int) $request->input('order.0.column', 0);
        $orderDir  = strtolower($request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $searchVal = trim($request->input('search.value', ''));

        $base = StockLedger::query()
            ->from('stock_ledgers as sl')
            ->where('sl.ref_type', 'adjustment')
            ->leftJoin('warehouses as w', 'w.id', '=', 'sl.warehouse_id')
            ->leftJoin('products as p', 'p.id', '=', 'sl.product_id')
            ->leftJoin('users as u', 'u.id', '=', 'sl.created_by')
            ->selectRaw("sl.id, sl.txn_date, sl.quantity, sl.direction, sl.unit_cost, sl.note,
                 p.name as product_name, p.sku as product_sku,
                 w.name as warehouse, u.name as created_by_name");

        if (function_exists('current_branch_id') && current_branch_id() !== null) {
            $base->where('sl.branch_id', current_branch_id()); // <- এখানে filter
        }

        $total = (clone $base)->count();

        if ($searchVal !== '') {
            $base->where(function ($q) use ($searchVal) {
                $q->where('p.name', 'like', "%{$searchVal}%")
                    ->orWhere('p.sku', 'like', "%{$searchVal}%")
                    ->orWhere('w.name', 'like', "%{$searchVal}%")
                    ->orWhere('sl.note', 'like', "%{$searchVal}%");
            });
        }

        $filtered = (clone $base)->count();

        $orderMap = [
            'txn_date'  => 'sl.txn_date',
            'warehouse' => 'w.name',
            'product'   => 'p.name',
            'direction' => 'sl.direction',
            'qty'       => 'sl.quantity',
            'by'        => 'u.name',
            'note'      => 'sl.note',
        ];

        $orderCol = $columns[$orderIdx] ?? 'txn_date';
        $base->orderBy($orderMap[$orderCol] ?? 'sl.txn_date', $orderDir);

        $rows = $base->skip($start)->take($length)->get();
        // dd($rows);

        $data = [];
        foreach ($rows as $r) {
            $prod   = '<div><strong>' . e($r->product_name ?? '—') . '</strong><br><code>' . e($r->product_sku ?? '') . '</code></div>';
            $qty    = ($r->direction === 'IN' ? '+' : '-') . number_format((float) $r->quantity, 3);
            $data[] = [
                '',
                optional($r->txn_date)->format('Y-m-d H:i'),
                e($r->warehouse ?? '—'),
                $prod,
                $qty,
                e($r->created_by_name ?? '—'),
                e($r->note ?? '—'),
                '<div class="text-end"></div>',
            ];
        }

        return response()->json([
            'draw'                 => $draw,
            'iTotalRecords'        => $total,
            'iTotalDisplayRecords' => $filtered,
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

}
