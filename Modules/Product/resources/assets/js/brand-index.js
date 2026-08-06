import 'datatables.net-dt';
import Swal from 'sweetalert2';
import 'datatables.net-dt/css/dataTables.dataTables.css';

$(function () {
    const table = $('#brandTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/brands/data',
        },
        columns: [
            {
                data: 'logo',
                orderable: false,
                searchable: false,
                render: (data) => data
                    ? `<img src="${data}" class="h-8 w-8 rounded-lg object-cover border border-gray-200 dark:border-gray-700" alt="logo">`
                    : `<div class="h-8 w-8 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400"><iconify-icon icon="lucide:image-off"></iconify-icon></div>`
            },
            { data: 'name', name: 'name' },
            { data: 'slug', name: 'slug' },
            { data: 'products_count', name: 'products_count', orderable: false, searchable: false, className: 'text-center' },
            {
                data: 'is_active',
                name: 'is_active',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: (data) => data
                    ? `<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400">Active</span>`
                    : `<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-500 dark:bg-gray-500/10 dark:text-gray-400">Inactive</span>`
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                className: 'text-right',
                render: (id) => `
                    <div class="flex justify-end gap-2">
                        <a href="/brands/${id}/edit" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500">
                            <iconify-icon icon="lucide:pencil"></iconify-icon>
                        </a>
                        <button data-id="${id}" class="delete-brand p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/10 text-red-500">
                            <iconify-icon icon="lucide:trash-2"></iconify-icon>
                        </button>
                    </div>
                `
            },
        ],
    });

    $('#brandTable').on('click', '.delete-brand', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Delete this brand?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            confirmButtonColor: '#dc2626',
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: `/brands/${id}`,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: () => {
                    Swal.fire('Deleted', 'Brand has been deleted.', 'success');
                    table.ajax.reload(null, false);
                },
                error: () => {
                    Swal.fire('Error', 'Something went wrong.', 'error');
                }
            });
        });
    });
});
