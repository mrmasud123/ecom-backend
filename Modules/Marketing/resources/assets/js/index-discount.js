import 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';
$(function () {
    const table = $('#discountTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/discounts/data',
            type: 'GET',
        },
        columnDefs: [
            { targets: '_all', createdCell: (td) => $(td).addClass('px-4 py-3 text-gray-700 dark:text-gray-200') },
            { targets: 4, createdCell: (td) => $(td).addClass('text-center') },
            { targets: -1, createdCell: (td) => $(td).addClass('text-right') },
        ],
        columns: [
            { data: 'campaign', name: 'name', render: (data) => data },
            { data: 'value', name: 'value', orderable: false, render: (data) => data },
            { data: 'target', name: 'target', orderable: false, searchable: false, render: (data) => data },
            { data: 'schedule', name: 'schedule', orderable: false, render: (data) => data },
            { data: 'status', name: 'status', orderable: false, searchable: false, render: (data) => data },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        rowCallback: (row) => $(row).addClass('hover:bg-gray-50 dark:hover:bg-gray-800/60 transition-colors'),
        language: { search: '', searchPlaceholder: 'Search campaigns...' },
        drawCallback: function () {
            // recompute summary stats from what's currently loaded server-side
            $.get('/discounts/stats', function (stats) {
                $('#statActive').text(stats.active);
                $('#statScheduled').text(stats.scheduled);
                $('#statExpired').text(stats.expired);
            });
        },
    });

    $('#discountTable').on('click', '.delete-discount', function () {
        const url = $(this).data('url');

        Swal.fire({
            title: 'Delete this campaign?',
            text: 'This will stop the discount immediately and cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            confirmButtonColor: '#dc2626',
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url, method: 'DELETE',
                success: (response) => {
                    Swal.fire('Deleted!', response.message, 'success');
                    table.ajax.reload(null, false);
                },
                error: (xhr) => Swal.fire('Error!', xhr.responseJSON?.message || 'Something went wrong', 'error'),
            });
        });
    });
});
