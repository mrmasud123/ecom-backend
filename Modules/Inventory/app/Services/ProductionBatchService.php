<?php

namespace Modules\Inventory\Services;

use Modules\Inventory\Repositories\Interfaces\ProductionBatchRepositoryInterface;
use Modules\Inventory\Models\ProductionBatch;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProductionBatchService
{
    public function __construct(
        private readonly ProductionBatchRepositoryInterface $repository,
        private readonly StockService $stockService
    ) {}

    public function getForDataTable()
    {
        return $this->repository->getForDataTable();
    }

    public function find(int $id): ?ProductionBatch
    {
        return $this->repository->find($id);
    }

    public function nextBatchNumber(): string
    {
        return $this->repository->nextBatchNumber();
    }

    public function create(array $data, array $items): ProductionBatch
    {
        $data['status'] = 'draft';

        return $this->repository->create($data, $items);
    }

    public function update(ProductionBatch $batch, array $data, array $items): ProductionBatch
    {
        if ($batch->isCompleted()) {
            throw new \RuntimeException('Completed batches cannot be edited. They are locked to preserve the stock ledger.');
        }

        return $this->repository->update($batch, $data, $items);
    }

    public function delete(ProductionBatch $batch): bool
    {
        if ($batch->isCompleted()) {
            throw new \RuntimeException('Completed batches cannot be deleted.');
        }

        return $this->repository->delete($batch);
    }

    /**
     * Post the batch to stock: writes warehouse_stocks + stock_movements for every item,
     * then locks the batch as completed.
     */
    public function complete(ProductionBatch $batch): ProductionBatch
    {
        if ($batch->isCompleted()) {
            throw new \RuntimeException('This batch is already completed.');
        }

        if ($batch->items->isEmpty()) {
            throw new \RuntimeException('Cannot complete a batch with no items.');
        }

        DB::transaction(function () use ($batch) {
            foreach ($batch->items as $item) {
                $this->stockService->increaseStock(
                    warehouseId: $batch->warehouse_id,
                    productVariantId: $item->product_variant_id,
                    quantity: $item->quantity_produced,
                    reference: $batch,
                    note: "Production batch {$batch->batch_number}"
                );
            }

            $batch->update(['status' => 'completed']);
        });

        return $batch->fresh(['items', 'warehouse']);
    }
}
