$(document).ready(function () {
    let table = $('#invoice_code_table');
    var filter = 'all';
    table.DataTable({
        searching: true,
        processing: true,
        language: {
            url: window.Laravel.datatable_fa
        },
        initComplete: function () {
            // Add start_date and end_date inputs to the search input row
            $('.dataTables_filter').append(
                '<input type="button" data-value="all"  class="btn btn-dark code-filter text-white end_date" value="همه">'
                // '<div class="col"><input type="text" id="end_date" class="form-control btn btn-dark code-filter text-white" placeholder="فیلتر تا تاریخ..."></div>'
            );
            $('.dataTables_filter').append(
                '<input type="button" data-value="valid"  class="btn btn-dark code-filter text-white end_date" value="تأیید شده">'
                // '<div class="col"><input type="text" id="end_date" class="form-control btn btn-dark code-filter text-white" placeholder="فیلتر تا تاریخ..."></div>'
            );
            $('.dataTables_filter').append(
                '<input type="button" data-value="not-valid"  class="btn btn-dark code-filter text-white start_date" value="رد شده">'
                // '<div class="col"><input type="text" id="start_date" class="form-control btn btn-dark code-filter text-white" placeholder="فیلتر از تاریخ..."></div>'
            );
            $('.dataTables_filter').append(
                '<input type="button" data-value="pending"  class="btn btn-dark code-filter text-white end_date" value="انتظار تأیید">'
                // '<div class="col"><input type="text" id="end_date" class="form-control btn btn-dark code-filter text-white" placeholder="فیلتر تا تاریخ..."></div>'
            );

            // Add event listener to trigger search on date inputs
            $('.code-filter').on('click', function () {
                filter = $(this).data('value');
                table.DataTable().draw(); // Redraw the DataTable with the new filter
            });
        },
        ajax: {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: table.attr('action'), // Replace with the actual URL of your data endpoint
            type: 'POST',
            data: function (d) {
                d.filter = filter;
            }
        },
        serverSide: true,

        columnDefs: [
            {targets: [0, 1, 2, 3, 4, 5], orderable: false} // Disable sorting for columns 1 and 5 (indexes start from 0)
        ],
        columns: [
            {data: 'counter', name: 'counter', title: '#'},
            {
                data: 'date',
                name: 'date',
                title: 'تاریخ'
            },
            {
                data: 'number',
                name: 'number',
                title: 'شماره فاکتور',

                searchable: false
            },
            {
                data: 'amount',
                name: 'amount',
                title: 'مبلغ خرید',
                searchable: true
            },
            {
                data: 'image',
                name: 'image',
                title: 'عکس فاکتور',
                render: function (data, row) {
                    return (
                        '<input type="button" class="btn btn-info btn-sm invoiceImageShow" data-src="' +
                        data +
                        '" value="نمایش" id="">'
                    );
                },
                searchable: false
            },
            {
                data: 'state',
                name: 'state',
                title: 'وضعیت',
                render: function (data, row, type) {
                    if (data == 'pending') {
                        return '<span class="badge badge-primary">انتظار تأیید</span>';
                    } else if (data == 'valid') {
                        return '<span class="badge badge-success">تأیید شده</span>';
                    } else {
                        return '<span class="badge badge-danger">رد شده</span>';
                    }
                },
                searchable: false
            },
            {
                data: 'action',
                name: 'action',
                title: 'عملیات',
                render: function (data, type, row) {
                    if (row.state != 'valid') {
                        return (
                            '<input type="button" data-id="' +
                            data +
                            '" data-amount="' +
                            row.amount +
                            '" data-factor="' +
                            row.number +
                            '" data-image="' +
                            row.image +
                            '" value="بیشتر"' +
                            ' class="invoiceActionButton btn btn-info btn-sm" />'
                        );
                    } else {
                        // Handle the case where the state is 'valid'
                        return ''; // Return an empty string or null
                    }
                },
                searchable: true
            }
        ],
        select: 'single',
        drawCallback: function (settings) {
            // console.log('Draw callback executed');
            // Reset the counter on each page change
            var api = this.api();
            var rows = api.rows({page: 'current'}).nodes();
            var last = null;
            var counter = api.page.info().start + 1; // Start counter from the correct number

            api.column(0, {page: 'current'})
                .data()
                .each(function (group, i) {
                    if (last !== group) {
                        counter = api.page.info().start + 1;
                    }

                    $(rows).eq(i).find('td:eq(0)').html(counter);
                    counter++;

                    last = group;
                });
        }
    });
});

function addCommas(nStr) {
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

$(document).on('click', '.invoiceActionButton', function () {
    let btn = $(this);
    $('.invoiceImage').attr('src', btn.data('image'));
    $('.invoice-price').text(btn.data('amount'));
    $('.invoice-code-number').text(btn.data('factor'));
    $('#selectedInvoice').val(btn.data('id'));
    // moneyInputSpan();
    $('#validationChicking').modal();
});

$('.validationValidateButton').on('click', function () {
    $('#validationChicking').modal('hide');
    $('#validationValidateModal').modal();
});

$(document).on('click', '.invoiceRejectButton', function () {
    let btn = $('.invoiceRejectButton');
    $.ajax({
        url: rejectionUrl + '/' + $('#selectedInvoice').val(),
        type: 'get',

        success: function (response) {
            if (response.status == 'success') {
                // $('#validationValidateModal').modal('hide');
                toastr.success(response.data);
                $('#invoice_code_table').DataTable().ajax.reload();
            } else {
                // $('#validationValidateModal').modal('hide');
                toastr.error(response.data);
            }
            // Handle successful response
        },
        beforeSend: function (xhr) {
            block(btn);
        },
        complete: function () {
            $('#validationChicking').modal('hide');
            unblock(btn);
        }
    });
});
$('#invoiceValidationButton').on('click', function () {
    let btn = $(this);
    let formData = $('#invoiceValidationForm').serialize();
    // console.log($('#selectedInvoice').val());
    $.ajax({
        url: validationUrl + '/' + $('#selectedInvoice').val(),
        type: 'post',
        data: formData,
        success: function (response) {
            console.log(response);
            if (response.status == 'success') {
                toastr.success(response.data);
                $('#invoice_code_table').DataTable().ajax.reload();
            } else {
                toastr.error(response.data);
            }
        },
        beforeSend: function (xhr) {
            block(btn);
        },
        complete: function () {
            $('#validationValidateModal').modal('hide');
            unblock(btn);
        }
    });
});

$(document).on('click', '.invoiceImageShow', function () {
    console.log($(this).attr('src'));
    $('#invoiceImage').attr('src', $(this).data('src'));
    $('.image_modal').modal();
});
