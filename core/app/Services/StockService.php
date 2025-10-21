<?php

namespace App\Services;

use App\Models\backend\Product;
use App\Models\backend\StockCurrent;
use App\Models\backend\StockLedger;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\backend\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;


class StockService
{
    /**
     * direction: 'IN' | 'OUT'
     * ref: ['type' => 'opening|receive|transfer|adjustment|sale|return', 'id' => int|null]
     */
    // public function apply(int $productId, ?int $warehouseId, string $direction, float $qty,
    //                       ?float $unitCost = null, array $ref = [], ?int $userId = null, ?string $note = null,
    //                       ?\DateTimeInterface $when = null): void
          
    // {
    //     if ($qty <= 0) {
    //         throw ValidationException::withMessages(['quantity' => 'Qty must be > 0']);
    //     }

    //     $product = Product::query()->whereKey($productId)->firstOrFail();
    //     if (!$product->is_sellable) {
    //         throw ValidationException::withMessages(['product_id' => 'Use a sellable product (variant/single).']);
    //     }

    //     DB::transaction(function() use ($productId, $warehouseId, $direction, $qty, $unitCost, $ref, $userId, $note, $when) {
    //         // 1) Ledger line
    //         StockLedger::query()->create([
    //             'txn_date'     => $when?->format('Y-m-d H:i:s') ?? now(),
    //             'product_id'   => $productId,
    //             'warehouse_id' => $warehouseId,
    //             'ref_type'     => $ref['type'] ?? null,
    //             'ref_id'       => $ref['id']   ?? null,
    //             'direction'    => $direction,
    //             'quantity'     => $qty,
    //             'unit_cost'    => $unitCost,
    //             'note'         => $note,
    //             'created_by'   => $userId,
    //         ]);

    //         // 2) Currents upsert
    //         $effect = $direction === 'IN' ? +$qty : -$qty;

    //         $exists = StockCurrent::query()
    //             ->where('product_id', $productId)
    //             ->where('warehouse_id', $warehouseId)
    //             ->lockForUpdate()
    //             ->first();

    //         if ($exists) {
    //             $newQty = (float)$exists->quantity + $effect;
    //             // negative policy: যদি allow না করেন, এখানে ব্লক করুন
    //             $exists->update(['quantity' => $newQty]);
    //         } else {
    //             // নতুন রো
    //             StockCurrent::query()->create([
    //                 'product_id'   => $productId,
    //                 'warehouse_id' => $warehouseId,
    //                 'quantity'     => max(0, $effect), // OUT হলে 0 হবে; policy অনুযায়ী পরিবর্তন করুন
    //             ]);
    //         }
    //     });
    // }
    
    

    /**
     * direction: 'IN' | 'OUT'
     * ref: ['type' => 'opening|receive|transfer|adjustment|sale|return', 'id' => int|null]
     */
    public function apply(
        int $productId,
        int $warehouseId,                 // <- আপনার স্কিমা অনুযায়ী required
        string $direction,                // 'IN' | 'OUT'
        float $qty,
        ?float $unitCost = null,
        array $ref = [],
        ?int $userId = null,
        ?string $note = null,
        ?\DateTimeInterface $when = null
    ): void {
        if ($qty <= 0) {
            throw ValidationException::withMessages(['quantity' => 'Qty must be > 0']);
        }

        // 0) Product must be sellable (single বা child—আপনার parent/child মডেলে child/ single-ই sellable হবে)
        $product = Product::query()->whereKey($productId)->firstOrFail();
        if (!$product->is_sellable) {
            throw ValidationException::withMessages(['product_id' => 'Use a sellable product (variant/single).']);
        }

        // 1) Warehouse → Branch resolve + guard
        $warehouse = Warehouse::query()->select('id','branch_id')->findOrFail($warehouseId);
        $branchId  = (int)$warehouse->branch_id;

        if (function_exists('current_branch_id')) {
            $current = current_branch_id();
            if ($current !== null && (int)$current !== $branchId) {
                throw ValidationException::withMessages([
                    'warehouse_id' => 'Selected warehouse does not belong to current branch.'
                ]);
            }
        }

        $userId  = $userId ?? (Auth::id() ?: null);
        $txnDate = $when ? Carbon::instance($when) : now();

        DB::transaction(function () use (
            $productId, $warehouseId, $branchId, $direction, $qty, $unitCost, $ref, $userId, $note, $txnDate,
        ) {
            // 2) Ledger insert (schema aligned)
            StockLedger::query()->create([
                'txn_date'     => $txnDate,                // datetime
                'product_id'   => $productId,
                'warehouse_id' => $warehouseId,
                'branch_id'    => $branchId,
                'ref_type'     => $ref['type'] ?? null,
                'ref_id'       => $ref['id']   ?? null,
                'direction'    => $direction,              // 'IN' | 'OUT'
                'quantity'     => $qty,
                'unit_cost'    => $unitCost,
                'note'         => $note,
                'created_by'   => $userId,
            ]); 

            // 3) Current upsert (unique key: branch_id+product_id+warehouse_id)
            $delta = $direction === 'IN' ? +$qty : -$qty;

            $cur = StockCurrent::query()
                ->where('branch_id', $branchId)
                ->where('product_id', $productId)
                ->where('warehouse_id', $warehouseId)
                ->lockForUpdate()
                ->first();

            if ($cur) {
                $newQty = (float)$cur->quantity + $delta;

                // ❗ negative policy: ব্লক করতে চাইলে এই অংশ ব্যবহার করুন
                // if ($newQty < 0) {
                //     throw ValidationException::withMessages(['quantity' => 'Insufficient stock.']);
                // }

                $cur->update(['quantity' => $newQty]);
            } else {
                // OUT দিয়ে শুরু হলে negative avoid করতে চাইলে max(0, ...) রাখুন
                $startQty = max(0, $delta);

                // ❗ কঠোর পলিসি চাইলে:
                // if ($delta < 0) throw ValidationException::withMessages(['quantity'=>'No stock to deduct.']);

                StockCurrent::query()->create([
                    'product_id'   => $productId,
                    'warehouse_id' => $warehouseId,
                    'branch_id'    => $branchId,
                    'quantity'     => $startQty,
                ]);
            }
        });
    }


}