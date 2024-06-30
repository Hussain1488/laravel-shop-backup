$(document).ready(function () {
    $('#sms-log-table').DataTable({
        searching: true,
        processing: true,
        language: {
            url: window.Laravel.datatable_fa
        },
        ajax: {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: $('#sms-log-table').data('href')
        },
        serverSide: true,
        columnDefs: [
            {targets: [0, 4], orderable: false} // Disable sorting for columns 1 and 5 (indexes start from 0)
        ],
        columns: [
            {data: 'counter', name: 'counter', title: '#'},
            {
                data: 'mobile',
                name: 'mobile',
                title: 'شماره'
            },
            {data: 'type', name: 'type', title: 'نوع'},
            {data: 'time', name: 'time', title: 'زمان'},
            {data: 'action', name: 'action', title: 'عملیات'}
        ],
        select: 'single',
        drawCallback: function (settings) {
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
            // this.api().processing(false);
        }
    });
});
$(document).on('click', '.show-sms', function () {
    var btn = $(this);

    $.ajax({
        url: $(this).data('action'),
        type: 'GET',
        success: function (data) {
            $('#sms-detail').empty();
            $('#sms-detail').append(data);
            $('#show-modal').modal('show');
        },
        beforeSend: function (xhr) {
            block(btn);
        },
        complete: function () {
            unblock(btn);
        }
    });
});
