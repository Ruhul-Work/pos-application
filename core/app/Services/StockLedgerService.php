<?php

namespace App\Services;

use App\Models\backend\StockLedger;
use App\Models\backend\StockCurrent;
use Illuminate\Support\Facades\DB;
use Exception;

class StockLedgerService
{
    /**
     * Deduct stock for SALE (warehouse wise)
     */
    public static function deductForSale(array $params): void
    {
        /*
        $params = [
            'sale_id' => 10,
            'warehouse_id' => 1,
            'branch_id' => 1,
            'user_id' => 1,
            'items' => [
                [
                    'product_id' => 5,
                    'quantity' => 2,
                    'unit_price' => 500
                ]
            ]
        ];
        */

        foreach ($params['items'] as $item) {

            $qty = abs($item['quantity']);

            // -----------------------------
            // 1️⃣ Lock current stock row
            // -----------------------------
            $stock = StockCurrent::where('warehouse_id', $params['warehouse_id'])
                ->where('branch_id', $params['branch_id'])
                ->where('product_id', $item['product_id'])
                ->lockForUpdate()
                ->first();
                
            $product = DB::table('products')->where('id', $item['product_id'])->first();

            if (!$stock || $stock->quantity < $qty) {
                throw new Exception(
                        'Insufficient stock for "' . ($product->name ?? 'Product') . '"'
                    );
            }

            // -----------------------------
            // 2️⃣ Insert stock ledger (OUT)
            // -----------------------------
            StockLedger::create([
                'txn_date'     => now(),
                'product_id'   => $item['product_id'],
                'warehouse_id' => $params['warehouse_id'],
                'branch_id'    => $params['branch_id'],

                'ref_type'     => 'sale',
                'ref_id'       => $params['sale_id'],
                'direction'    => 'out',

                'quantity'     => $qty, // always positive
                'unit_cost'    => $item['unit_price'],

                'note'         => 'POS Sale',
                'created_by'   => $params['user_id'],
            ]);

            // -----------------------------
            // 3️⃣ Update stock_currents
            // -----------------------------
            $stock->update([
                'quantity' => $stock->quantity - $qty,
                'version'  => $stock->version + 1,
            ]);
        }
    }
}
