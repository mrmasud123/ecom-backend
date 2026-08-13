import 'datatables.net-dt';
import Swal from 'sweetalert2';
import 'datatables.net-dt/css/dataTables.dataTables.css';


$(function () {
    const statusBadge = (status) => {
        const map = {
            draft: 'bg-warning/10 text-warning',
            completed: 'bg-success/10 text-success',
        };
        return `<span class="rounded-full px-2.5 py-1 text-xs font-medium ${map[status]}">${status}</span>`;
    };

    const table = $('#batches-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/production-batches/data",
        columns: [
            { data: 'batch_number', name: 'batch_number' },
            { data: 'warehouse_name', name: 'warehouse.name', orderable: false },
            {
                data: 'production_date',
                name: 'production_date',
                render: (d) => new Date(d).toLocaleDateString(),
            },
            { data: 'total_quantity', name: 'total_quantity', orderable: false, searchable: false },
            {
                data: 'status',
                name: 'status',
                orderable: false,
                searchable: false,
                render: statusBadge,
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                render: (id, type, row) => `
                    <div class="flex items-center justify-end gap-2">
                        <a href="/production-batches/${id}/edit" class="rounded p-1.5 text-primary hover:bg-primary/10" title="View / Edit">
                            <iconify-icon icon="lucide:${row.status === 'completed' ? 'eye' : 'pencil'}"></iconify-icon>
                        </a>
                        ${row.status === 'draft' ? `
                            <button class="delete-btn rounded p-1.5 text-danger hover:bg-danger/10" data-id="${id}" title="Delete">
                                <iconify-icon icon="lucide:trash-2"></iconify-icon>
                            </button>
                        ` : ''}
                    </div>
                `,
            },
        ],
    });

    $('#batches-table').on('click', '.delete-btn', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Delete this draft batch?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete',
            confirmButtonColor: '#d33',
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: `/production-batches/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (res) {
                    Swal.fire('Deleted!', res.message, 'success');
                    table.ajax.reload(null, false);
                },
                error: function (xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message ?? 'Something went wrong.', 'error');
                },
            });
        });
    });
});
