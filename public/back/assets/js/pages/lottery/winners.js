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
                '<input type="button" data-value="weekly"  class="btn btn-dark code-filter text-white start_date" value="هفته ای">'
                // '<div class="col"><input type="text" id="start_date" class="form-control btn btn-dark code-filter text-white" placeholder="فیلتر از تاریخ..."></div>'
            );
            $('.dataTables_filter').append(
                '<input type="button" data-value="monthly"  class="btn btn-dark code-filter text-white end_date" value="ماهانه">'
                // '<div class="col"><input type="text" id="end_date" class="form-control btn btn-dark code-filter text-white" placeholder="فیلتر تا تاریخ..."></div>'
            );
            $('.dataTables_filter').append(
                '<input type="button" data-value="yearly"  class="btn btn-dark code-filter text-white end_date" value="سالانه">'
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
            {targets: [0, 5], orderable: false} // Disable sorting for the first and last column
        ],
        columns: [
            {data: 'counter', name: 'counter', title: '#'},
            {
                data: 'user_id',
                name: 'user_id',
                title: 'کاربر'
            },
            {
                data: 'lottery_code',
                name: 'lottery_code',
                title: 'کد قرعه کشی'
            },
            {
                data: 'type',
                name: 'type',
                title: 'نوع قرعه کشی',
                render: function (data) {
                    if (data == 'weekly') {
                        return 'هفته ای';
                    } else if (data == 'monthly') {
                        return 'ماهانه';
                    } else {
                        return 'سالانه';
                    }
                }
            },
            {
                data: 'description',
                name: 'description',
                title: 'توضیحات'
            },
            {
                data: 'lottery_date',
                name: 'lottery_date',
                title: 'تاریخ قرعه کشی'
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
