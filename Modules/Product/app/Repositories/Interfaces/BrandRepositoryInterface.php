<?php

namespace Modules\Product\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Modules\Product\Models\Brand;
use Modules\Product\Models\Category;

interface BrandRepositoryInterface
{
    public function create(array $data): Brand;

    public function update(Brand $brand, array $data): Brand;

    public function delete(Brand $brand): bool;

    public function queryWithProductCount(): Builder;

}
