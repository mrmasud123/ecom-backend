$(function(){
    $(function () {
        let rowIndex = $('#items-body tr').length;

        function recalcRow($row) {
            const qty = parseFloat($row.find('.qty-input').val()) || 0;
            const cost = parseFloat($row.find('.cost-input').val()) || 0;
            $row.find('.line-total').text('৳' + (qty * cost).toFixed(2));
            recalcGrandTotal();
        }

        function recalcGrandTotal() {
            let total = 0;
            $('.item-row').each(function () {
                const qty = parseFloat($(this).find('.qty-input').val()) || 0;
                const cost = parseFloat($(this).find('.cost-input').val()) || 0;
                total += qty * cost;
            });
            $('#grand-total').text('৳' + total.toFixed(2));
        }

        $('#items-body').on('input', '.qty-input, .cost-input', function () {
            recalcRow($(this).closest('.item-row'));
        });

        $('#add-item-row').on('click', function () {
            $.get("/production-batches/row-template", { index: rowIndex }, function (html) {
                $('#items-body').append(html);
                rowIndex++;
            });
        });

        $('#items-body').on('click', '.remove-row-btn', function () {
            if ($('.item-row').length <= 1) {
                Swal.fire('Notice', 'A batch needs at least one product.', 'info');
                return;
            }
            $(this).closest('.item-row').remove();
            recalcGrandTotal();
        });

        $('#complete-batch-btn').on('click', function () {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Complete this batch?',
                text: 'This will add the produced quantities to stock and lock the batch from further edits.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, complete it',
            }).then((result) => {
                if (!result.isConfirmed) return;

                $.ajax({
                    url: `/production-batches/${id}/complete`,
                    method: 'PATCH',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (res) {
                        Swal.fire('Done!', res.message, 'success').then(() => location.reload());
                    },
                    error: function (xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message ?? 'Something went wrong.', 'error');
                    },
                });
            });
        });

        recalcGrandTotal();
    });
})
