@extends('layouts.app')


@section('vendor-scripts')
    @vite(['Modules/Product/resources/assets/js/product-index.js']);
@endsection
@section('content')

    <x-common.page-breadcrumb :pageTitle="[['name' => 'Products', 'link'=> '#']]" />

    <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">

        <div class="flex justify-between mb-5">
            <h2 class="text-xl font-semibold dark:text-white">Manage Products</h2>
            <a href="{{route('product.create')}}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                + Add Product
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <table id="productTable" class="datatable w-full text-sm">

                <thead class="bg-gray-50 dark:bg-gray-800">
                <tr class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Brand</th>
                    <th class="px-4 py-3 text-left">Category</th>
                    <th class="px-4 py-3 text-left">Product Type</th>
                    <th class="px-4 py-3 text-right" style="text-align: right">Action</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-900">
                {{-- DataTables will populate rows here --}}
                </tbody>

            </table>
        </div>

    </div>

@endsection
