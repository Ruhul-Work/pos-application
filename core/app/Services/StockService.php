<?php
namespace App\Services;

use App\Models\backend\Product;
use App\Models\backend\StockCurrent;
use App\Models\backend\StockLedger;
use App\Models\backend\Warehouse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockService
{

    /**
     * direction: 'IN' | 'OUT'
     * ref: ['type' => 'opening|receive|transfer|adjustment|sale|return', 'id' => int|null]
     */
    public function apply(
        int $productId,
        int $warehouseId,  // <- আপনার স্কিমা অনুযায়ী required
        string $direction, // 'IN' | 'OUT'
        float $qty,
        ?float $unitCost = null,
        array $ref = [],
        ?int $userId = null,
        ?string $note = null,
        ? \DateTimeInterface $when = null
    ) : void {
        if ($qty <= 0) {
            throw ValidationException::withMessages(['quantity' => 'Qty must be > 0']);
        }

        // 0) Product must be sellable (single বা child—আপনার parent/child মডেলে child/ single-ই sellable হবে)
        $product = Product::query()->whereKey($productId)->firstOrFail();
        if (! $product->is_sellable) {
            throw ValidationException::withMessages(['product_id' => 'Use a sellable product (variant/single).']);
        }

        // 1) Warehouse → Branch resolve + guard
        $warehouse = Warehouse::query()->select('id', 'branch_id')->findOrFail($warehouseId);
        $branchId  = (int) $warehouse->branch_id;

        if (function_exists('current_branch_id')) {
            $current = current_branch_id();
            if ($current !== null && (int) $current !== $branchId) {
                throw ValidationException::withMessages([
                    'warehouse_id' => 'Selected warehouse does not belong to current branch.',
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
                'txn_date'     => $txnDate, // datetime
                'product_id'   => $productId,
                'warehouse_id' => $warehouseId,
                'branch_id'    => $branchId,
                'ref_type'     => $ref['type'] ?? null,
                'ref_id'       => $ref['id'] ?? null,
                'direction'    => $direction, // 'IN' | 'OUT'
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
                $newQty = (float) $cur->quantity + $delta;

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

    public function deleteAdjustment(StockLedger $ledger, ?int $userId = null): void
    {
        DB::transaction(function () use ($ledger, $userId) {
            // reverse effect
            $effect = ($ledger->direction === 'IN') ? -$ledger->quantity : +$ledger->quantity;
            $this->touchCurrent($ledger->product_id, $ledger->warehouse_id, $ledger->branch_id, $effect);

            // delete line
            $ledger->delete();
        });
    }

    public function reapplyAdjustment(StockLedger $ledger, float $newQty, ?float $newCost, ?string $newNote, ?int $userId = null): void
    {
        DB::transaction(function () use ($ledger, $newQty, $newCost, $newNote, $userId) {
            // 1) reverse old
            $oldEff = ($ledger->direction === 'IN') ? -$ledger->quantity : +$ledger->quantity;
            $this->touchCurrent($ledger->product_id, $ledger->warehouse_id, $ledger->branch_id, $oldEff);

            // 2) rewrite ledger fields
            $dir = $newQty >= 0 ? 'IN' : 'OUT';
            $ledger->update([
                'direction'  => $dir,
                'quantity'   => abs($newQty),
                'unit_cost'  => $newCost,
                'note'       => $newNote,
                'created_by' => $userId ?? $ledger->created_by,
            ]);

            // 3) apply new
            $newEff = $dir === 'IN' ? +abs($newQty) : -abs($newQty);
            $this->touchCurrent($ledger->product_id, $ledger->warehouse_id, $ledger->branch_id, $newEff);
        });
    }

    private function touchCurrent(int $productId, ?int $warehouseId, ?int $branchId, float $delta): void
    {
        $cur = StockCurrent::query()
            ->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->where('branch_id', $branchId)
            ->lockForUpdate()
            ->first();

        if ($cur) {
            $cur->update(['quantity' => (float) $cur->quantity + $delta]);
        } else {
            StockCurrent::create([
                'product_id'   => $productId,
                'warehouse_id' => $warehouseId,
                'branch_id'    => $branchId,
                'quantity'     => max(0, $delta), // policy অনুযায়ী চাইলে negativeও allow করতে পারো
            ]);
        }
    }

}
