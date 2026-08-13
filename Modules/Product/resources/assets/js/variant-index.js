import 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';
$(function () {
    const table = $('#variantTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/product-variants/data',
            type: 'GET',
            data: function (d) {
                d.stock_status = $('#stockFilter').val();
            },
        },
        columnDefs: [
            { targets: '_all', createdCell: (td) => $(td).addClass('px-4 py-3 text-gray-700 dark:text-gray-200') },
            { targets: 5, createdCell: (td) => $(td).addClass('text-center') },
            { targets: -1, createdCell: (td) => $(td).addClass('text-right') },
        ],
        columns: [
            { data: 'variant', name: 'product.name', render: (data) => data },
            { data: 'sku', name: 'sku', render: (data) => data },
            { data: 'combination', name: 'combination', orderable: false, searchable: false, render: (data) => data },
            { data: 'price', name: 'price', render: (data) => data },
            { data: 'stock', name: 'quantity', render: (data) => data },
            { data: 'status', name: 'is_active', orderable: false, searchable: false, render: (data) => data },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        rowCallback: (row) => $(row).addClass('hover:bg-gray-50 dark:hover:bg-gray-800/60 transition-colors'),
        language: { search: '', searchPlaceholder: 'Search by SKU or product...' },
        drawCallback: loadStats,
    });

    $('#stockFilter').on('change', () => table.ajax.reload());

    function loadStats() {
        $.get('/product-variants/stats', function (stats) {
            $('#statTotal').text(stats.total);
            $('#statActive').text(stats.active);
            $('#statLowStock').text(stats.low_stock);
            $('#statOutOfStock').text(stats.out_of_stock);
        });
    }
});
