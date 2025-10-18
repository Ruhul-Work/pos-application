<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StockLedger;
use App\Services\StockService;


class StockTransferController extends Controller
{    
     public function index()
    {
        return view('backend.modules.inventory.stockTransfer.index');
    }

    public function listAjax(Request $request)
    {
        $columns   = ['txn_date','from_wh','to_wh','product','qty','by','note'];
        $draw      = (int) $request->input('draw');
        $start     = (int) $request->input('start', 0);
        $length    = (int) $request->input('length', 10);
        $orderIdx  = (int) $request->input('order.0.column', 0);
        $orderDir  = strtolower($request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $searchVal = trim($request->input('search.value', ''));

        // আমরা transfer লেনদেনকে pair হিসেবে রাখি: OUT + IN (same ref)
        // লিস্টে দেখানোর জন্য pair JOIN/aggregate করতে পারেন।
        // সহজে: OUT লাইনকে ধরুন (direction='OUT'), সাথে matching IN warehouse দেখান।
        $base = StockLedger::query()
            ->from('stock_ledgers as outl')
            ->where('outl.ref_type', 'transfer')
            ->where('outl.direction', 'OUT')
            ->leftJoin('stock_ledgers as inl', function($j){
                $j->on('inl.ref_type', '=', DB::raw("'transfer'"))
                  ->on('inl.ref_id',   '=', 'outl.ref_id')
                  ->on('inl.product_id', '=', 'outl.product_id')
                  ->on('inl.direction', '=', DB::raw("'IN'"));
            })
            ->leftJoin('warehouses as wfrom', 'wfrom.id', '=', 'outl.warehouse_id')
            ->leftJoin('warehouses as wto',   'wto.id',   '=', 'inl.warehouse_id')
            ->leftJoin('products as p', 'p.id', '=', 'outl.product_id')
            ->leftJoin('users as u', 'u.id', '=', 'outl.created_by')
            ->selectRaw("
                outl.id,
                outl.txn_date,
                outl.quantity,
                outl.note,
                p.name as product_name,
                p.sku  as product_sku,
                wfrom.name as from_wh,
                wto.name   as to_wh,
                u.name     as created_by_name
            ");

        $total = (clone $base)->count();

        if ($searchVal !== '') {
            $base->where(function ($q) use ($searchVal) {
                $q->where('p.name','like',"%{$searchVal}%")
                  ->orWhere('p.sku','like',"%{$searchVal}%")
                  ->orWhere('wfrom.name','like',"%{$searchVal}%")
                  ->orWhere('wto.name','like',"%{$searchVal}%")
                  ->orWhere('outl.note','like',"%{$searchVal}%");
            });
        }

        $filtered = (clone $base)->count();

        $orderMap = [
            'txn_date' => 'outl.txn_date',
            'from_wh'  => 'wfrom.name',
            'to_wh'    => 'wto.name',
            'product'  => 'p.name',
            'qty'      => 'outl.quantity',
            'by'       => 'u.name',
            'note'     => 'outl.note',
        ];
        $orderCol = $columns[$orderIdx] ?? 'txn_date';
        $base->orderBy($orderMap[$orderCol] ?? 'outl.txn_date', $orderDir);

        $rows = $base->skip($start)->take($length)->get();

        $data = [];
        foreach ($rows as $r) {
            $prod = '<div><strong>'.e($r->product_name ?? '—').'</strong><br><code>'.e($r->product_sku ?? '').'</code></div>';
            $data[] = [
                optional($r->txn_date)->format('Y-m-d H:i'),
                e($r->from_wh ?? '—'),
                e($r->to_wh   ?? '—'),
                $prod,
                number_format((float)$r->quantity, 3),
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
        return view('backend.modules.inventory.stockTransfer.modal');
    }

    public function store(Request $r, StockService $stock)
    {
        $data = $r->validate([
            'from_warehouse_id' => 'required|different:to_warehouse_id|exists:warehouses,id',
            'to_warehouse_id'   => 'required|exists:warehouses,id',
            'rows'              => 'required|array|min:1',
            'rows.*.product_id' => 'required|exists:products,id',
            'rows.*.qty'        => 'required|numeric|min:0.001',
            'note'              => 'nullable|string|max:500',
        ]);

        DB::transaction(function() use ($data, $stock) {
            // চাইলে এখানে একটি transfer header row বানিয়ে তার id কে ref_id দিন
            $ref = ['type' => 'transfer', 'id' => null];

            foreach ($data['rows'] as $row) {
                $pid = (int)$row['product_id'];
                $qty = (float)$row['qty'];

                // OUT from source
                $stock->apply($pid, (int)$data['from_warehouse_id'], 'OUT', $qty, null, $ref, auth()->id(), $data['note'] ?? null);

                // IN to target
                $stock->apply($pid, (int)$data['to_warehouse_id'],   'IN',  $qty, null, $ref, auth()->id(), $data['note'] ?? null);
            }
        });

        return response()->json(['success'=>true,'msg'=>'Transfer completed']);
    }
//     public function store(Request $r, StockService $stock)
// {
//     $data = $r->validate([
//         'from_warehouse_id' => 'required|different:to_warehouse_id|exists:warehouses,id',
//         'to_warehouse_id'   => 'required|exists:warehouses,id',
//         'rows' => 'required|array|min:1',
//         'rows.*.product_id' => 'required|exists:products,id',
//         'rows.*.qty'        => 'required|numeric|min:0.001',
//     ]);

//     DB::transaction(function() use ($data, $stock) {
//         // Transfer ID (internal doc id) create করলে ref_id এখানে দিন
//         $ref = ['type' => 'transfer', 'id' => null];

//         foreach ($data['rows'] as $row) {
//             // OUT: source
//             $stock->apply((int)$row['product_id'], (int)$data['from_warehouse_id'], 'OUT', (float)$row['qty'], null, $ref, auth()->id(), 'Transfer OUT');

//             // IN: target
//             $stock->apply((int)$row['product_id'], (int)$data['to_warehouse_id'],   'IN',  (float)$row['qty'], null, $ref, auth()->id(), 'Transfer IN');
//         }
//     });

//     return response()->json(['success'=>true,'msg'=>'Transfer completed']);
// }

}