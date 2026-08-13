<?php

namespace Modules\Inventory\Repositories\Interfaces;

use Modules\Inventory\Models\ProductionBatch;
use Yajra\DataTables\DataTables;

interface ProductionBatchRepositoryInterface
{
    public function getForDataTable();

    public function find(int $id): ?ProductionBatch;

    public function nextBatchNumber(): string;

    public function create(array $data, array $items): ProductionBatch;

    public function update(ProductionBatch $batch, array $data, array $items): ProductionBatch;

    public function delete(ProductionBatch $batch): bool;
}
