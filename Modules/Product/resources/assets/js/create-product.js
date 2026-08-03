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
});
