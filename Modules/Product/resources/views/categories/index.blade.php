@extends('layouts.app')

@section('vendor-scripts')
    @vite(['Modules/Product/resources/assets/js/category-index.js']);
@endsection

@section('content')

    <x-common.page-breadcrumb :pageTitle="[['name' => 'Categories', 'link' => '#']]" />

    <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">

        <div class="flex justify-between mb-5">
            <h2 class="text-xl font-semibold dark:text-white">Manage Categories</h2>
            <a href="{{ route('category.create') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                + Add Category
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <table id="categoryTable" class="datatable w-full text-sm">

                <thead class="bg-gray-50 dark:bg-gray-800">
                <tr class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Parent</th>
                    <th class="px-4 py-3 text-center">Subcategories</th>
                    <th class="px-4 py-3 text-center">Sort order</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-right">Action</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-900">
                {{-- DataTables populates this --}}
                </tbody>

            </table>
        </div>

    </div>

@endsection
