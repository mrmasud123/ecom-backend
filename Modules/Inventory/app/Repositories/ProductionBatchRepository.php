<?php

namespace Modules\Inventory\Repositories;

use Modules\Inventory\Repositories\Interfaces\ProductionBatchRepositoryInterface;
use Modules\Inventory\Models\ProductionBatch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Facades\DataTables as DataTablesFacade;

class ProductionBatchRepository implements ProductionBatchRepositoryInterface
{
    public function getForDataTable()
    {
        $query = ProductionBatch::query()
            ->with('warehouse')
            ->withSum('items', 'quantity_produced')
            ->select('production_batches.*');

        return DataTablesFacade::eloquent($query)
            ->addColumn('warehouse_name', fn (ProductionBatch $b) => $b->warehouse->name ?? '—')
            ->addColumn('total_quantity', fn (ProductionBatch $b) => $b->items_sum_quantity_produced ?? 0)
            ->addColumn('status', fn (ProductionBatch $b) => $b->status)
            ->addColumn('action', fn (ProductionBatch $b) => $b->id)
            ->rawColumns(['status', 'action']);
    }

    public function find(int $id): ?ProductionBatch
    {
        return ProductionBatch::with('items.productVariant')->find($id);
    }

    public function nextBatchNumber(): string
    {
        $year = now()->format('Y');
        $lastId = (int) (ProductionBatch::withTrashed()->max('id') ?? 0);

        return sprintf('PB-%s-%05d', $year, $lastId + 1);
    }

    public function create(array $data, array $items): ProductionBatch
    {
        return DB::transaction(function () use ($data, $items) {
            $data['batch_number'] = $this->nextBatchNumber();
            $data['created_by'] = Auth::id();
            $data['total_cost'] = $this->calculateTotalCost($items);

            $batch = ProductionBatch::create($data);
            $batch->items()->createMany($items);

            return $batch->fresh('items');
        });
    }

    public function update(ProductionBatch $batch, array $data, array $items): ProductionBatch
    {
        return DB::transaction(function () use ($batch, $data, $items) {
            $data['total_cost'] = $this->calculateTotalCost($items);

            $batch->update($data);
            $batch->items()->delete();
            $batch->items()->createMany($items);

            return $batch->fresh('items');
        });
    }

    public function delete(ProductionBatch $batch): bool
    {
        return $batch->delete();
    }

    private function calculateTotalCost(array $items): float
    {
        return array_sum(array_map(
            fn ($item) => (float) ($item['quantity_produced'] ?? 0) * (float) ($item['unit_cost'] ?? 0),
            $items
        ));
    }
}
