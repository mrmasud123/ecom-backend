<?php

namespace Modules\Product\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Modules\Product\Models\Brand;
use Modules\Product\Models\Category;
use Modules\Product\Repositories\Interfaces\BrandRepositoryInterface;

class BrandRepository implements BrandRepositoryInterface
{
    public function __construct(protected Brand $model) {}

    public function all(){
        return $this->model->all();
    }
    public function create(array $data): Brand
    {
        return $this->model->create($data);
    }

    public function update(Brand $brand, array $data): Brand
    {
        $brand->update($data);

        return $brand->fresh();
    }

    public function delete(Brand $brand): bool
    {
        return $brand->delete();
    }

    public function queryWithProductCount(): Builder
    {
        return Brand::query()->withCount('products')->select('brands.*');
    }
}
