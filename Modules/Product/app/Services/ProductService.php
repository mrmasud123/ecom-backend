<?php

namespace Modules\Product\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Product\Models\Product;
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

    public function update(Product $product, array $data, array $images = []): Product
    {
        if (empty($data['slug']) && $product->name !== $data['name']) {
            $data['slug'] = $this->uniqueSlug($data['name'], $product->id);
        }

        return DB::transaction(function () use ($product, $data, $images) {
            $product = $this->products->update($product, $data);

            if (array_key_exists('category_ids', $data)) {
                $this->products->syncCategories($product, $data['category_ids'] ?? []);
            }

            $this->attachImages($product, $images);

            return $product;
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
}
