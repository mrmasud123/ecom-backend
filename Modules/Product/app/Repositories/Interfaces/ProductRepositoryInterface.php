<?php

namespace Modules\Product\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Modules\Product\Models\Product;

interface ProductRepositoryInterface
{
    public function create(array $data): Product;

    public function update(Product $product, array $data): Product;

    public function delete(Product $product): bool;

    public function syncCategories(Product $product, array $categoryIds): void;

    public function queryForDataTable(): Builder;
}
