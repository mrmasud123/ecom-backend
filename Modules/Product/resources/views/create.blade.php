@extends('layouts.app')

@section('vendor-scripts')
    @vite(['Modules/Product/resources/assets/js/create-product.js']);
@endsection

@section('content')

    <x-common.page-breadcrumb :pageTitle="[['name' => 'Products', 'link' => route('product.index')], ['name' => 'Add Product', 'link' => '#']]" />

    <form id="createProductForm" action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3 items-start">

            {{-- ============ MAIN COLUMN ============ --}}
            <div class="space-y-6 xl:col-span-2 order-1">

                {{-- Basic info --}}
                <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">
                    <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white">Basic information</h3>
                    <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">The essentials — what it's called and how it's identified.</p>

                    <div class="space-y-5">
                        <div>
                            <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Product name</label>
                            <input type="text" id="name" name="name" required placeholder="e.g. Everyday Canvas Tote"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white dark:placeholder:text-gray-500">
                        </div>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div>
                                <label for="sku" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">SKU</label>
                                <input type="text" id="sku" name="sku" required placeholder="TOTE-CAN-001"
                                       class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label for="slug" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Slug <span class="text-gray-400">(auto-filled, editable)</span>
                                </label>
                                <input type="text" id="slug" name="slug" placeholder="everyday-canvas-tote"
                                       class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                            </div>
                        </div>

                        <div>
                            <label for="short_description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Short description</label>
                            <input type="text" id="short_description" name="short_description" maxlength="255" placeholder="One line for listings and search results"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Full description</label>
                            <textarea id="description" name="description" rows="6" placeholder="Materials, fit, care instructions — whatever helps someone decide."
                                      class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Images --}}
                <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">
                    <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white">Images</h3>
                    <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">The first image becomes the product's cover.</p>

                    <label for="images"
                           class="flex flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-gray-300 bg-gray-50 px-6 py-10 text-center cursor-pointer transition hover:border-blue-400 hover:bg-blue-50/40 dark:border-gray-700 dark:bg-white/5 dark:hover:border-blue-400/60">
                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l-3 3m3-3l3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Click to upload, or drag images here</span>
                        <span class="text-xs text-gray-400">PNG, JPG up to 4MB each</span>
                        <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
                    </label>

                    <div id="imagePreviewGrid" class="mt-4 grid grid-cols-3 gap-3 sm:grid-cols-4 md:grid-cols-6"></div>
                </div>

                {{-- Pricing --}}
                <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">
                    <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white">Pricing</h3>
                    <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">What the customer pays, what it costs you, and what "on sale" looks like.</p>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        <div>
                            <label for="price" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400">৳</span>
                                <input type="number" step="0.01" min="0" id="price" name="price" required placeholder="0.00"
                                       class="h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-8 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                            </div>
                        </div>
                        <div>
                            <label for="compare_price" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Compare-at price</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400">৳</span>
                                <input type="number" step="0.01" min="0" id="compare_price" name="compare_price" placeholder="0.00"
                                       class="h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-8 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                            </div>
                            <p class="mt-1 text-xs text-gray-400">Shown crossed out. Leave blank if not discounted.</p>
                        </div>
                        <div>
                            <label for="cost_price" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Cost per item</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400">৳</span>
                                <input type="number" step="0.01" min="0" id="cost_price" name="cost_price" placeholder="0.00"
                                       class="h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-8 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                            </div>
                            <p class="mt-1 text-xs text-gray-400">For margin reports only — never shown publicly.</p>
                        </div>
                    </div>
                </div>

                {{-- Inventory --}}
                <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">
                    <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white">Inventory</h3>
                    <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">Track stock and decide what happens when it runs out.</p>

                    <div class="mb-5 flex items-center justify-between rounded-lg border border-gray-200 px-4 py-3 dark:border-gray-700">
                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-white">Track quantity</p>
                            <p class="text-xs text-gray-400">Deduct stock automatically as orders come in</p>
                        </div>
                        <label class="relative inline-flex cursor-pointer items-center">
                            <input type="checkbox" name="track_quantity" id="track_quantity" value="1" checked class="peer sr-only">
                            <div class="h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-green-600 transition-colors "></div>
                            <div class="absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition-transform peer-checked:translate-x-5"></div>
                        </label>
                    </div>

                    <div id="quantityFields" class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label for="quantity" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity available</label>
                            <input type="number" min="0" id="quantity" name="quantity" placeholder="0"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                        </div>
                        <div class="flex items-center gap-3 pt-7">
                            <input type="checkbox" name="allow_backorder" id="allow_backorder" value="1" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-700">
                            <label for="allow_backorder" class="text-sm text-gray-700 dark:text-gray-300">Allow customers to order when out of stock</label>
                        </div>
                    </div>
                </div>

                {{-- Shipping --}}
                <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">
                    <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white">Shipping</h3>
                    <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">Used to calculate courier rates. Skip if this product isn't physical.</p>

                    <div class="grid grid-cols-2 gap-5 sm:grid-cols-4">
                        <div>
                            <label for="weight" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Weight (kg)</label>
                            <input type="number" step="0.01" min="0" id="weight" name="weight" placeholder="0.00"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="length" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Length (cm)</label>
                            <input type="text" id="length" name="length" placeholder="0"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="width" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Width (cm)</label>
                            <input type="text" id="width" name="width" placeholder="0"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="height" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Height (cm)</label>
                            <input type="text" id="height" name="height" placeholder="0"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>

                {{-- ============ VARIANTS (only relevant when has_variants is true) ============ --}}
                <div id="variantsCard" class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">
                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white">Variants</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Pick the attributes that apply, then generate combinations.</p>
                        </div>
                    </div>

                    {{-- Attribute picker --}}
                    <div class="mb-6">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Attributes for this product</label>
                        <div class="flex flex-wrap gap-4 rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                            @forelse($attributes ?? [] as $attribute)
                                <label class="attribute-checkbox flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                    <input type="checkbox"
                                           class="attribute-select h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-700"
                                           value="{{ $attribute->id }}"
                                           data-values='@json($attribute->attributeValues->map(fn($v) => ["id" => $v->id, "value" => $v->value]))'

                                    >
                                    {{ $attribute->name }}
                                </label>
                            @empty
                                <p class="text-xs text-gray-400">No attributes exist yet. <a href="{{ route('attributes.create') }}" class="text-blue-600 hover:underline">Create one first</a>.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Value pickers per selected attribute — filled in by JS --}}
                    <div id="attributeValuesContainer" class="mb-6 space-y-4"></div>

                    <button type="button" id="generateVariantsBtn"
                            class="mb-6 inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        Generate combinations
                    </button>

                    {{-- Variant rows table --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                            <tr class="border-b border-gray-200 text-xs uppercase text-gray-400 dark:border-gray-700">
                                <th class="py-2 pr-1">Combination</th>
                                <th class="py-2 px-1">SKU</th>
                                <th class="py-2 px-1">Price</th>
                                <th class="py-2 px-1">Compare price</th>
                                <th class="py-2 px-1">Quantity</th>
                                <th class="py-2 px-1 text-center">Active</th>
                                <th class="py-2 pl-1">Remove</th>
                            </tr>
                            </thead>
                            <tbody id="variantRows" class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($product->variants ?? [] as $index => $variant)
                                <tr class="variant-row" data-variant-id="{{ $variant->id }}">
                                    <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                                    <input type="hidden" name="variants[{{ $index }}][attribute_value_ids]" value="{{ $variant->attributeValues->pluck('id')->implode(',') }}">
                                    <td class="py-2 pr-3 text-gray-700 dark:text-gray-300">
                                        {{ $variant->attributeValues->pluck('value')->implode(' / ') }}
                                    </td>
                                    <td class="py-2 px-3">
                                        <input type="text" name="variants[{{ $index }}][sku]" value="{{ $variant->sku }}"
                                               class="h-9 w-32 rounded-md border border-gray-300 bg-transparent px-2 text-sm dark:border-gray-700 dark:text-white">
                                    </td>
                                    <td class="py-2 px-3">
                                        <input type="number" step="0.01" min="0" name="variants[{{ $index }}][price]" value="{{ $variant->price }}" placeholder="{{ $product->price }}"
                                               class="h-9 w-24 rounded-md border border-gray-300 bg-transparent px-2 text-sm dark:border-gray-700 dark:text-white">
                                    </td>
                                    <td class="py-2 px-3">
                                        <input type="number" step="0.01" min="0" name="variants[{{ $index }}][compare_price]" value="{{ $variant->compare_price }}"
                                               class="h-9 w-24 rounded-md border border-gray-300 bg-transparent px-2 text-sm dark:border-gray-700 dark:text-white">
                                    </td>
                                    <td class="py-2 px-3">
                                        <input type="number" min="0" name="variants[{{ $index }}][quantity]" value="{{ $variant->quantity }}"
                                               class="h-9 w-20 rounded-md border border-gray-300 bg-transparent px-2 text-sm dark:border-gray-700 dark:text-white">
                                    </td>
                                    <td class="py-2 px-3 text-center">
                                        <input type="checkbox" name="variants[{{ $index }}][is_active]" value="1" {{ $variant->is_active ? 'checked' : '' }}
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-700">
                                    </td>
                                    <td class="py-2 pl-3 text-right">
                                        <button type="button" class="remove-variant-row text-gray-400 hover:text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr id="noVariantsRow">
                                    <td colspan="7" class="py-6 text-center text-xs text-gray-400">
                                        Select attributes above and click "Generate combinations" to create variants.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- SEO --}}
                <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">
                    <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white">Search engine listing</h3>
                    <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">How this product appears in Google results.</p>

                    <div class="space-y-5">
                        <div>
                            <label for="meta_title" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Meta title</label>
                            <input type="text" id="meta_title" name="meta_title" maxlength="255" placeholder="Defaults to product name if left blank"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="meta_description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Meta description</label>
                            <textarea id="meta_description" name="meta_description" rows="3" maxlength="255" placeholder="One or two sentences shown under the title in search results"
                                      class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ SIDEBAR ============ --}}
            {{-- Only THIS wrapper is sticky, and only from xl up. The two cards inside are plain
                 stacked blocks — no sticky/top on either — so there's exactly one sticky element,
                 never two competing for the same space. --}}
            <div class="space-y-6 self-start xl:sticky xl:top-6 order-2">

                {{-- Organization renders first on mobile so Save/Cancel stays the last thing on the page --}}
                <div class="order-2 xl:order-1 bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-800">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Organization</h3>

                    <div class="space-y-5">
                        <div>
                            <label for="brand_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Brand</label>
                            <select id="brand_id" name="brand_id"
                                    class="h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                                <option value="">Select a brand</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Categories</label>
                            <div class="max-h-48 space-y-2 overflow-y-auto rounded-lg border border-gray-300 p-3 dark:border-gray-700">
                                @forelse($categories ?? [] as $category)
                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                        <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-700">
                                        {{ $category->name }}
                                    </label>
                                @empty
                                    <p class="text-xs text-gray-400">No categories yet.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="flex items-center justify-between rounded-lg border border-gray-200 px-4 py-3 dark:border-gray-700">
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-white">Has variants</p>
                                <p class="text-xs text-gray-400">e.g. different sizes or colors</p>
                            </div>
                            <label class="relative inline-flex cursor-pointer items-center">
                                <input type="checkbox" name="has_variants" id="has_variants" value="1" class="peer sr-only">
                                <div class="h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-green-600 transition-colors "></div>
                                <div class="absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition-transform peer-checked:translate-x-5"></div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Status / publish (with Save/Cancel) --}}
                <div class="order-1 xl:order-2 bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-800">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Status</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-white">Active</p>
                                <p class="text-xs text-gray-400">Visible to customers when published</p>
                            </div>
                            <label class="relative inline-flex cursor-pointer items-center">
                                <input type="checkbox" name="is_active" value="1" checked class="peer sr-only">
                                <div class="h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-green-600 transition-colors "></div>
                                <div class="absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition-transform peer-checked:translate-x-5"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-white">Featured</p>
                                <p class="text-xs text-gray-400">Show on homepage / featured sections</p>
                            </div>
                            <label class="relative inline-flex cursor-pointer items-center">
                                <input type="checkbox" name="is_featured" value="1" class="peer sr-only">
                                <div class="h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-green-600 transition-colors "></div>
                                <div class="absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition-transform peer-checked:translate-x-5"></div>
                            </label>
                        </div>


                        <div>
                            <label for="published_at" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Publish date</label>
                            <input type="datetime-local" id="published_at" name="published_at"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                            <p class="mt-1 text-xs text-gray-400">Leave blank to publish immediately on save.</p>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3 border-t border-gray-100 pt-5 dark:border-gray-700">
                        <button type="submit"
                                class="flex-1 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                            Save product
                        </button>
                        <a href="{{ route('product.index') }}"
                           class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/10">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
