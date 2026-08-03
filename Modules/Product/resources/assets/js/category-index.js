import 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';
$(function () {
    $('#categoryTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/categories/data',
            type: 'GET',
            error: function (xhr) {
                console.error("AJAX Error:", xhr.responseText);
            }
        },
        columnDefs: [
            { targets: '_all', createdCell: (td) => $(td).addClass('px-4 py-3 text-gray-700 dark:text-gray-200') },
            { targets: [2, 3, 4], createdCell: (td) => $(td).addClass('text-center') },
            { targets: -1, createdCell: (td) => $(td).addClass('text-right') }
        ],
        columns: [
            { data: 'name', name: 'name', render: (data) => `<span class="font-medium text-gray-800 dark:text-white">${data}</span>` },
            { data: 'parent', name: 'parent' },
            { data: 'children_count', name: 'children_count', orderable: false, searchable: false },
            { data: 'sort_order', name: 'sort_order' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        rowCallback: (row) => $(row).addClass('hover:bg-gray-50 dark:hover:bg-gray-800/60 transition-colors'),
        language: { search: "", searchPlaceholder: "Search categories..." }
    });

    $('#categoryTable').on('click', '.delete-category', function () {
        const url = $(this).data('url');

        Swal.fire({
            title: 'Delete this category?',
            text: 'This cannot be undone.',
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
                    Swal.fire('Deleted!', response.message || 'Category deleted.', 'success');
                    $('#categoryTable').DataTable().ajax.reload(null, false);
                },
                error: function (xhr) {
                    Swal.fire('Error!', xhr.responseJSON?.message || 'Something went wrong', 'error');
                }
            });
        });
    });
});
