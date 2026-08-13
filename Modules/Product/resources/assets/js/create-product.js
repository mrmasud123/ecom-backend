$(function () {
    // Auto-generate slug from name, but stop overwriting once the user edits it manually
    let slugEdited = false;
    $('#slug').on('input', () => slugEdited = true);
    $('#name').on('input', function () {
        if (slugEdited) return;
        $('#slug').val(
            $(this).val()
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
        );
    });

    // Show/hide quantity fields based on "Track quantity" toggle
    function toggleQuantityFields() {
        $('#quantityFields').toggle($('#track_quantity').is(':checked'));
    }
    $('#track_quantity').on('change', toggleQuantityFields);
    toggleQuantityFields();

    // Live image previews with remove buttons
    const dt = new DataTransfer(); // lets us actually remove a file from the input, not just hide its preview
    $('#images').on('change', function () {
        Array.from(this.files).forEach(file => dt.items.add(file));
        this.files = dt.files;
        renderPreviews();
    });

    function renderPreviews() {
        $('#imagePreviewGrid').empty();
        Array.from(dt.files).forEach((file, index) => {
            const url = URL.createObjectURL(file);
            const isFirst = index === 0;
            $('#imagePreviewGrid').append(`
                <div class="relative group rounded-lg overflow-hidden border ${isFirst ? 'border-blue-400 ring-2 ring-blue-400/40' : 'border-gray-200 dark:border-gray-700'} aspect-square">
                    <img src="${url}" class="h-full w-full object-cover">
                    ${isFirst ? '<span class="absolute bottom-1 left-1 rounded bg-blue-600 px-1.5 py-0.5 text-[10px] font-medium text-white">Cover</span>' : ''}
                    <button type="button" data-index="${index}"
                            class="remove-image absolute top-1 right-1 flex h-5 w-5 items-center justify-center rounded-full bg-black/60 text-white opacity-0 group-hover:opacity-100 transition text-xs">
                        &times;
                    </button>
                </div>
            `);
        });
    }

    $('#imagePreviewGrid').on('click', '.remove-image', function () {
        const index = $(this).data('index');
        const newDt = new DataTransfer();
        Array.from(dt.files).forEach((file, i) => { if (i !== index) newDt.items.add(file); });
        dt.items.clear();
        Array.from(newDt.files).forEach(file => dt.items.add(file));
        $('#images')[0].files = dt.files;
        renderPreviews();
    });

    // Submit via AJAX, same pattern as product-index.js
    $('#createProductForm').on('submit', function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        Swal.fire({ title: 'Saving product...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        $.ajax({
            url: $(form).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: () => $(form).find('button[type="submit"]').prop('disabled', true),
            complete: () => $(form).find('button[type="submit"]').prop('disabled', false),
            success: function (response) {
                Swal.close();
                Swal.fire({ title: 'Success!', text: response.message || 'Product created successfully', icon: 'success' })
                    .then(() => window.location.href = "/products");
            },
            error: function (xhr) {
                Swal.close();
                let errorMsg = 'Something went wrong';
                if (xhr.responseJSON?.errors) errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                else if (xhr.responseJSON?.message) errorMsg = xhr.responseJSON.message;
                Swal.fire({ title: 'Error!', text: errorMsg, icon: 'error' });
            }
        });
    });


    //Product Variants
    $(function () {
        const $attributeValuesContainer = $('#attributeValuesContainer');
        const $variantRows = $('#variantRows');
        const $generateBtn = $('#generateVariantsBtn');
        const $variantsCard = $('#variantsCard');
        const $inventoryCard = $('#inventoryCard');
        const $hasVariantsToggle = $('#has_variants');

        let variantRowIndex = $variantRows.find('.variant-row').length;

        const syncVariantsVisibility = () => {
            const enabled = $hasVariantsToggle.is(':checked');
            $variantsCard.toggleClass('hidden', !enabled);
            $inventoryCard.toggleClass('hidden', enabled);
        };
        $hasVariantsToggle.on('change', syncVariantsVisibility);
        syncVariantsVisibility();

        const renderAttributeValueBlocks = () => {
            const previouslyChecked = $('.attribute-value-select:checked')
                .map(function () { return $(this).val(); })
                .get();
            const usedValueIds = new Set();
            $('.variant-row').each(function () {
                const ids = ($(this).find('input[name$="[attribute_value_ids]"]').val() || '')
                    .split(',')
                    .filter(Boolean);
                ids.forEach((id) => usedValueIds.add(id));
            });

            $attributeValuesContainer.empty();

            $('.attribute-select:checked').each(function () {
                const attributeId = $(this).val();
                const attributeName = $(this).closest('.attribute-checkbox').text().trim();
                const values = JSON.parse($(this).attr('data-values'));

                const checkboxesHtml = values.map((v) => {
                    const isChecked = previouslyChecked.includes(String(v.id)) || usedValueIds.has(String(v.id));
                    return `
                    <label class="flex items-center gap-1.5 text-sm text-gray-700 dark:text-gray-300">
                        <input type="checkbox" class="attribute-value-select h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-700"
                               data-attribute-id="${attributeId}" value="${v.id}" data-label="${v.value}" ${isChecked ? 'checked' : ''}>
                        ${v.value}
                    </label>
                `;
                }).join('');

                const $block = $(`
                <div class="attribute-value-block" data-attribute-id="${attributeId}">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">${attributeName} values</label>
                    <div class="flex flex-wrap gap-3 rounded-lg border border-gray-200 p-3 dark:border-gray-700">
                        ${checkboxesHtml}
                    </div>
                </div>
            `);

                $attributeValuesContainer.append($block);
            });
        };

        $(document).on('change', '.attribute-select', renderAttributeValueBlocks);
        renderAttributeValueBlocks();

        const cartesianProduct = (groups) => {
            return groups.reduce(
                (acc, group) => acc.flatMap((combo) => group.map((item) => [...combo, item])),
                [[]]
            );
        };

        $generateBtn.on('click', () => {
            const groupsMap = {};
            $('.attribute-value-select:checked').each(function () {
                const attrId = $(this).data('attribute-id');
                const id = $(this).val();
                const label = $(this).data('label');
                if (!groupsMap[attrId]) groupsMap[attrId] = [];
                groupsMap[attrId].push({ id, label });
            });

            const groups = Object.values(groupsMap);

            if (groups.length === 0) {
                Swal.fire({ icon: 'info', text: 'Select at least one attribute value first.', timer: 1800, showConfirmButton: false });
                return;
            }

            const combinations = cartesianProduct(groups);

            const existingRows = {};
            $('.variant-row').each(function () {
                const ids = ($(this).find('input[name$="[attribute_value_ids]"]').val() || '')
                    .split(',')
                    .filter(Boolean)
                    .sort()
                    .join(',');
                existingRows[ids] = $(this);
            });

            const keptSignatures = new Set();
            $variantRows.find('#noVariantsRow').remove();

            combinations.forEach((combo) => {
                const ids = combo.map((c) => c.id).sort();
                const signature = ids.join(',');
                keptSignatures.add(signature);

                if (existingRows[signature]) {
                    return;
                }

                const label = combo.map((c) => c.label).join(' / ');
                const idsCsv = combo.map((c) => c.id).join(',');

                const $row = $(`
                <tr class="variant-row">
                    <input type="hidden" name="variants[${variantRowIndex}][attribute_value_ids]" value="${idsCsv}">
                    <td class="py-2 pr-1 text-gray-700 dark:text-gray-300">${label}</td>
                    <td class="py-2 px-1">
                        <input type="text" name="variants[${variantRowIndex}][sku]" placeholder="SKU"
                               class="h-9 w-32 rounded-md border border-gray-300 bg-transparent px-2 text-sm dark:border-gray-700 dark:text-white">
                    </td>
                    <td class="py-2 px-1">
                        <input type="number" step="0.01" min="0" name="variants[${variantRowIndex}][price]" placeholder="0.00"
                               class="h-9 w-24 rounded-md border border-gray-300 bg-transparent px-2 text-sm dark:border-gray-700 dark:text-white">
                    </td>
                    <td class="py-2 px-1">
                        <input type="number" step="0.01" min="0" name="variants[${variantRowIndex}][compare_price]" placeholder="0.00"
                               class="h-9 w-24 rounded-md border border-gray-300 bg-transparent px-2 text-sm dark:border-gray-700 dark:text-white">
                    </td>
                    <td class="py-2 px-1">
                        <input type="number" min="0" name="variants[${variantRowIndex}][quantity]" placeholder="0"
                               class="h-9 w-20 rounded-md border border-gray-300 bg-transparent px-2 text-sm dark:border-gray-700 dark:text-white">
                    </td>
                    <td class="py-2 px-1 text-center">
                        <input type="checkbox" name="variants[${variantRowIndex}][is_active]" value="1" checked
                               class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-700">
                    </td>
                    <td class="py-2 pl-1 text-right">
                        <button type="button" class="remove-variant-row text-gray-400 hover:text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </td>
                </tr>
            `);

                $variantRows.append($row);
                variantRowIndex++;
            });

            // remove rows whose combination is no longer selected
            $('.variant-row').each(function () {
                const ids = ($(this).find('input[name$="[attribute_value_ids]"]').val() || '')
                    .split(',')
                    .filter(Boolean)
                    .sort()
                    .join(',');

                if (!keptSignatures.has(ids)) {
                    $(this).remove();
                }
            });

            if ($variantRows.find('.variant-row').length === 0) {
                $variantRows.append('<tr id="noVariantsRow"><td colspan="7" class="py-6 text-center text-xs text-gray-400">Select attributes above and click "Generate combinations" to create variants.</td></tr>');
            }
        });

        $variantRows.on('click', '.remove-variant-row', function () {
            $(this).closest('.variant-row').remove();

            if ($variantRows.find('.variant-row').length === 0) {
                $variantRows.append('<tr id="noVariantsRow"><td colspan="7" class="py-6 text-center text-xs text-gray-400">Select attributes above and click "Generate combinations" to create variants.</td></tr>');
            }
        });

        $('#existingImageGrid').on('click', '.remove-existing-image', function () {
            const mediaId = $(this).data('media-id');
            $(`#removedMediaIds`).append(`<input type="hidden" name="removed_media_ids[]" value="${mediaId}">`);
            $(this).closest('[data-media-id]').fadeOut(150, function () { $(this).remove(); });
        });

        let slugManuallyEdited = false;
        $('#slug').on('input', () => (slugManuallyEdited = true));
        $('#name').on('input', function () {
            if (!slugManuallyEdited) {
                $('#slug').val($(this).val().toString().trim().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, ''));
            }
        });
    });

});
