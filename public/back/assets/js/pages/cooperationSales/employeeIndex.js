var delete_action;
var table = $('#employee_table');
$(document).ready(function () {
    table.DataTable({
        searching: false,
        processing: true,
        language: {
            url: window.Laravel.datatable_fa
        }
    });
});

$(document).on('click', '.details-button', function () {
    // console.log('hey');
    let btn = $(this);
    $.ajax({
        url: btn.data('action'),
        type: 'GET',
        success: function (data) {
            $('#employee-detail').empty();
            $('#employee-detail').append(data);
            $('#employee-show-modal').modal();
        },
        beforeSend: function (xhr) {
            block(btn);
        },
        complete: function () {
            unblock(btn);
        }
    });
});

$(document).on('click', '.delete-button', function () {
    delete_action = $(this).attr('action');
    $('#employee-delete-modal').modal();
});
$(document).on('click', '.employee-delete-button', function () {
    // alert(delete_action);
    let btn = $(this);
    $.ajax({
        url: delete_action,
        type: 'Post',
        success: function () {
            Swal.fire({
                type: 'success',
                title: 'حذف پرسنل',
                text: 'پرسنل با موفقیت حذف گردید!',
                confirmButtonText: 'باشه'
            });
            $('#employee-delete-modal').modal('hide');
            location.reload();
        },
        beforeSend: function (xhr) {
            block(btn);
        },
        complete: function () {
            unblock(btn);
        }
    });
});

$(document).on('click', '.download-button', function () {
    // Get the URL from the data-url attribute
    var url = $(this).data('url');

    // Create a hidden link element
    var link = document.createElement('a');
    link.href = url;
    link.download = $(this).data('name') + '-QR-code.png'; // Set a default filename for download

    // Append the link to the body and programmatically click it
    document.body.appendChild(link);
    link.click();

    // Remove the link from the body
    document.body.removeChild(link);
});
