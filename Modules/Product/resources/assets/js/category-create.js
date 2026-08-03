import 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';

$(function () {
    let slugEdited = false;
    $('#slug').on('input', () => slugEdited = true);
    $('#name').on('input', function () {
        if (slugEdited) return;
        $('#slug').val(
            $(this).val().toLowerCase().trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
        );
    });

    $('#createCategoryForm').on('submit', function (e) {
        e.preventDefault();
        const form = this;

        Swal.fire({ title: 'Saving category...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        $.ajax({
            url: $(form).attr('action'),
            method: 'POST',
            data: $(form).serialize(),
            beforeSend: () => $(form).find('button[type="submit"]').prop('disabled', true),
            complete: () => $(form).find('button[type="submit"]').prop('disabled', false),
            success: function (response) {
                Swal.close();
                Swal.fire({ title: 'Success!', text: response.message, icon: 'success' })
                    .then(() => window.location.href = "/categories");
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
