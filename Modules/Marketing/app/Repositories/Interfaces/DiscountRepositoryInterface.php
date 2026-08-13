<?php

namespace Modules\Marketing\Repositories\Interfaces;

use Modules\Marketing\Models\Discount;

interface DiscountRepositoryInterface
{
    public function queryForDataTable();

    public function create(array $data): Discount;

    public function update(Discount $discount, array $data): Discount;

    public function find(int $id): ?Discount;

    public function delete(Discount $discount): bool;

    public function syncTargets(Discount $discount, string $targetType, array $ids): void;
}
