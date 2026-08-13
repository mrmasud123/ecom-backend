@extends('layouts.app')

@section('vendor-scripts')
    @vite(['Modules/Marketing/resources/assets/js/create-discount.js']);
@endsection

@section('content')

    <x-common.page-breadcrumb :pageTitle="[['name' => 'Discounts / Campaigns', 'link' => route('discount.index')], ['name' => 'Create Discount', 'link' => '#']]" />

    <form id="createDiscountForm" action="{{ route('discount.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3 items-start">

            {{-- ============ MAIN COLUMN ============ --}}
            <div class="space-y-6 xl:col-span-2 order-1">

                {{-- Basic info --}}
                <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">
                    <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white">Campaign details</h3>
                    <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">What this campaign is called, for your own reference.</p>

                    <div class="space-y-5">
                        <div>
                            <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Campaign name</label>
                            <input type="text" id="name" name="name" required placeholder="e.g. Weekend Flash Sale"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white dark:placeholder:text-gray-500">
                        </div>

                        <div>
                            <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Description <span class="text-gray-400">(optional)</span>
                            </label>
                            <textarea id="description" name="description" rows="3" placeholder="Internal note about why this campaign exists"
                                      class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Discount value — visual type selector + amount --}}
                <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">
                    <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white">Discount value</h3>
                    <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">How much customers save.</p>

                    <div class="mb-5 grid grid-cols-2 gap-3">
                        <label class="discount-type-option relative cursor-pointer">
                            <input type="radio" name="type" value="percentage" checked class="peer sr-only">
                            <div class="flex items-center gap-3 rounded-xl border-2 border-gray-200 p-4 transition peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:border-gray-700 dark:peer-checked:border-indigo-400 dark:peer-checked:bg-indigo-500/10">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 3.75L4.5 4.5m0 0L4.5 9m0-4.5L9 4.5m10.5-.75L19.5 9m0-4.5L15 4.5m4.5 15L15 19.5m4.5 0L19.5 15m0 4.5L15 19.5M4.5 15l4.5 4.5m-4.5-4.5L9 19.5m-4.5-4.5v4.5" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">Percentage</p>
                                    <p class="text-xs text-gray-400">e.g. 20% off</p>
                                </div>
                                <svg class="check-icon absolute right-3 top-3 h-5 w-5 text-indigo-500 opacity-0 peer-checked:opacity-100 transition" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </label>

                        <label class="discount-type-option relative cursor-pointer">
                            <input type="radio" name="type" value="fixed" class="peer sr-only">
                            <div class="flex items-center gap-3 rounded-xl border-2 border-gray-200 p-4 transition peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:border-gray-700 dark:peer-checked:border-indigo-400 dark:peer-checked:bg-indigo-500/10">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-300">
                                    <span class="text-base font-bold">৳</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">Fixed amount</p>
                                    <p class="text-xs text-gray-400">e.g. ৳200 off</p>
                                </div>
                                <svg class="check-icon absolute right-3 top-3 h-5 w-5 text-indigo-500 opacity-0 peer-checked:opacity-100 transition" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </label>
                    </div>

                    <div>
                        <label for="value" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                        <div class="relative max-w-xs">
                            <span id="valuePrefix" class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 hidden"></span>
                            <input type="number" step="0.01" min="0" id="value" name="value" required placeholder="20"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-4 pr-10 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                            <span id="valueSuffix" class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-gray-400">%</span>
                        </div>
                        <p class="mt-2 text-xs text-gray-400" id="valuePreview">A ৳1000 item will sell for <span class="font-semibold text-emerald-600 dark:text-emerald-400">৳800.00</span></p>
                    </div>
                </div>

                {{-- Apply to — targeting --}}
                <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">
                    <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white">Apply to</h3>
                    <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">Choose what this campaign discounts.</p>

                    <div class="mb-4">
                        <select id="targetType" name="target_type"
                                class="h-11 w-full max-w-xs appearance-none rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                            <option value="products">Specific products</option>
                            <option value="variants">Specific variants</option>
                            <option value="categories">Specific categories</option>
                            <option value="storewide">Storewide (everything)</option>
                        </select>
                    </div>

                    <div id="productTargetPicker">
                        <select data-placeholder="Search and select products..." id="productSelect" name="product_ids[]" multiple
                                class="select2-target w-full rounded-lg border border-gray-300 text-sm dark:border-gray-700 dark:text-white">
                            @foreach($products ?? [] as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="categoryTargetPicker" class="hidden">
                        <select data-placeholder="Search and select categories..." id="categorySelect" name="category_ids[]" multiple
                                class="select2-target w-full rounded-lg border border-gray-300 text-sm dark:border-gray-700 dark:text-white">
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="variantTargetPicker" class="hidden">
                        <select data-placeholder="Search and select variants..." id="variantSelect" name="variant_ids[]" multiple class="select2-target w-full ...">
                            @foreach($products ?? [] as $product)
                                @foreach($product->variants as $variant)
                                    <option value="{{ $variant->id }}">{{ $product->name }} — {{ $variant->sku }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div id="storewideNotice" class="hidden items-center gap-2 rounded-lg bg-amber-50 px-4 py-3 text-xs text-amber-700 dark:bg-amber-900/20 dark:text-amber-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        This will discount every product in your store.
                    </div>
                </div>
            </div>

            {{-- ============ SIDEBAR ============ --}}
            <div class="space-y-6 self-start xl:sticky xl:top-6 order-2">

                {{-- Live preview card --}}
                <div class="order-1 xl:order-1 rounded-2xl bg-gradient-to-br from-indigo-600 to-indigo-500 p-6 text-white shadow-lg">
                    <p class="mb-1 text-xs font-medium uppercase tracking-wide text-indigo-100">Preview</p>
                    <p id="previewName" class="mb-3 text-lg font-semibold">Weekend Flash Sale</p>
                    <div class="flex items-baseline gap-2">
                        <span id="previewBadge" class="inline-flex items-center rounded-full bg-white/20 px-3 py-1 text-sm font-bold">20% OFF</span>
                    </div>
                    <p id="previewStatus" class="mt-3 text-xs text-indigo-100">Set a schedule to see when this goes live</p>
                </div>

                {{-- Schedule --}}
                <div class="order-2 xl:order-2 bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-800">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Schedule</h3>

                    <div class="space-y-5">
                        <div>
                            <label for="starts_at" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Starts</label>
                            <input type="datetime-local" id="starts_at" name="starts_at"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                            <p class="mt-1 text-xs text-gray-400">Leave blank to start immediately.</p>
                        </div>

                        <div>
                            <label for="ends_at" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Ends</label>
                            <input type="datetime-local" id="ends_at" name="ends_at"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                            <p class="mt-1 text-xs text-gray-400">Leave blank to run indefinitely.</p>
                        </div>

                        <div class="flex items-center justify-between rounded-lg border border-gray-200 px-4 py-3 dark:border-gray-700">
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-white">Active</p>
                                <p class="text-xs text-gray-400">Turn off to pause without deleting</p>
                            </div>
                            <label class="relative inline-flex cursor-pointer items-center">
                                <input type="checkbox" name="is_active" value="1" checked class="peer sr-only">
                                <div class="h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-green-600 transition-colors"></div>
                                <div class="absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition-transform peer-checked:translate-x-5"></div>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3 border-t border-gray-100 pt-5 dark:border-gray-700">
                        <button type="submit"
                                class="flex-1 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">
                            Launch campaign
                        </button>
                        <a href="{{ route('discount.index') }}"
                           class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/10">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @include('marketing::discount-campaign.partials.rocket')
    </form>

@endsection
