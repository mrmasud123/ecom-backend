@extends('layouts.app')

@section('vendor-scripts')
    @vite(['Modules/Product/resources/assets/js/create-attribute.js']);
@endsection

@section('content')

    <x-common.page-breadcrumb :pageTitle="[['name' => 'Attributes', 'link' => route('attributes.index')], ['name' => 'Add Attribute', 'link' => '#']]" />

    <form id="createAttributeForm" action="{{ route('attributes.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3 items-start">

            {{-- ============ MAIN COLUMN ============ --}}
            <div class="space-y-6 xl:col-span-2 order-1">

                {{-- Basic info --}}
                <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">
                    <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white">Basic information</h3>
                    <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">The name customers and staff will see for this attribute.</p>

                    <div class="space-y-5">
                        <div>
                            <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Attribute name</label>
                            <input type="text" id="name" name="name" required placeholder="e.g. Color, Size, Material"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white dark:placeholder:text-gray-500">
                        </div>

                        <div>
                            <label for="slug" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Slug <span class="text-gray-400">(auto-filled, editable)</span>
                            </label>
                            <input type="text" id="slug" name="slug" placeholder="color"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>

                {{-- Attribute values --}}
                <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">
                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white">Values</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Add the options customers can choose from.</p>
                        </div>
                        <button type="button" id="addValueRow"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Add value
                        </button>
                    </div>

                    <div id="valueRows" class="space-y-3">
                        {{-- Template row (cloned by JS). data-color-field toggled based on #type select. --}}
                        <div class="attribute-value-row grid grid-cols-1 gap-3 sm:grid-cols-[1fr_1fr_auto_auto] items-center">
                            <input type="text" name="values[0][value]" placeholder="e.g. Red"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                            <input type="text" name="values[0][slug]" placeholder="red"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                            <input type="color" name="values[0][color_code]" value="#000000"
                                   class="color-field hidden h-11 w-14 cursor-pointer rounded-lg border border-gray-300 bg-transparent p-1 dark:border-gray-700">
                            <button type="button" class="remove-value-row inline-flex h-11 w-11 items-center justify-center rounded-lg text-gray-400 transition hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <p class="mt-3 text-xs text-gray-400">You can also add values later from the attribute's edit page.</p>
                </div>
            </div>

            {{-- ============ SIDEBAR ============ --}}
            <div class="space-y-6 self-start xl:sticky xl:top-6 order-2">

                {{-- Type / behavior --}}
                <div class="order-2 xl:order-1 bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-800">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Type</h3>

                    <div class="space-y-5">
                        <div>
                            <label for="type" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Display as</label>
                            <select id="type" name="type"
                                    class="h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                                <option value="select">Dropdown select</option>
                                <option value="radio">Radio buttons</option>
                                <option value="color">Color swatch</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-400">Choose "Color swatch" to enable color pickers on values.</p>
                        </div>
                    </div>
                </div>

                {{-- Save / cancel --}}
                <div class="order-1 xl:order-2 bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-800">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Save</h3>
                    <p class="mb-5 text-xs text-gray-400">This attribute will be available to assign on product variants once saved.</p>

                    <div class="flex gap-3 border-t border-gray-100 pt-5 dark:border-gray-700">
                        <button type="submit"
                                class="flex-1 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                            Save attribute
                        </button>
                        <a href="{{ route('attributes.index') }}"
                           class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/10">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
