import select2 from 'select2';
select2($);
$(document).ready((function () {

    // console.log(select2());
    $('.select2-target').select2({
        placeholder: 'Search and select...',
        width: '100%',
        allowClear: true,
    });

    $('#targetType').on('change', function () {
        const val = $(this).val();
        $('#productTargetPicker').toggleClass('hidden', val !== 'products');
        $('#categoryTargetPicker').toggleClass('hidden', val !== 'categories');
        $('#variantTargetPicker').toggleClass('hidden', val !== 'variants');
        $('#storewideNotice').toggleClass('hidden', val !== 'storewide');
    });


    const $typeInputs = $('input[name="type"]');
    const $value = $('#value');
    const $valuePrefix = $('#valuePrefix');
    const $valueSuffix = $('#valueSuffix');
    const $valuePreview = $('#valuePreview');
    const $name = $('#name');
    const $previewName = $('#previewName');
    const $previewBadge = $('#previewBadge');
    const $previewStatus = $('#previewStatus');
    const $startsAt = $('#starts_at');
    const $endsAt = $('#ends_at');
    const $targetType = $('#targetType');

    const updateValueUI = () => {
        const isPercentage = $('input[name="type"]:checked').val() === 'percentage';
        $valuePrefix.toggleClass('hidden', isPercentage);
        $valueSuffix.text(isPercentage ? '%' : '৳');
        updatePreview();
    };

    const updatePreview = () => {
        const type = $('input[name="type"]:checked').val();
        const value = parseFloat($value.val()) || 0;

        $previewBadge.text(type === 'percentage' ? `${value}% OFF` : `৳${value.toFixed(2)} OFF`);

        const sample = 1000;
        const final = type === 'percentage'
            ? sample * (1 - value / 100)
            : Math.max(0, sample - value);
        $valuePreview.html(`A ৳${sample} item will sell for <span class="font-semibold text-emerald-600 dark:text-emerald-400">৳${final.toFixed(2)}</span>`);

        const starts = $startsAt.val();
        const ends = $endsAt.val();
        if (!starts && !ends) {
            $previewStatus.text('Runs indefinitely, starting now');
        } else if (starts && ends) {
            $previewStatus.text(`Runs ${new Date(starts).toLocaleDateString()} – ${new Date(ends).toLocaleDateString()}`);
        } else if (starts) {
            $previewStatus.text(`Starts ${new Date(starts).toLocaleDateString()}`);
        } else {
            $previewStatus.text(`Ends ${new Date(ends).toLocaleDateString()}`);
        }
    };

    $typeInputs.on('change', updateValueUI);
    $value.on('input', updatePreview);
    $startsAt.on('change', updatePreview);
    $endsAt.on('change', updatePreview);
    $name.on('input', function () {
        $previewName.text($(this).val() || 'Untitled campaign');
    });

    updateValueUI();

    $targetType.on('change', function () {
        const val = $(this).val();
        $('#productTargetPicker').toggleClass('hidden', val !== 'products');
        $('#categoryTargetPicker').toggleClass('hidden', val !== 'categories');
        $('#variantTargetPicker').toggleClass('hidden', val !== 'variants');
        $('#storewideNotice').toggleClass('hidden', val !== 'storewide');
    });
    function showLaunchModal() {
        const $modal = $('#launchModal');
        $('#rocket').removeClass('launching');
        $('#flame').removeClass('burning');
        $('#successBadge').addClass('hidden').removeClass('flex').css('opacity', 0);
        $('#launchTitle').text('Launching your campaign…');
        $('#launchSubtitle').text('Setting up your discount');

        $modal.removeClass('hidden').addClass('flex');

        setTimeout(() => $('#flame').addClass('burning'), 200);
        setTimeout(() => $('#rocket').addClass('launching'), 500);
    }

    function showLaunchSuccess() {
        $('#launchTitle').text('Campaign launched');
        $('#launchSubtitle').text('Your discount is now live');
        $('#successBadge').removeClass('hidden').addClass('flex');
        requestAnimationFrame(() => $('#successBadge').css('opacity', 1));
    }

    function hideLaunchModal() {
        $('#launchModal').addClass('hidden').removeClass('flex');
    }
    // Submit the form
    // ============ Form submit (AJAX) ============
    $('#createDiscountForm').on('submit', function (e) {
        e.preventDefault();

        const $form = $(this);

        showLaunchModal();

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
            .done((result) => {
                showLaunchSuccess();
                setTimeout(() => {
                    window.location.href = result.redirect || '/discounts';
                }, 1400);
            })
            .fail((xhr) => {
                hideLaunchModal();
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    const firstError = Object.values(xhr.responseJSON.errors)[0][0];
                    Swal.fire({ icon: 'error', title: 'Validation error', text: firstError });
                } else {
                    Swal.fire({ icon: 'error', title: 'Something went wrong', text: xhr.responseJSON?.message || 'Please try again.' });
                }
            });
    });

// ============ Render inline validation errors on fields ============
    function renderValidationErrors(errors) {
        Object.keys(errors).forEach((field) => {
            // handles both flat fields ("name") and array fields ("product_ids")
            const $input = $(`[name="${field}"], [name="${field}[]"]`);

            if ($input.length) {
                // for Select2 boxes, the visible border lives on the sibling .select2-container
                const $target = $input.hasClass('select2-target')
                    ? $input.next('.select2-container').find('.select2-selection')
                    : $input;

                $target.addClass('error-border border-red-400');
                $input.after(`<p class="field-error mt-1 text-xs text-red-500">${errors[field][0]}</p>`);
            }
        });
    }
}));
