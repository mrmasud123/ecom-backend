<?php

namespace Modules\Marketing\Services\Interfaces;

use Modules\Marketing\Models\Discount;

interface DiscountServiceInterface
{
    public function store(array $data): Discount;

    public function update(int $id, array $data): Discount;

    public function delete(int $id): bool;
}
