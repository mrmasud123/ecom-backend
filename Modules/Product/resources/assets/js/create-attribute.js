import Swal from 'sweetalert2';

$(function () {
    const $form = $('#createAttributeForm');
    const $nameInput = $('#name');
    const $slugInput = $('#slug');
    const $typeSelect = $('#type');
    const $valueRows = $('#valueRows');
    const $addValueBtn = $('#addValueRow');

    let rowIndex = 1;

    const slugify = (str) =>
        str
            .toString()
            .trim()
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');

    let slugManuallyEdited = false;
    $slugInput.on('input', () => (slugManuallyEdited = true));
    $nameInput.on('input', function () {
        if (!slugManuallyEdited) {
            $slugInput.val(slugify($(this).val()));
        }
    });

    const toggleColorFields = () => {
        const isColor = $typeSelect.val() === 'color';
        $('.color-field').toggleClass('hidden', !isColor);
    };
    $typeSelect.on('change', toggleColorFields);
    toggleColorFields(); // run once on load

    const buildRow = (index) => {
        const showColor = $typeSelect.val() === 'color' ? '' : 'hidden';
        return $(`
            <div class="attribute-value-row grid grid-cols-1 gap-3 sm:grid-cols-[1fr_1fr_auto_auto] items-center">
                <input type="text" name="values[${index}][value]" placeholder="e.g. Blue"
                       class="value-input h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                <input type="text" name="values[${index}][slug]" placeholder="blue"
                       class="slug-input h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-400 focus:outline-none focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:text-white">
                <input type="color" name="values[${index}][color_code]" value="#000000"
                       class="color-field ${showColor} h-11 w-14 cursor-pointer rounded-lg border border-gray-300 bg-transparent p-1 dark:border-gray-700">
                <button type="button" class="remove-value-row inline-flex h-11 w-11 items-center justify-center rounded-lg text-gray-400 transition hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        `);
    };

    const bindRowSlugify = ($row) => {
        const $valueInput = $row.find('.value-input');
        const $slugField = $row.find('.slug-input');
        let edited = false;

        $slugField.on('input', () => (edited = true));
        $valueInput.on('input', function () {
            if (!edited) {
                $slugField.val(slugify($(this).val()));
            }
        });
    };

    bindRowSlugify($valueRows.find('.attribute-value-row').first());

    $addValueBtn.on('click', () => {
        const $row = buildRow(rowIndex);
        $valueRows.append($row);
        bindRowSlugify($row);
        rowIndex++;
    });

    $valueRows.on('click', '.remove-value-row', function () {
        const $rows = $valueRows.find('.attribute-value-row');

        if ($rows.length === 1) {
            Swal.fire({
                icon: 'info',
                text: 'At least one value row is required.',
                timer: 1800,
                showConfirmButton: false,
            });
            return;
        }

        $(this).closest('.attribute-value-row').remove();
    });

    $form.on('submit', function (e) {
        e.preventDefault();

        const $submitBtn = $form.find('button[type="submit"]');
        const originalText = $submitBtn.html();
        $submitBtn.prop('disabled', true).html('Saving...');

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .done((result) => {
                Swal.fire({
                    icon: 'success',
                    title: 'Attribute saved',
                    timer: 1500,
                    showConfirmButton: false,
                }).then(() => {
                    window.location.href = result.redirect || '/products/attributes';
                });
            })
            .fail((xhr) => {
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
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
});
