$(document).ready(function () {
    let table = $('#lottery_codes_table');
    var filter = 'all';
    $('#lottery_codes_table').DataTable({
        searching: true,
        processing: true,
        orderable: true,
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
                '<input type="button" data-value="invoice"  class="btn btn-dark code-filter text-white start_date" value="فاکتور خرید">'
                // '<div class="col"><input type="text" id="start_date" class="form-control btn btn-dark code-filter text-white" placeholder="فیلتر از تاریخ..."></div>'
            );
            $('.dataTables_filter').append(
                '<input type="button" data-value="dailyCode"  class="btn btn-dark code-filter text-white end_date" value="کد روزانه">'
                // '<div class="col"><input type="text" id="end_date" class="form-control btn btn-dark code-filter text-white" placeholder="فیلتر تا تاریخ..."></div>'
            );
            $('.dataTables_filter').append(
                '<input type="button" data-value="active"  class="btn btn-dark code-filter text-white end_date" value="فعال">'
                // '<div class="col"><input type="text" id="end_date" class="form-control btn btn-dark code-filter text-white" placeholder="فیلتر تا تاریخ..."></div>'
            );
            $('.dataTables_filter').append(
                '<input type="button" data-value="deactive"  class="btn btn-dark code-filter text-white end_date" value="باطل">'
                // '<div class="col"><input type="text" id="end_date" class="form-control btn btn-dark code-filter text-white" placeholder="فیلتر تا تاریخ..."></div>'
            );

            // Add event listener to trigger search on date inputs
            $('.code-filter').on('click', function () {
                // Convert Persian dates to Gregorian before sending to server
                // alert($(this).data('value'));

                filter = $(this).data('value');

                // alert(codes);
                $('#lottery_codes_table').DataTable().draw();
            });
        },
        ajax: {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: table.data('action'), // Replace with the actual URL of your data endpoint
            type: 'GET', // Specify GET method for the Ajax request
            data: function (d) {
                d.filter = filter;
            }
        },
        serverSide: true,
        columnDefs: [
            {targets: [0, 6], orderable: false} // Disable sorting for the first and last column
        ],
        columns: [
            {data: 'counter', name: 'counter', title: '#'},
            {data: 'user_id', name: 'user_id', title: 'کاربر'},
            {
                data: 'code',
                name: 'code',
                title: 'کد قرعه کشی',
                searchable: true
            },
            {
                data: 'source',
                name: 'source',
                title: 'منبع کد',
                render: function (data) {
                    if (data == 'invoice') {
                        return '<span class="badge badge-success">فاکتور خرید</span>';
                    } else {
                        return '<span class="badge badge-info">کد روزانه</span>';
                    }
                },
                searchable: true
            },
            {
                data: 'state',
                name: 'state',
                title: 'وضعیت',
                render: function (data) {
                    return data == 'active'
                        ? '<span class="badge badge-success">فعال</span>'
                        : '<span class="badge badge-danger">باطل</span>';
                },
                searchable: true
            },
            {data: 'date', name: 'date', title: 'تاریخ', orderable: true},
            {
                data: 'button',
                name: 'button',
                title: 'عملیات',
                render: function (data) {
                    if (data.state) {
                        return (
                            '<input type="button" data-id="' +
                            data.id +
                            '" value="بیشتر" class="lotteryCodeButton btn btn-info btn-sm" />'
                        );
                    } else {
                        return '';
                    }
                }
            }
        ],
        select: 'single',
        drawCallback: function (settings) {
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
$(document).on('click', '.lotteryCodeButton', function () {
    let btn = $(this);
    $('.lottery_code_id').val(btn.data('id'));
    $('#lottery_code_more_modal').modal();
});

$(document).on('click', '#lottery-winner-code-button', function () {
    var button = $(this); // Store a reference to the button

    var formData = $('#lottery_winner_form').serialize();

    // Include additional data along with the form data
    var requestData = formData + '&id=' + $('.lottery_code_id').val();
    $.ajax({
        url: $('#lottery_winner_form').attr('action'),
        type: 'post',
        data: requestData,
        success: function (response) {
            $('#lottery_code_more_modal').modal('hide');
            if (response.status == 'success') {
                toastr.success(response.message);
                $('#lottery_codes_table').DataTable().ajax.reload();
            } else {
                toastr.error(response.message);
            }
        },
        beforeSend: function () {
            block(button); // Use the stored reference to the button
        },
        complete: function () {
            unblock(button); // Use the stored reference to the button
        }
    });
});
$(document).on('click', '#lottery-code-state-button', function () {
    var button = $(this); // Store a reference to the button
    var formData = $('#lotteryStateCodeForm').serialize();
    // Include additional data along with the form data
    var requestData = formData + '&id=' + $('.lottery_code_id').val();
    $.ajax({
        url: $('#lotteryStateCodeForm').attr('action'),
        type: 'post',
        data: requestData,
        success: function (response) {
            $('#lottery_code_more_modal').modal('hide');
            if (response.status == 'success') {
                toastr.success(response.message);
                $('#lottery_codes_table').DataTable().ajax.reload();
            } else {
                toastr.error(response.message);
            }
        },
        beforeSend: function () {
            block(button); // Use the stored reference to the button
        },
        complete: function () {
            unblock(button); // Use the stored reference to the button
        }
    });
});
