@extends('layouts.app')

@section('vendor-scripts')
    @vite(['Modules/Marketing/resources/assets/js/index-discount.js']);
@endsection

@section('content')

    <x-common.page-breadcrumb :pageTitle="[['name' => 'Discounts / Campaigns', 'link' => '#']]" />

    {{-- Summary strip --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-2xl bg-white dark:bg-white/5 shadow-lg p-5 flex items-center gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-semibold text-gray-800 dark:text-white" id="statActive">—</p>
                <p class="text-xs text-gray-400">Active campaigns</p>
            </div>
        </div>

        <div class="rounded-2xl bg-white dark:bg-white/5 shadow-lg p-5 flex items-center gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                    <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-semibold text-gray-800 dark:text-white" id="statScheduled">—</p>
                <p class="text-xs text-gray-400">Scheduled (upcoming)</p>
            </div>
        </div>

        <div class="rounded-2xl bg-white dark:bg-white/5 shadow-lg p-5 flex items-center gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-semibold text-gray-800 dark:text-white" id="statExpired">—</p>
                <p class="text-xs text-gray-400">Expired / inactive</p>
            </div>
        </div>
    </div>

    {{-- Main table card --}}
    <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">All campaigns</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Manage percentage and fixed-amount discounts.</p>
            </div>
            <a href="{{ route('discount.create') }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                New campaign
            </a>
        </div>
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <table id="discountTable" class="w-full stripe hover">
                <thead>
                <tr class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                    <th class="px-4 py-3">Campaign</th>
                    <th class="px-4 py-3">Value</th>
                    <th class="px-4 py-3">Applies to</th>
                    <th class="px-4 py-3">Schedule</th>
                    <th class="text-center">Status</th>
                    <th class="text-right" style="text-align: right">Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection
