@extends('layouts.app')
@section('vendor-scripts')
    @vite([
        'Modules/Inventory/resources/assets/js/calc-batches.js'
    ]);
@endsection
@section('title', 'Edit Production Batch')

@section('content')

    <x-common.page-breadcrumb :pageTitle="[['name' => 'Inventory', 'link' => '#'], ['name' => 'Stock Production', 'link' => route('production-batches.index')], ['name' => $batch->batch_number, 'link' => '#']]" />

    <div class="mb-6 flex items-center gap-3">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
            {{ $batch->isCompleted() ? 'View' : 'Edit' }} batch — {{ $batch->batch_number }}
        </h2>
        @if ($batch->isCompleted())
            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400">Completed</span>
        @else
            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700 dark:bg-amber-900/20 dark:text-amber-400">Draft</span>
        @endif
    </div>

    @if ($batch->isCompleted())
        <div class="mb-6 flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-900/40 dark:bg-green-900/10 dark:text-green-400">
            <iconify-icon icon="lucide:info" class="text-lg"></iconify-icon>
            This batch has already been posted to stock and is locked from editing to keep the stock ledger accurate.
        </div>
    @endif

    <form action="{{ route('production-batches.update', $batch) }}" method="POST">
        @csrf
        @method('PUT')
        @include('inventory::production-batches.partials._form')
    </form>

@endsection

