$(document).ready(function () {
    var operatorId = $('#operato-id').val();
    $('#operatorActivity').DataTable({
        searching: true,
        processing: true,
        language: {
            url: window.Laravel.datatable_fa
        },
        initComplete: function () {
            // Add start_date and end_date inputs to the search input row
            $('.dataTables_filter').append(
                '<label>فیلتر از تاریخ:<input type="text"  class="persian-date-picker start_date" placeholder="فیلتر از تاریخ"></label>'
                // '<div class="col"><input type="text" id="start_date" class="form-control persian-date-picker" placeholder="فیلتر از تاریخ..."></div>'
            );
            $('.dataTables_filter').append(
                '<label>فیلتر تا تاریخ:<input type="text"  class="persian-date-picker end_date" placeholder="فیلتر تا تاریخ"></label>'
                // '<div class="col"><input type="text" id="end_date" class="form-control persian-date-picker" placeholder="فیلتر تا تاریخ..."></div>'
            );

            // Add event listener to trigger search on date inputs
            $('.start_date, .end_date').on('change', function () {
                // Convert Persian dates to Gregorian before sending to server
                var startDate = $('.start_date').val();
                var endDate = $('.end_date').val();

                $('.en_start_date').val(startDate.toEnglishDigit());
                $('.en_end_date').val(endDate.toEnglishDigit());
                // console.log($('.en_end_date').val(), $('.en_start_date').val());

                $('#operatorActivity').DataTable().draw();
            });

            // Initialize Persian date picker
            $('.persian-date-picker').customPersianDate();
        },
        ajax: {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $('#operato-id').data('action'), // Replace with the actual URL of your data endpoint
            type: 'POST',
            data: function (d) {
                d.operator_id = operatorId;
                d.start_date = $('.en_start_date').val();
                d.end_date = $('.en_end_date').val();
            }
        },
        serverSide: true,
        columnDefs: [
            {targets: [0, 1, 2, 4], orderable: false} // Disable sorting for columns 1 and 5 (indexes start from 0)
        ],
        columns: [
            {data: 'counter', name: 'counter', title: '#'},
            {
                data: 'workdescription',
                name: 'workdescription',
                title: 'عملیات'
            },
            {
                data: 'username',
                name: 'username',
                title: 'گیرنده فعالیت',
                searchable: true
            },
            {
                data: 'formatted_date',
                name: 'created_at',
                title: 'تاریخ',
                searchable: false
            },
            {
                data: 'details_action', // Assuming 'details_action' contains button attributes
                name: 'details_action',
                title: 'جزئیات',
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        // Construct HTML for the button
                        var buttonHTML =
                            '<button data-action="' +
                            row.details_action.data_action +
                            '" data-time="' +
                            row.details_action.data_time +
                            '" data-date="' +
                            row.details_action.data_date +
                            '" class="btn btn-info btn-sm details-show waves-effect waves-light">بیشتر<i class="feather icon-info"></i></button>';
                        return buttonHTML;
                    }
                    return data;
                },
                searchable: false
            }
        ],
        select: 'single',
        order: [[3, 'desc']],
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

    $('#operator_list').DataTable({
        searching: true,
        processing: true,
        language: {
            url: window.Laravel.datatable_fa
        }
    });

    $(document).on('click', '.details-show', function () {
        let date = $(this).data('date');
        let time = $(this).data('time');
        let btn = $(this);

        $.ajax({
            url: $(this).data('action'),
            type: 'GET',
            success: function (data) {
                var activityData = data.data;
                var detailsData = data.details.data;

                $('.modal-body').html('');
                var contentDiv = $('<div></div>');
                contentDiv.append(
                    '<div class="row"><div class="col-4"><strong>عملیات:</strong></div><div class="col-8"> ' +
                        activityData.workdescription +
                        '</div></div>'
                );
                var list = $('<ul></ul>');

                // Iterate through detailsData and create list items
                for (var key in detailsData) {
                    if (detailsData.hasOwnProperty(key)) {
                        list.append(
                            '<li class="mt-1"><div class="row"><div class="col-4"><strong>' +
                                key +
                                ':</strong></div><div class="col-8"> ' +
                                detailsData[key] +
                                '</div></div></li>'
                        );
                    }
                }

                // Append the list to the content div
                contentDiv.append(list);

                // Append content for activityData

                // Append additional content to the modal body
                contentDiv.append(
                    '<p class="mt-1"><div class="row"><div class="col-4"><strong>تاریخ:</strong></div><div class="col-8"> ' +
                        date +
                        '</div></div></p>' +
                        '<p class="mt-1"><div class="row"><div class="col-4"><strong>زمان:</strong></div><div class="col-8"> ' +
                        time +
                        '</div></div></p>'
                );

                // Append the content div to the modal body
                $('.modal-body').append(contentDiv);

                $('#activity_details').modal();
            },
            beforeSend: function (xhr) {
                block(btn);
            },
            complete: function () {
                unblock(btn);
            }
        });
    });
});
