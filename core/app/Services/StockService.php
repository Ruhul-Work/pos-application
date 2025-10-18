<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockLedger;
use App\Models\StockCurrent;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockService
{
    /**
     * direction: 'IN' | 'OUT'
     * ref: ['type' => 'opening|receive|transfer|adjustment|sale|return', 'id' => int|null]
     */
    public function apply(int $productId, ?int $warehouseId, string $direction, float $qty,
                          ?float $unitCost = null, array $ref = [], ?int $userId = null, ?string $note = null,
                          ?\DateTimeInterface $when = null): void
    {
        if ($qty <= 0) {
            throw ValidationException::withMessages(['quantity' => 'Qty must be > 0']);
        }

        $product = Product::query()->whereKey($productId)->firstOrFail();
        if (!$product->is_sellable) {
            throw ValidationException::withMessages(['product_id' => 'Use a sellable product (variant/single).']);
        }

        DB::transaction(function() use ($productId, $warehouseId, $direction, $qty, $unitCost, $ref, $userId, $note, $when) {
            // 1) Ledger line
            StockLedger::query()->create([
                'txn_date'     => $when?->format('Y-m-d H:i:s') ?? now(),
                'product_id'   => $productId,
                'warehouse_id' => $warehouseId,
                'ref_type'     => $ref['type'] ?? null,
                'ref_id'       => $ref['id']   ?? null,
                'direction'    => $direction,
                'quantity'     => $qty,
                'unit_cost'    => $unitCost,
                'note'         => $note,
                'created_by'   => $userId,
            ]);

            // 2) Currents upsert
            $effect = $direction === 'IN' ? +$qty : -$qty;

            $exists = StockCurrent::query()
                ->where('product_id', $productId)
                ->where('warehouse_id', $warehouseId)
                ->lockForUpdate()
                ->first();

            if ($exists) {
                $newQty = (float)$exists->quantity + $effect;
                // negative policy: যদি allow না করেন, এখানে ব্লক করুন
                $exists->update(['quantity' => $newQty]);
            } else {
                // নতুন রো
                StockCurrent::query()->create([
                    'product_id'   => $productId,
                    'warehouse_id' => $warehouseId,
                    'quantity'     => max(0, $effect), // OUT হলে 0 হবে; policy অনুযায়ী পরিবর্তন করুন
                ]);
            }
        });
    }
}
