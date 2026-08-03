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
        $productId = $this->route('product')?->id;

        return [
            'name'               => ['required', 'string', 'max:255'],
            'sku'                => ['required', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($productId)],
            'slug'               => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($productId)],
            'short_description'  => ['nullable', 'string', 'max:500'],
            'description'        => ['nullable', 'string'],

            'price'              => ['required', 'numeric', 'min:0'],
            'compare_price'      => ['nullable', 'numeric', 'min:0', 'gt:price'],
            'cost_price'         => ['nullable', 'numeric', 'min:0'],

            'track_quantity'     => ['sometimes', 'boolean'],
            'quantity'           => ['nullable', 'integer', 'min:0', 'required_if:track_quantity,1'],
            'allow_backorder'    => ['sometimes', 'boolean'],

            'weight'             => ['nullable', 'numeric', 'min:0'],
            'length'             => ['nullable', 'string', 'max:50'],
            'width'              => ['nullable', 'string', 'max:50'],
            'height'             => ['nullable', 'string', 'max:50'],

            'has_variants'       => ['sometimes', 'boolean'],
            'is_active'          => ['sometimes', 'boolean'],
            'is_featured'        => ['sometimes', 'boolean'],

            'meta_title'         => ['nullable', 'string', 'max:255'],
            'meta_description'   => ['nullable', 'string', 'max:255'],
            'published_at'       => ['nullable', 'date'],

            'brand_id'           => ['nullable', Rule::exists('brands', 'id')],
            'category_ids'       => ['nullable', 'array'],
            'category_ids.*'     => [Rule::exists('categories', 'id')],

            'images'             => ['nullable', 'array'],
            'images.*'           => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }
}
