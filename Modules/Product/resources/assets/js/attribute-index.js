// Modules/Product/resources/assets/js/attribute-index.js
import 'datatables.net-dt';
import Swal from 'sweetalert2';

$(function () {
    const dataUrl = $('#attributeTable').data('url') || '/attributes/data';

    const table = $('#attributeTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dataUrl,
        },
        columns: [
            { data: 'name', name: 'name' },
            { data: 'slug', name: 'slug' },
            {
                data: 'type',
                name: 'type',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: (data) => {
                    const labels = {
                        select: 'Select',
                        color: 'Color',
                        text: 'Text',
                        radio: 'Radio',
                    };
                    return `<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">${labels[data] || data}</span>`;
                }
            },
            {
                data: 'values_count',
                name: 'values_count',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: (count) => `<span class="text-gray-600 dark:text-gray-400">${count}</span>`
            },
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
                        <a href="/attributes/${id}/edit" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500">
                            <iconify-icon icon="lucide:pencil"></iconify-icon>
                        </a>
                        <button data-id="${id}" class="delete-attribute p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/10 text-red-500">
                            <iconify-icon icon="lucide:trash-2"></iconify-icon>
                        </button>
                    </div>
                `
            },
        ],
    });

    $('#attributeTable').on('click', '.delete-attribute', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Delete this attribute?',
            text: 'Deleting this will also remove its values. This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            confirmButtonColor: '#dc2626',
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: `/attributes/${id}`,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: () => {
                    Swal.fire('Deleted', 'Attribute has been deleted.', 'success');
                    table.ajax.reload(null, false);
                },
                error: (xhr) => {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong.', 'error');
                }
            });
        });
    });
});
