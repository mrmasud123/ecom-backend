<?php

namespace Modules\Marketing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:discounts,slug'],
            'description' => ['nullable', 'string'],

            'type' => ['required', Rule::in(['percentage', 'fixed'])],
            'value' => ['required', 'numeric', 'min:0', Rule::when($this->type === 'percentage', ['max:100'])],

            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'is_active' => ['nullable', 'boolean'],

            'target_type' => ['required', Rule::in(['products', 'variants', 'categories', 'storewide'])],

            'product_ids' => ['required_if:target_type,products', 'array'],
            'product_ids.*' => ['integer', 'exists:products,id'],

            'variant_ids' => ['required_if:target_type,variants', 'array'],
            'variant_ids.*' => ['integer', 'exists:product_variants,id'],

            'category_ids' => ['required_if:target_type,categories', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_ids.required_if' => 'Select at least one product.',
            'variant_ids.required_if' => 'Select at least one variant.',
            'category_ids.required_if' => 'Select at least one category.',
            'ends_at.after' => 'End date must be after the start date.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => $this->slug ?: \Str::slug($this->name),
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
