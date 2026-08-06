{{-- Modules/Product/resources/views/brand/index.blade.php --}}
@extends('layouts.app')

@section('vendor-scripts')
    @vite(['Modules/Product/resources/assets/js/brand-index.js']);
@endsection

{{--@section('vendor-css')--}}
{{--    @vite(['resources/css/datatable.css']);--}}
{{--@endsection--}}

@section('content')

    <x-common.page-breadcrumb :pageTitle="[['name' => 'Brands', 'link' => '#']]" />

    <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">

        <div class="flex justify-between items-center mb-5">
            <div>
                <h2 class="text-xl font-semibold dark:text-white">Manage Brands</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Manage product brands, logos, and visibility
                </p>
            </div>
            <a href="{{ route('brands.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <iconify-icon icon="lucide:plus" class="text-base"></iconify-icon>
                Add Brand
            </a>
        </div>

        <div class="overflow-x-auto">
            <table id="brandTable" class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200 uppercase text-xs">

                <tr class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                    <th class="px-4 py-3 text-left">Logo</th>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Slug</th>
                    <th class="px-4 py-3 text-center" style="text-align: center">Total Products</th>
                    <th class="px-4 py-3 text-center" style="text-align: center">Status</th>
                    <th class="px-4 py-3" style="text-align: right">Action</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                {{-- DataTables will populate rows here --}}
                </tbody>

            </table>
        </div>

    </div>

@endsection
