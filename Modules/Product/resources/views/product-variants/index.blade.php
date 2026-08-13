@extends('layouts.app')

@section('vendor-scripts')
    @vite(['Modules/Product/resources/assets/js/variant-index.js']);
@endsection

@section('content')

    <x-common.page-breadcrumb :pageTitle="[['name' => 'Product Variants', 'link' => '#']]" />

    {{-- Summary strip --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-4">
        <div class="rounded-2xl bg-white dark:bg-white/5 shadow-lg p-5 flex items-center gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-semibold text-gray-800 dark:text-white" id="statTotal">—</p>
                <p class="text-xs text-gray-400">Total variants</p>
            </div>
        </div>

        <div class="rounded-2xl bg-white dark:bg-white/5 shadow-lg p-5 flex items-center gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-semibold text-gray-800 dark:text-white" id="statActive">—</p>
                <p class="text-xs text-gray-400">Active</p>
            </div>
        </div>

        <div class="rounded-2xl bg-white dark:bg-white/5 shadow-lg p-5 flex items-center gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-semibold text-gray-800 dark:text-white" id="statLowStock">—</p>
                <p class="text-xs text-gray-400">Low stock (&lt; 10)</p>
            </div>
        </div>

        <div class="rounded-2xl bg-white dark:bg-white/5 shadow-lg p-5 flex items-center gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-semibold text-gray-800 dark:text-white" id="statOutOfStock">—</p>
                <p class="text-xs text-gray-400">Out of stock</p>
            </div>
        </div>
    </div>

    {{-- Main table card --}}
    <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">All variants</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Every sellable SKU across your variant products.</p>
            </div>
            <div class="flex items-center gap-2">
                <select id="stockFilter" class="h-10 rounded-lg border border-gray-300 bg-transparent px-3 text-sm text-gray-700 focus:border-blue-400 focus:outline-none dark:border-gray-700 dark:text-gray-300">
                    <option value="">All stock levels</option>
                    <option value="in_stock">In stock</option>
                    <option value="low_stock">Low stock</option>
                    <option value="out_of_stock">Out of stock</option>
                </select>
            </div>
        </div>

        <table id="variantTable" class="w-full stripe hover">
            <thead>
            <tr class="text-gray-500 dark:text-gray-400">
                <th>Variant</th>
                <th>SKU</th>
                <th>Combination</th>
                <th>Price</th>
                <th>Stock</th>
                <th class="text-center">Status</th>
                <th class="text-right">Action</th>
            </tr>
            </thead>
        </table>
    </div>

@endsection
