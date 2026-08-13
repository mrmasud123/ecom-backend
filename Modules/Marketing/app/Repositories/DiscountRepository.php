<?php

namespace Modules\Marketing\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Modules\Marketing\Models\Discount;
use Modules\Marketing\Repositories\Interfaces\DiscountRepositoryInterface;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductVariant;

class DiscountRepository implements DiscountRepositoryInterface
{
    public function __construct(protected Discount $model)
    {
    }

    public function queryForDataTable(): Builder
    {
        return $this->model->select('discounts.*');
    }

    public function create(array $data): Discount
    {
        return $this->model->create($this->onlyDiscountColumns($data));
    }

    public function update(Discount $discount, array $data): Discount
    {
        $discount->update($this->onlyDiscountColumns($data));

        return $discount->fresh();
    }

    public function find(int $id): ?Discount
    {
        return $this->model->find($id);
    }

    public function delete(Discount $discount): bool
    {
        return (bool) $discount->delete();
    }

    /**
     * Rebuild the discountables pivot rows for this discount based on
     * the selected target type. "storewide" clears all specific targets.
     */
    public function syncTargets(Discount $discount, string $targetType, array $ids): void
    {
        // wipe existing targets first — this is a full rebuild, not a merge
        $discount->discountables()->delete();

        $modelClass = match ($targetType) {
            'products' => Product::class,
            'variants' => ProductVariant::class,
            'categories' => Category::class,
            'storewide' => null,
        };

        if ($modelClass === null || empty($ids)) {
            return; // storewide has no discountable rows at all
        }

        $rows = collect($ids)->map(fn ($id) => [
            'discount_id' => $discount->id,
            'discountable_type' => $modelClass,
            'discountable_id' => $id,
            'created_at' => now(),
            'updated_at' => now(),
        ])->all();

        $discount->discountables()->insert($rows);
    }

    protected function onlyDiscountColumns(array $data): array
    {
        return collect($data)->only([
            'name', 'slug', 'description', 'type', 'value',
            'starts_at', 'ends_at', 'is_active',
        ])->all();
    }
}
