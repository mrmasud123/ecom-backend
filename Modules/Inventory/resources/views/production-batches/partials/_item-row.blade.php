<tr class="item-row">
    <td class="py-2 pr-3">
        <select name="items[{{ $index }}][product_variant_id]" {{ $locked ?? false ? 'disabled' : '' }}
        class="variant-select h-9 w-full appearance-none rounded-md border border-gray-300 bg-transparent px-2 text-sm text-gray-800 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white dark:disabled:bg-white/5">
            <option value="">Select product...</option>
            {{-- Populate via your existing product-variant list/API, mirroring how variants are selected elsewhere --}}
            @foreach (\Modules\Product\Models\ProductVariant::with('product')->get() as $variant)
                <option value="{{ $variant->id }}" {{ ($item->product_variant_id ?? null) == $variant->id ? 'selected' : '' }}>
                    {{ $variant->product->name ?? '' }} — {{ $variant->sku ?? $variant->name }}
                </option>
            @endforeach
        </select>
    </td>
    <td class="py-2 px-3">
        <input type="number" min="1" name="items[{{ $index }}][quantity_produced]"
               value="{{ $item->quantity_produced ?? '' }}" placeholder="0" {{ $locked ?? false ? 'disabled' : '' }}
               class="qty-input h-9 w-full rounded-md border border-gray-300 bg-transparent px-2 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white dark:disabled:bg-white/5">
    </td>
    <td class="py-2 px-3">
        <input type="number" min="0" step="0.01" name="items[{{ $index }}][unit_cost]"
               value="{{ $item->unit_cost ?? '' }}" placeholder="0.00" {{ $locked ?? false ? 'disabled' : '' }}
               class="cost-input h-9 w-full rounded-md border border-gray-300 bg-transparent px-2 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white dark:disabled:bg-white/5">
    </td>
    <td class="line-total py-2 px-3 text-sm font-medium text-gray-800 dark:text-white">৳0.00</td>
    <td class="py-2 pl-3 text-right">
        @if (! ($locked ?? false))
            <button type="button" class="remove-row-btn text-gray-400 hover:text-red-600">
                <iconify-icon icon="lucide:x"></iconify-icon>
            </button>
        @endif
    </td>
</tr>
