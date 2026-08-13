@php $isEdit = isset($batch); @endphp

<div class="space-y-6">

    {{-- Batch info --}}
    <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">
        <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white">Batch information</h3>
        <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">Which warehouse this stock is being added to, and when.</p>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Batch number</label>
                <input type="text" value="{{ $isEdit ? $batch->batch_number : $nextBatchNumber }}" disabled
                       class="h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 text-sm text-gray-500 dark:border-gray-700 dark:bg-white/5 dark:text-gray-400">
            </div>

            <div>
                <label for="warehouse_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Warehouse <span class="text-red-500">*</span>
                </label>
                <select id="warehouse_id" name="warehouse_id" {{ $isEdit && $batch->isCompleted() ? 'disabled' : '' }}
                class="h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white dark:disabled:bg-white/5">
                    <option value="">Select warehouse</option>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}"
                            {{ old('warehouse_id', $batch->warehouse_id ?? '') == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->name }}
                        </option>
                    @endforeach
                </select>
                @error('warehouse_id') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="production_date" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Production date <span class="text-red-500">*</span>
                </label>
                <input type="date" id="production_date" name="production_date"
                       value="{{ old('production_date', isset($batch) ? $batch->production_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                       {{ $isEdit && $batch->isCompleted() ? 'disabled' : '' }}
                       class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white dark:disabled:bg-white/5">
                @error('production_date') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="notes" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                <textarea id="notes" name="notes" rows="2" placeholder="Optional notes about this batch"
                          {{ $isEdit && $batch->isCompleted() ? 'disabled' : '' }}
                          class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white dark:disabled:bg-white/5">{{ old('notes', $batch->notes ?? '') }}</textarea>
            </div>

        </div>
    </div>

    {{-- Items --}}
    <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white">Products produced</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Add each product variant and how many units were made.</p>
            </div>
            @if (! $isEdit || ! $batch->isCompleted())
                <button type="button" id="add-item-row"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/10">
                    <iconify-icon icon="lucide:plus"></iconify-icon> Add product
                </button>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" id="items-table">
                <thead>
                <tr class="border-b border-gray-200 text-xs uppercase text-gray-400 dark:border-gray-700">
                    <th class="py-2 pr-1">Product variant</th>
                    <th class="w-32 py-2 px-1">Quantity</th>
                    <th class="w-40 py-2 px-1">Unit cost</th>
                    <th class="w-32 py-2 px-1">Line total</th>
                    <th class="w-10"></th>
                </tr>
                </thead>
                <tbody id="items-body" class="divide-y divide-gray-100 dark:divide-gray-800">
                @php $existingItems = $isEdit ? $batch->items : collect([null]); @endphp
                @foreach ($existingItems as $index => $item)
                    @include('inventory::production-batches.partials._item-row', ['item' => $item, 'index' => $index, 'locked' => $isEdit && $batch->isCompleted()])
                @endforeach
                </tbody>
            </table>
        </div>
        @error('items') <span class="mt-2 block text-xs text-red-500">{{ $message }}</span> @enderror

        <div class="mt-4 flex justify-end border-t border-gray-100 pt-4 dark:border-gray-700">
            <div class="text-sm">
                <span class="text-gray-500 dark:text-gray-400">Estimated total cost:</span>
                <span id="grand-total" class="ml-2 text-lg font-semibold text-gray-800 dark:text-white">৳0.00</span>
            </div>
        </div>
    </div>

</div>

{{-- Actions --}}
<div class="mt-6 flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 dark:border-gray-700 sm:flex-row sm:justify-between">
    <a href="{{ route('production-batches.index') }}"
       class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/10">
        <iconify-icon icon="lucide:arrow-left"></iconify-icon> Back
    </a>

    <div class="flex flex-col-reverse gap-3 sm:flex-row">
        @if ($isEdit && ! $batch->isCompleted())
            <button type="button" id="complete-batch-btn" data-id="{{ $batch->id }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-green-700">
                <iconify-icon icon="lucide:check-circle-2"></iconify-icon> Complete &amp; add to stock
            </button>
        @endif

        @if (! $isEdit || ! $batch->isCompleted())
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                <iconify-icon icon="lucide:save"></iconify-icon> Save draft
            </button>
        @endif
    </div>
</div>
