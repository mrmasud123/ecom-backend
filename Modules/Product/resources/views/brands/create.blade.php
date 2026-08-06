{{-- Modules/Product/resources/views/brand/create.blade.php --}}
@extends('layouts.app')

@section('vendor-scripts')
    @vite(['Modules/Product/resources/assets/js/brand-create.js']);
@endsection

@section('content')

    <x-common.page-breadcrumb :pageTitle="[
        ['name' => 'Brands', 'link' => route('brands.index')],
        ['name' => 'Add Brand', 'link' => '#'],
    ]" />

    <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6 w-full">

        <div class="mb-6">
            <h2 class="text-xl font-semibold dark:text-white">Add Brand</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Create a new brand for your products
            </p>
        </div>

        <form id="brandCreateForm" enctype="multipart/form-data"
              data-store-url="{{ route('brands.store') }}"
              data-index-url="{{ route('brands.index') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Brand Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="e.g. Nike">
                    <p class="text-xs text-red-500 mt-1 error-text" data-field="name"></p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Slug
                    </label>
                    <input type="text" name="slug" id="slug"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="auto-generated from name">
                    <p class="text-xs text-gray-400 mt-1">Leave blank to auto-generate from the name</p>
                    <p class="text-xs text-red-500 mt-1 error-text" data-field="slug"></p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Short description of the brand"></textarea>
                    <p class="text-xs text-red-500 mt-1 error-text" data-field="description"></p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Logo
                    </label>

                    <label for="logo" id="logoDropzone"
                           class="relative flex items-center gap-4 rounded-xl border border-dashed border-gray-300 bg-gray-50 p-4 text-left cursor-pointer transition hover:border-blue-400 hover:bg-blue-50/40 dark:border-gray-700 dark:bg-white/5 dark:hover:border-blue-400/60">

                        <div id="logoPreviewWrapper" class="h-16 w-16 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center overflow-hidden bg-white dark:bg-gray-800 shrink-0">
                            <iconify-icon icon="lucide:image" class="text-2xl text-gray-400"></iconify-icon>
                        </div>

                        <div class="flex-1 min-w-0">
                            <p id="logoFileName" class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Click or drag an image to upload
                            </p>
                            <p id="logoFileMeta" class="text-xs text-gray-400 mt-0.5">
                                PNG, JPG or WEBP — max 2MB
                            </p>
                        </div>

                        <button type="button" id="logoRemoveBtn"
                                class="hidden shrink-0 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/10 text-red-500">
                            <iconify-icon icon="lucide:x" class="text-lg"></iconify-icon>
                        </button>

                        <input type="file" id="logo" name="logo" accept="image/png,image/jpeg,image/webp" class="hidden">
                    </label>

                    <p class="text-xs text-red-500 mt-1 error-text" data-field="logo"></p>
                </div>

                <div class="md:col-span-2 flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 dark:bg-gray-700 rounded-full peer peer-checked:bg-green-500 dark:peer-checked:bg-green-500 transition-colors"></div>
                        <div class="absolute left-0.5 top-0.5 h-5 w-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                    </label>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Active</span>
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-8 pt-5 border-t border-gray-100 dark:border-gray-800">
                <a href="{{ route('brands.index') }}"
                   class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                    Cancel
                </a>
                <button type="submit" id="submitBtn"
                        class="px-5 py-2.5 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    Save Brand
                </button>
            </div>

        </form>
    </div>

@endsection
