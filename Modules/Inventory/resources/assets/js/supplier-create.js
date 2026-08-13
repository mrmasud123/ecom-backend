// Modules/Product/resources/assets/js/brand-create.js
import Swal from 'sweetalert2';

$(function () {
    const $form = $('#brandCreateForm');
    const storeUrl = $form.data('store-url');
    const indexUrl = $form.data('index-url');
    const $submitBtn = $('#submitBtn');

    const MAX_SIZE_MB = 2;
    const ALLOWED_TYPES = ['image/png', 'image/jpeg', 'image/webp'];

    const $dropzone = $('#logoDropzone');
    const $input = $('#logo');
    const $previewWrapper = $('#logoPreviewWrapper');
    const $fileName = $('#logoFileName');
    const $fileMeta = $('#logoFileMeta');
    const $removeBtn = $('#logoRemoveBtn');

    const defaultPreviewHtml = `<iconify-icon icon="lucide:image" class="text-2xl text-gray-400"></iconify-icon>`;

    function resetLogo() {
        $input.val('');
        $previewWrapper.html(defaultPreviewHtml);
        $fileName.text('Click or drag an image to upload').removeClass('text-gray-700 dark:text-gray-300').addClass('text-gray-500 dark:text-gray-400');
        $fileMeta.text('PNG, JPG or WEBP — max 2MB');
        $removeBtn.addClass('hidden');
        $('.error-text[data-field="logo"]').text('');
    }

    function formatSize(bytes) {
        return (bytes / 1024 / 1024).toFixed(2) + ' MB';
    }

    function handleFile(file) {
        if (!file) return;

        if (!ALLOWED_TYPES.includes(file.type)) {
            $('.error-text[data-field="logo"]').text('Only PNG, JPG, or WEBP images are allowed.');
            return;
        }

        if (file.size > MAX_SIZE_MB * 1024 * 1024) {
            $('.error-text[data-field="logo"]').text(`Image must be smaller than ${MAX_SIZE_MB}MB.`);
            return;
        }

        $('.error-text[data-field="logo"]').text('');

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        $input[0].files = dataTransfer.files;

        const reader = new FileReader();
        reader.onload = (ev) => {
            $previewWrapper.html(`<img src="${ev.target.result}" class="h-full w-full object-cover">`);
        };
        reader.readAsDataURL(file);

        $fileName.text(file.name).removeClass('text-gray-500 dark:text-gray-400').addClass('text-gray-700 dark:text-gray-300');
        $fileMeta.text(formatSize(file.size));
        $removeBtn.removeClass('hidden');
    }

    // No click handler on the label needed — browser handles label→input natively.

    $input.on('change', function (e) {
        handleFile(e.target.files[0]);
    });

    $removeBtn.on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        resetLogo();
    });

    // Drag and drop
    $dropzone.on('dragover', function (e) {
        e.preventDefault();
        $(this).removeClass('border-gray-300 dark:border-gray-700').addClass('border-blue-400 dark:border-blue-500 bg-blue-50/50 dark:bg-blue-500/5');
    });

    $dropzone.on('dragleave drop', function (e) {
        e.preventDefault();
        $(this).addClass('border-gray-300 dark:border-gray-700').removeClass('border-blue-400 dark:border-blue-500 bg-blue-50/50 dark:bg-blue-500/5');
    });

    $dropzone.on('drop', function (e) {
        e.preventDefault();
        const file = e.originalEvent.dataTransfer.files[0];
        handleFile(file);
    });

    // Auto-slug preview
    $('#name').on('input', function () {
        if ($('#slug').data('touched')) return;
        const slug = $(this).val().toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
        $('#slug').attr('placeholder', slug || 'auto-generated from name');
    });

    $('#slug').on('input', function () {
        $(this).data('touched', true);
    });

    // Form submission
    $form.on('submit', function (e) {
        e.preventDefault();

        $('.error-text').text('');
        const formData = new FormData(this);
        formData.set('is_active', $('#is_active').is(':checked') ? 1 : 0);

        Swal.fire({
            title: 'Saving brand...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading(),
        });
        $submitBtn.prop('disabled', true);

        $.ajax({
            url: storeUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                Swal.fire({
                    title: 'Success!',
                    text: response.message || 'Brand created successfully.',
                    icon: 'success',
                }).then(() => {
                    window.location.href = indexUrl;
                });
            },
            error: function (xhr) {
                Swal.close();

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach((field) => {
                        $(`.error-text[data-field="${field}"]`).text(errors[field][0]);
                    });

                    Swal.fire({
                        title: 'Validation error',
                        text: Object.values(errors)[0][0],
                        icon: 'error',
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Something went wrong.',
                        icon: 'error',
                    });
                }
            },
            complete: function () {
                $submitBtn.prop('disabled', false);
            },
        });
    });
});
