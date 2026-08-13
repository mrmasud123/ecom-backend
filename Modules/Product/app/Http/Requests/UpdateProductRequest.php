<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')->id ?? $this->route('product');

        return [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($productId)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($productId)],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'price' => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'min:0', 'gte:price'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],

            'track_quantity' => ['nullable', 'boolean'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'allow_backorder' => ['nullable', 'boolean'],

            'weight' => ['nullable', 'numeric', 'min:0'],
            'length' => ['nullable', 'numeric', 'min:0'],
            'width' => ['nullable', 'numeric', 'min:0'],
            'height' => ['nullable', 'numeric', 'min:0'],

            'brand_id' => ['nullable', 'integer', 'exists:brands,id'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],

            'has_variants' => ['nullable', 'boolean'],

            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],

            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],

            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:4096'],
            'removed_media_ids' => ['nullable', 'array'],
            'removed_media_ids.*' => ['integer'],

            // required_if: only mandatory when has_variants is truthy
            'variants' => ['required_if:has_variants,1', 'array'],
            'variants.*.id' => ['nullable', 'integer', Rule::exists('product_variants', 'id')->where('product_id', $productId)],
            'variants.*.sku' => ['required_with:variants', 'string', 'max:255'],
            'variants.*.price' => ['nullable', 'numeric', 'min:0'],
            'variants.*.compare_price' => ['nullable', 'numeric', 'min:0'],
            'variants.*.quantity' => ['required_with:variants', 'integer', 'min:0'],
            'variants.*.is_active' => ['nullable', 'boolean'],
            'variants.*.attribute_value_ids' => ['required_with:variants', 'string'],
        ];
    }

    /**
     * Reject duplicate SKUs across variant rows within the submission,
     * and duplicate SKUs against other variants in the DB (excluding this product's own rows).
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $variants = collect($this->input('variants', []));

            $skus = $variants->pluck('sku')->filter();
            if ($skus->count() !== $skus->unique()->count()) {
                $validator->errors()->add('variants', 'Variant SKUs must be unique.');
            }

            // reject duplicate attribute_value_id combinations (e.g. two "Red/S" rows)
            $signatures = $variants->map(function ($row) {
                return collect(explode(',', $row['attribute_value_ids'] ?? ''))
                    ->map('trim')->sort()->implode(',');
            })->filter();

            if ($signatures->count() !== $signatures->unique()->count()) {
                $validator->errors()->add('variants', 'Duplicate variant combinations detected.');
            }
        });
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => $this->slug ?: \Str::slug($this->name),
            'has_variants' => $this->boolean('has_variants'),
            'track_quantity' => $this->boolean('track_quantity'),
            'allow_backorder' => $this->boolean('allow_backorder'),
            'is_active' => $this->boolean('is_active'),
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }
}
