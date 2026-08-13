<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:attributes,slug'],
            'type' => ['required', 'string', Rule::in(['select', 'radio', 'color'])],

            'values' => ['required', 'array', 'min:1'],
            'values.*.value' => ['required', 'string', 'max:255'],
            'values.*.slug' => ['nullable', 'string', 'max:255'],
            'values.*.color_code' => ['nullable', 'required_if:type,color', 'string', 'max:7'],
        ];
    }

    public function messages(): array
    {
        return [
            'values.required' => 'Add at least one value for this attribute.',
            'values.*.value.required' => 'Each value row needs a name.',
            'values.*.color_code.required_if' => 'Color code is required when type is "Color swatch".',
        ];
    }

    /**
     * Reject duplicate slugs within the submitted values array itself,
     * since DB-level unique(attribute_id, slug) can't catch collisions
     * between rows in the same request.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $slugs = collect($this->input('values', []))
                ->map(fn ($row) => \Str::slug($row['slug'] ?? $row['value'] ?? ''))
                ->filter();

            if ($slugs->count() !== $slugs->unique()->count()) {
                $validator->errors()->add('values', 'Value slugs must be unique within this attribute.');
            }
        });
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => $this->slug ?: \Str::slug($this->name),
        ]);
    }
}
