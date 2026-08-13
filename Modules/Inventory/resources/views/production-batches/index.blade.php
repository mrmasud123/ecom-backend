@extends('layouts.app')

@section('vendor-scripts')
    @vite([
        'Modules/Inventory/resources/assets/js/production-batches-index.js'
    ]);
@endsection

@section('title', 'Stock Production')

@section('content')
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6">

        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-black dark:text-white">Stock Production</h2>
                <p class="text-sm text-gray-500 dark:text-bodydark2">Batches of self-manufactured stock added to inventory</p>
            </div>
            <a href="{{ route('production-batches.create') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                <iconify-icon icon="lucide:plus"></iconify-icon>
                New Batch
            </a>
        </div>

{{--        <div class="rounded-xl border border-stroke bg-white p-4 shadow-sm dark:border-strokedark dark:bg-boxdark dark:ring-1 dark:ring-white/5">--}}
{{--            <div class="overflow-x-auto">--}}

{{--            </div>--}}
{{--        </div>--}}

        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <table id="batches-table" class="w-full table-auto">
                <thead>
                    <tr class="border-b border-stroke text-left dark:border-strokedark">
                        <th class="px-4 py-3 font-medium text-black dark:text-white">Batch #</th>
                        <th class="px-4 py-3 font-medium text-black dark:text-white">Warehouse</th>
                        <th class="px-4 py-3 font-medium text-black dark:text-white">Production Date</th>
                        <th class="px-4 py-3 font-medium text-black dark:text-white">Total Qty</th>
                        <th class="px-4 py-3 font-medium text-black dark:text-white">Status</th>
                        <th class="px-4 py-3 text-right font-medium text-black dark:text-white">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection
h
