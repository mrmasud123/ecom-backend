<?php

namespace Modules\Product\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductVariant;
use Modules\Product\Repositories\Interfaces\ProductRepositoryInterface;

class ProductService
{
    public function __construct(protected ProductRepositoryInterface $products) {}


    public function create(array $data, array $images = []): Product
    {
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? $data['name']);

        return DB::transaction(function () use ($data, $images) {
            $product = $this->products->create($data);

            if (! empty($data['category_ids'])) {
                $this->products->syncCategories($product, $data['category_ids']);
            }

            $this->attachImages($product, $images);

            return $product;
        });
    }

//    public function update(Product $product, array $data, array $images = []): Product
//    {
//        if (empty($data['slug']) && $product->name !== $data['name']) {
//            $data['slug'] = $this->uniqueSlug($data['name'], $product->id);
//        }
//
//        return DB::transaction(function () use ($product, $data, $images) {
//            $product = $this->products->update($product, $data);
//
//            if (array_key_exists('category_ids', $data)) {
//                $this->products->syncCategories($product, $data['category_ids'] ?? []);
//            }
//
//            $this->attachImages($product, $images);
//
//            return $product;
//        });
//    }

    public function update(int $id, array $data): Product
    {
        $product = $this->products->find($id);

        if (! $product) {
            abort(404, 'Product not found.');
        }

        return DB::transaction(function () use ($product, $data) {
            $product = $this->products->update($product, $data);

            $this->products->syncCategories($product, $data['category_ids'] ?? []);

            if (! empty($data['removed_media_ids'])) {
                $this->removeImages($product, $data['removed_media_ids']);
            }

            if (! empty($data['images'])) {
                $this->attachImages($product, $data['images']);
            }

            if (! empty($data['has_variants'])) {
                $this->syncVariants($product, $data['variants'] ?? []);
            } else {
                $this->deleteAllVariants($product);
            }

            Cache::tags(['products'])->flush();

            return $product->fresh(['variants.attributeValues', 'categories', 'media']);
        });
    }

    public function delete(Product $product): bool
    {
        return $this->products->delete($product);
    }

    /**
     * Generate a URL-safe slug and guarantee uniqueness by appending -2, -3, etc.
     * $ignoreId excludes the current product when updating.
     */
    protected function uniqueSlug(string $source, ?int $ignoreId = null): string
    {
        $base = Str::slug($source);
        $slug = $base;
        $counter = 2;

        while (
        Product::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    /**
     * @param UploadedFile[] $images
     */
    protected function attachImages(Product $product, array $images): void
    {
        foreach ($images as $image) {
            $product->addMedia($image)->toMediaCollection('images');
        }
    }

    protected function syncVariants(Product $product, array $variantsData): void
    {
        $submittedIds = collect($variantsData)->pluck('id')->filter()->all();

        // delete DB variants that were removed client-side (not present in submission)
        $product->variants()
            ->whereNotIn('id', $submittedIds)
            ->get()
            ->each(fn (ProductVariant $variant) => $variant->delete());

        foreach ($variantsData as $row) {
            $attributeValueIds = collect(explode(',', $row['attribute_value_ids']))
                ->map(fn ($id) => (int) trim($id))
                ->filter()
                ->all();

            $payload = [
                'product_id' => $product->id,
                'sku' => $row['sku'],
                'price' => $row['price'] ?: null,
                'compare_price' => $row['compare_price'] ?: null,
                'quantity' => $row['quantity'] ?? 0,
                'is_active' => (bool) ($row['is_active'] ?? false),
            ];

            if (! empty($row['id'])) {
                $variant = $product->variants()->findOrFail($row['id']);
                $variant->update($payload);
            } else {
                $variant = ProductVariant::create($payload);
            }

            $variant->attributeValues()->sync($attributeValueIds);
        }
    }

    protected function deleteAllVariants(Product $product): void
    {
        $product->variants()->get()->each(fn (ProductVariant $variant) => $variant->delete());
    }

    protected function removeImages(Product $product, array $mediaIds): void
    {
        $product->media()->whereIn('id', $mediaIds)->get()->each->delete();
    }
}
