$(document).ready(function () {
    var bankId = $('#bank_data');
    $('#bank_transaction_list').DataTable({
        searching: true,
        processing: true,
        language: {
            url: window.Laravel.datatable_fa
        },

        initComplete: function () {
            // Add start_date and end_date inputs to the search input row
            $('.dataTables_filter').append(
                '<label>فیلتر از تاریخ:<input type="text" id="start_date" class="persian-date-picker" placeholder="فیلتر از تاریخ"></label>'
                // '<div class="col"><input type="text" id="start_date" class="form-control persian-date-picker" placeholder="فیلتر از تاریخ..."></div>'
            );
            $('.dataTables_filter').append(
                '<label>فیلتر تا تاریخ:<input type="text" id="end_date" class="persian-date-picker" placeholder="فیلتر تا تاریخ"></label>'
                // '<div class="col"><input type="text" id="end_date" class="form-control persian-date-picker" placeholder="فیلتر تا تاریخ..."></div>'
            );

            // Add event listener to trigger search on date inputs
            $('#start_date, #end_date').on('change', function () {
                // Convert Persian dates to Gregorian before sending to server
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();

                $('#en_start_date').val(startDate.toEnglishDigit());
                $('#en_end_date').val(endDate.toEnglishDigit());

                $('#bank_transaction_list').DataTable().draw();
            });

            // Initialize Persian date picker
            $('.persian-date-picker').customPersianDate();
        },
        ajax: {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: bankId.data('action'), // Replace with the actual URL of your data endpoint
            type: 'POST',
            data: function (d) {
                d.bankId = bankId.val();
                d.start_date = $('#en_start_date').val();
                d.end_date = $('#en_end_date').val();
            }
        },

        serverSide: true,
        columnDefs: [
            {targets: [0, 1, 2, 3, 4, 5], orderable: false} // Disable sorting for columns 1 and 5 (indexes start from 0)
        ],
        columns: [
            {data: 'counter', name: 'counter', title: '#'},
            {
                data: 'user',
                name: 'user',
                title: 'کاربر'
            },
            {
                data: 'source',
                name: 'source',
                title: 'منبع',
                searchable: false
            },
            {
                data: 'username',
                name: 'username',
                title: 'شماره تماس',
                searchable: true
            },
            {
                data: 'transactionprice',
                name: 'transactionprice',
                title: 'مبلغ تراکنش(ریال)',
                render: function (data) {
                    return (
                        '<span class="monyInputSpan">' +
                        parseFloat(data).toLocaleString() +
                        '</span>' +
                        ' ریال'
                    );
                },
                searchable: false
            },
            {
                data: 'status',
                name: 'status',
                title: 'نوع تراکنش',
                render: function (data) {
                    if (data == 'deposit') {
                        return (
                            '<span class="text-success">' + 'افزایش' + '</span>'
                        );
                    } else {
                        return (
                            '<span class="text-danger">' + 'کاهش' + '</span>'
                        );
                    }
                },
                searchable: false
            },
            {
                data: 'bankbalance',
                name: 'bankbalance',
                title: 'موجودی حساب(ریال)',
                render: function (data) {
                    return (
                        '<span class="monyInputSpan">' +
                        parseFloat(data).toLocaleString() +
                        '</span>' +
                        ' ریال'
                    );
                },
                searchable: false
            },
            {
                data: 'transaction_date',
                name: 'transaction_date',
                title: 'تاریخ',
                render: function (data, type, row, meta) {
                    // Assuming 'date' and 'time' are properties of the 'transaction_date' object
                    var html_show =
                        '<span>' +
                        data.date +
                        '</span>' +
                        '<br />' +
                        '<span>' +
                        data.time +
                        '</span>';
                    return html_show;
                },
                searchable: false
            },
            {
                data: 'transaction_details',
                name: 'transaction_details',
                title: 'جزئیات',
                render: function (data) {
                    // Assuming 'date' and 'time' are properties of the 'transaction_date' object
                    var html_show =
                        '<button data-id="' +
                        data +
                        '" class="btn btn-info btn-sm details-show transaction_details">بیشتر<i class="feather icon-info"></i></button>';
                    return html_show;
                },
                searchable: false
            }
        ],
        // order: [[6, 'desc']],
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

    $(document).on('click', '.transaction_details', function () {
        let btn = $(this);
        let url = $('#bank_data').data('details-action');
        let id = btn.data('id');
        let full_url = url.replace(':id', id);
        $.ajax({
            url: full_url,
            type: 'GET',
            success: function (data) {
                var transDetails = data.data;
                var type = data.type;
                // console.log(data.data, data.type);
                if (type == 'buyer') {
                    $('.modal-title').text(
                        'جزئیات تراکنش: ' + transDetails['شماره تماس کاربر']
                    );
                } else if (type == 'store') {
                    $('.modal-title').text(
                        'جزئیات تراکنش فروشگاه: ' + transDetails['اسم فروشگاه']
                    );
                } else {
                    $('.modal-title').text(
                        'جزئیات تراکنش حساب داخلی: ' + transDetails['اسم حساب']
                    );
                }
                $('.modal-body').html('');

                var contentDiv = $('<div></div>');
                var list = $('<ul></ul>');

                for (var key in transDetails) {
                    if (key === 'سند') {
                        list.append(
                            '<li class="mt-1"><div class="row"><div class="col-4"><strong>' +
                                key +
                                ':</strong></div><div class="col-8 img">'
                        );

                        // Assuming transDetails[key] is an array or object
                        $.each(transDetails[key], function (index, value) {
                            // Append each image and download link to the 'col-8' div
                            list.find('.img').append(
                                '<div style="position: relative; display: inline-block; margin: 5px;">' +
                                    '<a style="position: absolute; top: 5%;' +
                                    ' right: 5%; display: block; padding: 5px;' +
                                    '  text-decoration: none; border-radius: 5px;"' +
                                    'class="bg-color-primary text-success"' +
                                    'href="' +
                                    value +
                                    '" download="' +
                                    value +
                                    '">' +
                                    '<span class="badge badge-success badge-pill">' +
                                    '<i class="feather icon-download"></i></span>' +
                                    '</a>' +
                                    '<img style="width:70px; display:inline;margin:2px;"' +
                                    'src="' +
                                    value +
                                    '"/>' +
                                    '</div>'
                            );
                        });
                        // Close the remaining tags after the loop
                        list.append('</div></div></li>');
                    } else if (transDetails.hasOwnProperty(key)) {
                        list.append(
                            '<li class="mt-1"><div class="row"><div class="col-4"><strong>' +
                                key +
                                ':</strong></div><div class="col-8"> ' +
                                transDetails[key] +
                                '</div></div></li>'
                        );
                    }
                }
                contentDiv.append(list);
                // Append the content div to the modal body
                $('.modal-body').append(contentDiv);

                $('#bank_transaction_details').modal();
            },
            beforeSend: function (xhr) {
                block(btn);
            },
            complete: function () {
                unblock(btn);
            },
            error: function () {
                toastr.error('خطا در دریاف اطلاعات');
            }
        });
    });

    function formatMoney(value) {
        return numeral(value).format('0,0');
    }

    $(function () {
        let a = $('#total_transaction').val();
        let sign = '';
        if (a < 0) {
            sign = '-';
        } else {
            sign = '';
        }

        let b = addCommas(a.replace(/\D/g, ''));
        $('#total_transaction').val(sign + b);
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
});
