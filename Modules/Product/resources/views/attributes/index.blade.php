
@extends('layouts.app')

@section('vendor-scripts')
    @vite(['Modules/Product/resources/assets/js/attribute-index.js']);
@endsection

@section('content')

    <x-common.page-breadcrumb :pageTitle="[['name' => 'Attributes', 'link' => '#']]" />

    <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">

        <div class="flex justify-between items-center mb-5">
            <div>
                <h2 class="text-xl font-semibold dark:text-white">Manage Attributes</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Define product attributes used for variants and filters
                </p>
            </div>
            <a href="{{ route('attributes.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <iconify-icon icon="lucide:plus" class="text-base"></iconify-icon>
                Add Attribute
            </a>
        </div>

        <div style="overflow-x: auto" class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <table id="attributeTable" class="datatable w-full text-sm text-gray-700 dark:text-gray-300">

                <thead class="bg-gray-50 dark:bg-gray-800">
                <tr class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Slug</th>
                    <th class="px-4 py-3 text-center" style="text-align: center">Type</th>
                    <th class="px-4 py-3 text-center">Values</th>
                    <th class="px-4 py-3 text-center" style="text-align: center">Status</th>
                    <th class="px-4 py-3 text-right" style="text-align: right">Action</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                {{-- DataTables will populate rows here --}}
                </tbody>

            </table>
        </div>

    </div>

@endsection
