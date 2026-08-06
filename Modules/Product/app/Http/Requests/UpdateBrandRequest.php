<?php
// Modules/Product/app/Http/Requests/UpdateBrandRequest.php
namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $brandId = $this->route('brand');

        return [
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255', Rule::unique('brands', 'slug')->ignore($brandId)],
            'description' => ['nullable', 'string'],
            'logo'        => ['nullable', 'image', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
            'is_active'   => ['boolean'],
        ];
    }
}
