$(document).ready(function () {
    let table = $('#daily_code_table');
    var codes = 'monthly';
    var codeBtn = null;

    // console.log(table.attr('action'));
    $('#daily_code_table').DataTable({
        searching: true,
        processing: true,
        language: {
            url: window.Laravel.datatable_fa
        },
        initComplete: function () {
            // Add start_date and end_date inputs to the search input row
            $('.dataTables_filter').append(
                '<input type="button" data-value="today"  class="btn btn-dark code-filter text-white end_date" value="امروز">'
                // '<div class="col"><input type="text" id="end_date" class="form-control btn btn-dark code-filter text-white" placeholder="فیلتر تا تاریخ..."></div>'
            );
            $('.dataTables_filter').append(
                '<input type="button" data-value="weekly"  class="btn btn-dark code-filter text-white start_date" value="این هفته">'
                // '<div class="col"><input type="text" id="start_date" class="form-control btn btn-dark code-filter text-white" placeholder="فیلتر از تاریخ..."></div>'
            );
            $('.dataTables_filter').append(
                '<input type="button" data-value="monthly"  class="btn btn-dark code-filter text-white end_date" value="این ماه">'
                // '<div class="col"><input type="text" id="end_date" class="form-control btn btn-dark code-filter text-white" placeholder="فیلتر تا تاریخ..."></div>'
            );
            $('.dataTables_filter').append(
                '<input type="button" data-value="all"  class="btn btn-dark code-filter text-white end_date" value="همه">'
                // '<div class="col"><input type="text" id="end_date" class="form-control btn btn-dark code-filter text-white" placeholder="فیلتر تا تاریخ..."></div>'
            );

            // Add event listener to trigger search on date inputs
            $('.code-filter').on('click', function () {
                // Convert Persian dates to Gregorian before sending to server
                // alert($(this).data('value'));

                codes = $(this).data('value');

                // alert(codes);
                $('#daily_code_table').DataTable().draw();
            });
        },
        ajax: {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: table.attr('action'), // Replace with the actual URL of your data endpoint
            type: 'POST',
            data: function (d) {
                d.filter = codes;
            }
        },
        serverSide: true,

        columns: [
            {data: 'counter', name: 'counter', title: '#'},
            {
                data: 'date',
                name: 'date',
                title: 'تاریخ',
                render: function (data, type, row) {
                    if (data.is_today) {
                        return (
                            '<span class="badge badge-success">' +
                            data.date +
                            '</span>'
                        );
                    } else {
                        return (
                            '<span class="badge badge-primary">' +
                            data.date +
                            '</span>'
                        );
                    }
                }
            },
            {
                data: 'insta',
                name: 'insta',
                title: 'اینستاگرام',
                render: function (data, type, row) {
                    if (row.date.is_today) {
                        return (
                            '<span class="badge badge-success">' +
                            data +
                            '</span>'
                        );
                    } else {
                        return data;
                    }
                },
                searchable: true
            },
            {
                data: 'rubika',
                name: 'rubika',
                title: 'روبیکا',
                render: function (data, type, row) {
                    if (row.date.is_today) {
                        return (
                            '<span class="badge badge-success">' +
                            data +
                            '</span>'
                        );
                    } else {
                        return data;
                    }
                },
                searchable: true
            },
            {
                data: 'eitaa',
                name: 'eitaa',
                title: 'ایتا',
                render: function (data, type, row) {
                    if (row.date.is_today) {
                        return (
                            '<span class="badge badge-success">' +
                            data +
                            '</span>'
                        );
                    } else {
                        return data;
                    }
                },
                searchable: true
            },
            {
                data: 'site',
                name: 'site',
                title: 'سایت',
                render: function (data, type, row) {
                    if (row.date.is_today) {
                        return (
                            '<span class="badge badge-success">' +
                            data +
                            '</span>'
                        );
                    } else {
                        return data;
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

$('#daily_code_gerator_button').on('click', function () {
    let lastDay = $('.lastValue').val();
    let today = new Date();
    let lastDayDate = new Date(lastDay);
    lastDayDate.setHours(0, 0, 0, 0);
    today.setMonth(today.getMonth() + 1);
    today.setHours(0, 0, 0, 0);
    // console.log(lastDayDate, today);
    if (lastDayDate.getTime() > today.getTime()) {
        toastr.warning(
            'امروز نمیتوانید برای تولید کد روزانه قرعه کشی درخواست دهید. لطفا بعدا دوباره امتهان کنید!'
        );
    } else {
        let button = $(this); // Store reference to the button
        $.ajax({
            url: button.attr('action'), // Use stored reference
            type: 'GET',
            success: function (response) {
                button.prop('disabled', true);
                toastr.success('تولید کد روزانه برای یک ماه ایجاد شد!');
                $('#daily_code_table').DataTable().draw();
            },
            error: function (xhr, status, error) {},
            beforeSend: function (xhr) {
                block(button);
            },
            complete: function () {
                unblock(button);
            }
        });
    }
    condition = false;
});

$('#dailyCodeExport').on('click', function () {
    let button = $(this);
    window.open(button.attr('action'));
    // $.ajax({
    //     url: button.attr('action'),
    //     type: 'GET',
    //     success: function () {
    //         window.location.href = button.attr('action');
    //     }
    // });
});
