$(function () {
    const $attributeValuesContainer = $('#attributeValuesContainer');
    const $variantRows = $('#variantRows');
    const $generateBtn = $('#generateVariantsBtn');
    const $variantsCard = $('#variantsCard');
    const $inventoryCard = $('#inventoryCard');
    const $hasVariantsToggle = $('#has_variants');

    let variantRowIndex = $variantRows.find('.variant-row').length;

    const $imageInput = $('#images');
    const $imagePreviewGrid = $('#imagePreviewGrid');
    const $dropzone = $('label[for="images"]');

    let selectedFiles = [];

    const renderPreviews = () => {
        $imagePreviewGrid.empty();

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();

            reader.onload = (e) => {
                const $preview = $(`
                <div class="group relative aspect-square overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                    <img src="${e.target.result}" alt="" class="h-full w-full object-cover">
                    <button type="button" data-index="${index}"
                            class="remove-new-image absolute right-1 top-1 flex h-6 w-6 items-center justify-center rounded-full bg-black/60 text-white opacity-0 transition group-hover:opacity-100 hover:bg-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `);
                $imagePreviewGrid.append($preview);
            };

            reader.readAsDataURL(file);
        });
    };

    const syncInputFiles = () => {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach((file) => dataTransfer.items.add(file));
        $imageInput[0].files = dataTransfer.files;
    };

    const addFiles = (fileList) => {
        const newFiles = Array.from(fileList).filter((file) => file.type.startsWith('image/'));

        if (newFiles.length === 0) return;

        selectedFiles = [...selectedFiles, ...newFiles];
        syncInputFiles();
        renderPreviews();
    };

    $imageInput.on('change', function () {
        addFiles(this.files);
    });

    $imagePreviewGrid.on('click', '.remove-new-image', function (e) {
        e.preventDefault();
        const index = $(this).data('index');
        selectedFiles.splice(index, 1);
        syncInputFiles();
        renderPreviews();
    });

    $dropzone.on('dragover', function (e) {
        e.preventDefault();
        $(this).addClass('border-blue-400', 'bg-blue-50/40');
    });

    $dropzone.on('dragleave', function (e) {
        e.preventDefault();
        $(this).removeClass('border-blue-400', 'bg-blue-50/40');
    });

    $dropzone.on('drop', function (e) {
        e.preventDefault();
        $(this).removeClass('border-blue-400', 'bg-blue-50/40');
        addFiles(e.originalEvent.dataTransfer.files);
    });
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

    $('#editProductForm').on('submit', function (e) {
        e.preventDefault();

        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"]');
        const originalText = $submitBtn.html();

        const hasVariants = $('#has_variants').is(':checked');
        const variantRowCount = $('.variant-row').length;

        if (hasVariants && variantRowCount === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No variants added',
                text: '"Has variants" is on, but no variant rows exist. Generate combinations first, or turn variants off.',
            });
            return;
        }
        $submitBtn.prop('disabled', true).html('Updating...');
        const formData = new FormData(this);
        formData.append('_method', 'PUT');

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .done((result) => {
                Swal.fire({
                    icon: 'success',
                    title: 'Product updated',
                    timer: 1500,
                    showConfirmButton: false,
                }).then(() => {
                    window.location.href = result.redirect || '/products';
                });
            })
            .fail((xhr) => {
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    renderValidationErrors(xhr.responseJSON.errors);
                    const firstError = Object.values(xhr.responseJSON.errors)[0][0];
                    Swal.fire({ icon: 'error', title: 'Validation error', text: firstError });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong',
                        text: xhr.responseJSON?.message || 'Please try again.',
                    });
                }
            })
            .always(() => {
                $submitBtn.prop('disabled', false).html(originalText);
            });
    });

    function renderValidationErrors(errors) {
        $('.field-error').remove();
        $('.error-border').removeClass('error-border border-red-400');

        Object.keys(errors).forEach((field) => {
            const inputName = field.includes('.')
                ? field.replace(/\.(\d+)\./, '[$1][').replace(/\.([a-z_]+)$/, '[$1]') + (field.match(/\.[a-z_]+$/) ? '' : ']')
                : field;

            const $input = $(`[name="${inputName}"]`);
            if ($input.length) {
                $input.addClass('error-border border-red-400');
                $input.after(`<p class="field-error mt-1 text-xs text-red-500">${errors[field][0]}</p>`);
            }
        });
    }
});
