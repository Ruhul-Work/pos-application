<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{
      public function index()
    {
        return view('backend.modules.inventory.stockAdjustment.index');
    }

    public function listAjax(Request $request)
    {
        $columns   = ['txn_date','warehouse','product','qty','by','reason'];
        $draw      = (int) $request->input('draw');
        $start     = (int) $request->input('start', 0);
        $length    = (int) $request->input('length', 10);
        $orderIdx  = (int) $request->input('order.0.column', 0);
        $orderDir  = strtolower($request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $searchVal = trim($request->input('search.value', ''));

        $base = StockLedger::query()
            ->from('stock_ledgers as sl')
            ->where('sl.ref_type','adjustment')
            ->leftJoin('warehouses as w', 'w.id','=','sl.warehouse_id')
            ->leftJoin('products as p', 'p.id','=','sl.product_id')
            ->leftJoin('users as u', 'u.id','=','sl.created_by')
            ->selectRaw("
                sl.id, sl.txn_date, sl.direction, sl.quantity, sl.note,
                w.name as warehouse,
                p.name as product_name, p.sku as product_sku,
                u.name as created_by_name
            ");

        $total = (clone $base)->count();

        if ($searchVal !== '') {
            $base->where(function($q) use ($searchVal){
                $q->where('p.name','like',"%{$searchVal}%")
                  ->orWhere('p.sku','like',"%{$searchVal}%")
                  ->orWhere('w.name','like',"%{$searchVal}%")
                  ->orWhere('sl.note','like',"%{$searchVal}%");
            });
        }

        $filtered = (clone $base)->count();

        $orderMap = [
            'txn_date' => 'sl.txn_date',
            'warehouse'=> 'w.name',
            'product'  => 'p.name',
            'qty'      => 'sl.quantity',
            'by'       => 'u.name',
            'reason'   => 'sl.note',
        ];
        $orderCol = $columns[$orderIdx] ?? 'txn_date';
        $base->orderBy($orderMap[$orderCol] ?? 'sl.txn_date', $orderDir);

        $rows = $base->skip($start)->take($length)->get();

        $data = [];
        foreach ($rows as $r) {
            $prod = '<div><strong>'.e($r->product_name ?? '—').'</strong><br><code>'.e($r->product_sku ?? '').'</code></div>';
            $qty  = ($r->direction === 'IN' ? '+' : '-') . number_format((float)$r->quantity, 3);
            $data[] = [
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

    public function store(Request $r, StockService $stock)
    {
        $data = $r->validate([
            'warehouse_id'        => 'required|exists:warehouses,id',
            'rows'                => 'required|array|min:1',
            'rows.*.product_id'   => 'required|exists:products,id',
            'rows.*.qty'          => 'required|numeric', // + => IN, - => OUT
            'rows.*.reason'       => 'nullable|string|max:255',
        ]);

        foreach ($data['rows'] as $row) {
            $qty = (float)$row['qty'];
            if ($qty == 0) continue;

            $stock->apply(
                (int)$row['product_id'],
                (int)$data['warehouse_id'],
                $qty > 0 ? 'IN' : 'OUT',
                abs($qty),
                null,
                ['type' => 'adjustment', 'id' => null],
                auth()->id(),
                $row['reason'] ?? null
            );
        }

        return response()->json(['success'=>true,'msg'=>'Adjustment saved']);
    }
    
    
//     public function store(Request $r, StockService $stock)
// {
//     $data = $r->validate([
//         'warehouse_id' => 'required|exists:warehouses,id',
//         'rows' => 'required|array|min:1',
//         'rows.*.product_id' => 'required|exists:products,id',
//         'rows.*.qty'        => 'required|numeric', // +ve = IN, -ve = OUT
//         'rows.*.reason'     => 'nullable|string|max:255',
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

}