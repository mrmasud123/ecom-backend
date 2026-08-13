<?php

namespace Modules\Marketing\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Marketing\Models\Discount;
use Modules\Marketing\Repositories\Interfaces\DiscountRepositoryInterface;
use Modules\Marketing\Services\Interfaces\DiscountServiceInterface;

class DiscountService implements DiscountServiceInterface
{
    public function __construct(protected DiscountRepositoryInterface $discountRepository)
    {
    }

    public function store(array $data): Discount
    {
        return DB::transaction(function () use ($data) {
            $discount = $this->discountRepository->create($data);

            $ids = $this->resolveTargetIds($data);
            $this->discountRepository->syncTargets($discount, $data['target_type'], $ids);

            $this->flushDiscountCaches();

            return $discount->fresh('discountables');
        });
    }

    public function update(int $id, array $data): Discount
    {
        $discount = $this->discountRepository->find($id);

        if (! $discount) {
            abort(404, 'Discount not found.');
        }

        return DB::transaction(function () use ($discount, $data) {
            $discount = $this->discountRepository->update($discount, $data);

            $ids = $this->resolveTargetIds($data);
            $this->discountRepository->syncTargets($discount, $data['target_type'], $ids);

            $this->flushDiscountCaches();

            return $discount->fresh('discountables');
        });
    }

    public function delete(int $id): bool
    {
        $discount = $this->discountRepository->find($id);

        if (! $discount) {
            abort(404, 'Discount not found.');
        }

        $deleted = $this->discountRepository->delete($discount);

        if ($deleted) {
            $this->flushDiscountCaches();
        }

        return $deleted;
    }

    protected function resolveTargetIds(array $data): array
    {
        return match ($data['target_type']) {
            'products' => $data['product_ids'] ?? [],
            'variants' => $data['variant_ids'] ?? [],
            'categories' => $data['category_ids'] ?? [],
            'storewide' => [],
        };
    }

    /**
     * Discounts affect displayed prices across products, variants, and
     * categories — flush all price-relevant tags so stale prices don't linger.
     */
    protected function flushDiscountCaches(): void
    {
        Cache::tags(['products', 'discounts'])->flush();
    }
}
