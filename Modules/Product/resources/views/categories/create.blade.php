@extends('layouts.app')

@section('vendor-scripts')
    @vite(['Modules/Product/resources/assets/js/category-create.js']);
@endsection

@section('content')

    <x-common.page-breadcrumb :pageTitle="[['name' => 'Categories', 'link' => route('category.index')], ['name' => 'Add Category', 'link' => '#']]" />

    <form id="createCategoryForm" action="{{ route('category.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3 items-start">

            {{-- ============ MAIN COLUMN ============ --}}
            <div class="space-y-6 xl:col-span-2 order-1">

                <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">
                    <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white">Category details</h3>
                    <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">What it's called and how it's organized.</p>

                    <div class="space-y-5">
                        <div>
                            <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Category name</label>
                            <input type="text" id="name" name="name" required placeholder="e.g. Bags & Totes"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white dark:placeholder:text-gray-500">
                        </div>

                        <div>
                            <label for="slug" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Slug <span class="text-gray-400">(auto-filled, editable)</span>
                            </label>
                            <input type="text" id="slug" name="slug" placeholder="bags-totes"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea id="description" name="description" rows="5" maxlength="1000" placeholder="Shown on the category page, if you display one."
                                      class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ SIDEBAR ============ --}}
            <div class="space-y-6 self-start xl:sticky xl:top-6 order-2">

                <div class="order-2 xl:order-1 bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-800">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Organization</h3>

                    <div class="space-y-5">
                        <div>
                            <label for="parent_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Parent category</label>
                            <select id="parent_id" name="parent_id"
                                    class="h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                                <option value="">None (top-level category)</option>
                                @foreach($parents ?? [] as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="sort_order" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Sort order</label>
                            <input type="number" min="0" id="sort_order" name="sort_order" placeholder="0"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                            <p class="mt-1 text-xs text-gray-400">Lower numbers appear first.</p>
                        </div>
                    </div>
                </div>

                <div class="order-1 xl:order-2 bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-800">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Status</h3>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-white">Active</p>
                            <p class="text-xs text-gray-400">Visible when browsing categories</p>
                        </div>
                        <label class="relative inline-flex cursor-pointer items-center">
                            <input type="checkbox" name="is_active" value="1" checked class="peer sr-only">
                            <div class="h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-blue-600 transition-colors dark:bg-gray-700"></div>
                            <div class="absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition-transform peer-checked:translate-x-5"></div>
                        </label>
                    </div>

                    <div class="mt-6 flex gap-3 border-t border-gray-100 pt-5 dark:border-gray-700">
                        <button type="submit"
                                class="flex-1 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                            Save category
                        </button>
                        <a href="{{ route('category.index') }}"
                           class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/10">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
