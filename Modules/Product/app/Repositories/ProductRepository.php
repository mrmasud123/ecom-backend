<?php

namespace Modules\Product\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Modules\Product\Models\Product;
use Modules\Product\Repositories\Interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(protected Product $model) {}

    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

//    public function update(Product $product, array $data): Product
//    {
//        $product->update($data);
//
//        return $product->fresh();
//    }

    public function update(Product $product, array $data): Product
    {
        $product->update($this->onlyProductColumns($data));

        return $product->fresh();
    }
    public function find(int $id): ?Product
    {
        return $this->model->find($id);
    }
    protected function onlyProductColumns(array $data): array
    {
        return collect($data)->only([
            'name', 'sku', 'slug', 'short_description', 'description',
            'price', 'compare_price', 'cost_price',
            'track_quantity', 'quantity', 'allow_backorder',
            'weight', 'length', 'width', 'height',
            'brand_id', 'has_variants',
            'is_active', 'is_featured', 'published_at',
            'meta_title', 'meta_description',
        ])->all();
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    public function syncCategories(Product $product, array $categoryIds): void
    {
        $product->categories()->sync($categoryIds);
    }

    public function queryForDataTable(): Builder
    {
        return $this->model->newQuery()
            ->select([
                'products.id',
                'products.name',
                'products.brand_id',
                'products.has_variants',
                'products.is_active',
            ])
            ->with('brand:id,name')
            ->with('categories:id,name')
            ->with('variants');
    }
}
