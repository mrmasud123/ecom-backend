import 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';

$(function () {
    $('#productTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/products/data',
            type: 'GET',
            error: function (xhr) {
                console.error("AJAX Error:", xhr.responseText);
            }
        },
        columnDefs: [
            {
                targets: '_all',
                createdCell: (td) => $(td).addClass('px-4 py-3 text-gray-700 dark:text-gray-200')
            },
            {
                targets: 1,
                createdCell: (td) => $(td).addClass('text-center')
            },
            {
                targets: -1,
                createdCell: (td) => $(td).addClass('text-right')
            }
        ],
        columns: [
            {
                data: 'name',
                name: 'name',
                render: (data) => `<span class="font-medium text-gray-800 dark:text-white">${data}</span>`
            },
            {
                data: 'variants_count',
                name: 'variants_count',
                render: (data) => `<span class="inline-flex items-center justify-center h-6 w-6 rounded-md bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 font-semibold text-xs">${data}</span>`
            },
            { data: 'brand', name: 'brand' },
            { data: 'category', name: 'category' },
            { data: 'productType', name: 'productType', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        rowCallback: (row) => $(row).addClass('hover:bg-gray-50 dark:hover:bg-gray-800/60 transition-colors'),
        language: {
            search: "",
            searchPlaceholder: "Search products..."
        }
    });

    $('#productTable').on('click', '.delete-product', function () {
        const button = $(this);
        const url = button.data('url');

        Swal.fire({
            title: 'Delete this product?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            confirmButtonColor: '#dc2626',
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: url,
                method: 'DELETE',
                success: function (response) {
                    Swal.fire('Deleted!', response.message || 'Product deleted successfully.', 'success');
                    $('#productTable').DataTable().ajax.reload(null, false); // keep current page
                },
                error: function (xhr) {
                    Swal.fire('Error!', xhr.responseJSON?.message || 'Something went wrong', 'error');
                }
            });
        });
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#productForm').on('submit', function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const actionUrl = $(form).attr('action');

        let method = $(form).find('input[name="_method"]').val() || 'POST';
        console.log("Form method:", method);
        Swal.fire({
            title: 'Processing...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: actionUrl,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $(form).find('button[type="submit"]').prop('disabled', true);
            },
            complete: function () {
                $(form).find('button[type="submit"]').prop('disabled', false);
            },
            success: function (response) {
                Swal.close();

                Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success'
                }).then(() => {
                    window.location.href = "/products";
                });
            },

            error: function (xhr) {
                Swal.close();

                let errorMsg = 'Something went wrong';

                if (xhr.responseJSON?.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                }

                Swal.fire({
                    title: 'Error!',
                    text: errorMsg,
                    icon: 'error'
                });
            }
        });
    });


    $('#batchForm').on('submit', function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const actionUrl = $(form).attr('action');

        Swal.fire({
            title: 'Adding Stock...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: actionUrl,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $(form).find('button[type="submit"]').prop('disabled', true);
            },
            complete: function () {
                $(form).find('button[type="submit"]').prop('disabled', false);
            },
            success: function (response) {
                Swal.close();

                Swal.fire({
                    title: 'Success!',
                    text: response.message || 'Stock added successfully',
                    icon: 'success'
                }).then(() => {
                    // Option 1: redirect
                    window.location.href = "/products";

                    // Option 2 (better UX): reset form instead
                    // form.reset();
                });
            },

            error: function (xhr) {
                Swal.close();

                let errorMsg = 'Something went wrong';

                if (xhr.responseJSON?.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                } else if (xhr.responseJSON?.message) {
                    errorMsg = xhr.responseJSON.message;
                }

                Swal.fire({
                    title: 'Error!',
                    text: errorMsg,
                    icon: 'error'
                });
            }
        });
    });


});
