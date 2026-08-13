<?php

namespace Modules\Inventory\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Models\StockMovement;
use Modules\Inventory\Models\WarehouseStock;

class StockService
{
    private const CACHE_TAG = 'warehouse_stocks';

    /**
     * Increase stock for a variant in a warehouse and write a ledger entry.
     * Must be called inside a DB transaction by the caller if part of a bigger unit of work.
     */
    public function increaseStock(
        int $warehouseId,
        int $productVariantId,
        int $quantity,
        ?Model $reference = null,
        ?string $note = null
    ): WarehouseStock {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Quantity must be greater than zero.');
        }

        return DB::transaction(function () use ($warehouseId, $productVariantId, $quantity, $reference, $note) {
            $stock = WarehouseStock::query()
                ->where('warehouse_id', $warehouseId)
                ->where('product_variant_id', $productVariantId)
                ->lockForUpdate()
                ->first();

            $before = $stock->quantity ?? 0;

            if (! $stock) {
                $stock = WarehouseStock::create([
                    'warehouse_id' => $warehouseId,
                    'product_variant_id' => $productVariantId,
                    'quantity' => 0,
                ]);
            }

            $stock->increment('quantity', $quantity);
            $after = $before + $quantity;

            $this->writeLedger($warehouseId, $productVariantId, 'in', $quantity, $before, $after, $reference, $note);
            $this->flushCache();

            return $stock->fresh();
        });
    }

    /**
     * Decrease stock — used by Adjustments (shrinkage/loss) and future sales fulfillment.
     */
    public function decreaseStock(
        int $warehouseId,
        int $productVariantId,
        int $quantity,
        ?Model $reference = null,
        ?string $note = null,
        bool $allowNegative = false
    ): WarehouseStock {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Quantity must be greater than zero.');
        }

        return DB::transaction(function () use ($warehouseId, $productVariantId, $quantity, $reference, $note, $allowNegative) {
            $stock = WarehouseStock::query()
                ->where('warehouse_id', $warehouseId)
                ->where('product_variant_id', $productVariantId)
                ->lockForUpdate()
                ->firstOrFail();

            $before = $stock->quantity;

            if (! $allowNegative && $quantity > $before) {
                throw new \RuntimeException("Insufficient stock. Available: {$before}, requested: {$quantity}.");
            }

            $stock->decrement('quantity', $quantity);
            $after = max(0, $before - $quantity);

            $this->writeLedger($warehouseId, $productVariantId, 'out', $quantity, $before, $after, $reference, $note);
            $this->flushCache();

            return $stock->fresh();
        });
    }

    public function getStock(int $warehouseId, int $productVariantId): ?WarehouseStock
    {
        return Cache::tags(self::CACHE_TAG)->remember(
            "stock.{$warehouseId}.{$productVariantId}",
            now()->addMinutes(30),
            fn () => WarehouseStock::query()
                ->where('warehouse_id', $warehouseId)
                ->where('product_variant_id', $productVariantId)
                ->first()
        );
    }

    private function writeLedger(
        int $warehouseId,
        int $productVariantId,
        string $type,
        int $quantity,
        int $before,
        int $after,
        ?Model $reference,
        ?string $note
    ): void {
        StockMovement::create([
            'warehouse_id' => $warehouseId,
            'product_variant_id' => $productVariantId,
            'type' => $type,
            'quantity' => $quantity,
            'quantity_before' => $before,
            'quantity_after' => $after,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference?->id,
            'note' => $note,
            'created_by' => Auth::id(),
        ]);
    }

    private function flushCache(): void
    {
        Cache::tags(self::CACHE_TAG)->flush();
    }
}
