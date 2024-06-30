$(document).ready(function () {
    // $('.transaction_date').pDatePicker();

    $('.payDetailsButton').on('click', function () {
        let btn = $(this);
        var url = btn.data('href');

        $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {
                var detailsData = data.data;
                var doc = data.doc;
                let id = data.id;

                // console.log(data);
                // Clear previous content
                $('.modal-body').html('');
                // Create a list in modal body
                var contentDiv = $('<div></div>');

                // Create a list in modal body
                var list = $('<ul></ul>');

                // Iterate through detailsData and create list items
                for (var key in detailsData) {
                    if (detailsData.hasOwnProperty(key)) {
                        list.append(
                            '<li class="mt-1"><div class="row"><div class="col-5"><strong>' +
                                key +
                                '</strong></div><div class="col-7"> ' +
                                detailsData[key] +
                                '</div></div></li>'
                        );
                    }
                }

                // Append the list to the content div
                contentDiv.append(list);
                let docAddress =
                    '<ul><li class="mt-1"><div class="row"><div class="col-5"><strong>' +
                    'اسناد تراکنش' +
                    '</strong></div><div class="col-7"> ' +
                    '<a href="' +
                    doc +
                    '" class="btn btn-success ml-1 btn-sm"' +
                    ' download ><i class="feather icon-download">سند پرداخت </i></a>' +
                    '<a href="reqDocDownload/' +
                    id +
                    '" class="btn btn-success btn-sm"' +
                    ' download ><i class="feather icon-download">اسناد درخواست </i></a>' +
                    '</div></div></li></ul>';
                contentDiv.append(docAddress);

                // Append the content div to the modal body
                $('.modal-body').append(contentDiv);
                $('.modal-body').append('</ul>');
                // Append the list to the modal body
                // $('.modal-body').append(list);
                // Show the modal
                $('#payListDetails').modal('show');
            },
            beforeSend: function (xhr) {
                block(btn);
            },
            complete: function () {
                unblock(btn);
            }
        });
    });

    function loadDataBasedOnScreenSize() {
        // Get the screen width
        var screenWidth = $(window).width();

        // Check if it's a mobile screen (you can adjust the breakpoint as needed)
        if (screenWidth < 768) {
            // Load mobile-size data
            loadMobileSizeData();
        } else {
            // Load pc-size data
            loadPcSizeData();
        }
    }

    // Function to load data for pc-size
    function loadPcSizeData() {
        // Add your logic to load data for pc-size
        console.log('Loading PC-size data');
        // For example, you can make an AJAX request to fetch data
    }

    // Function to load data for mobile-size
    function loadMobileSizeData() {
        // Add your logic to load data for mobile-size
        console.log('Loading Mobile-size data');
        // For example, you can make an AJAX request to fetch data
    }

    // Call the function when the page loads and on window resize
    loadDataBasedOnScreenSize();
    $(window).resize(loadDataBasedOnScreenSize);

    $('.monyInputSpan').each(function () {
        var input = $(this).text();
        var digits = input.replace(/\D/g, '');
        var formattedNumber = addCommas(digits);
        $(this).text(formattedNumber);
    });
    $('.moneyInput').each(function () {
        var input = $(this).val();

        // Remove any non-digit characters (e.g., commas)
        var digits = input.replace(/\D/g, '');

        // Format the number with commas
        var formattedNumber = addCommas(digits);

        // Update the input field with the formatted number
        $(this).val(formattedNumber);
    });

    // Function to add commas as a thousands separator

    $('#creating_bank_button').on('click', function () {
        // console.log('hey');
        let Prefix = $('#Acount_number_prefix').val();
        let main_account_number = $('#main_account_number').val();
        $('#main_account_number').val(Prefix + main_account_number);
        let account_type = $('#Account_type option:selected');
        let account_gateway = $('#account_gatewy option:selected').val();
        if (account_type.data('code') == 21 && account_gateway == '') {
            toastr.warning('انتخاب درگاه پرداخت برای ماهیت بانک الزامی است!');
        } else {
            $('#create_bank_form').submit();
        }
    });
    accountType();
    $('.imageInput').on('change', function (e) {
        // Get the selected files
        var files = e.target.files;

        if (files && files.length > 0) {
            // Clear existing images
            $('.imgContainer').empty();

            // Loop through each selected file
            for (var i = 0; i < files.length; i++) {
                // Check if the file is an image
                if (files[i].type.startsWith('image/')) {
                    // If it's an image, create a FileReader to display the image
                    var reader = new FileReader();

                    // Set the callback function to display the image after reading
                    reader.onload = function (event) {
                        // Create an image element
                        var image = $('<img>')
                            .attr('src', event.target.result)
                            .css({
                                'max-width': '100px',
                                border: '2px solid #ccc',
                                margin: '5px',
                                'box-shadow': '0 0 5px rgba(0, 0, 0, 0.3)',
                                display: 'inline'
                            });

                        // Append the image to the container
                        $('.imgContainer').append(image);
                    };

                    reader.readAsDataURL(files[i]);
                } else {
                    // If it's not an image, just display the file name
                    var fileName = $('<span>').text(files[i].name).css({
                        border: '2px solid #ccc',
                        padding: '5px',
                        margin: '5px',
                        'box-shadow': '0 0 5px rgba(0, 0, 0, 0.3)',
                        display: 'inline-block'
                    });

                    // Append the file name to the container
                    $('.imgContainer').append(fileName);
                }
            }
        }
    });
});

$('.pay_button').on('click', function () {
    let dataId = $(this).data('id');
    let amount = String($(this).data('amount')).replace(/\D/g, '');
    let final = addCommas(amount);
    $('#deposit_amount_show').text(final);
    $('#pay_list_id').val(dataId);
    $('#myModal').modal();
});

$('#Account_type').on('change', function () {
    accountType();
});

function accountType() {
    let account_type = $('#Account_type option:selected');
    // console.log(account_type);
    if (account_type.data('code') == 21) {
        $('.gateway_input').show(500);
    } else {
        $('.gateway_input').hide(500);
    }
    $('#Acount_number_prefix').val(account_type.data('code'));
}

$('#submit_form_pay').on('click', function () {
    $('#payment_form').attr('action', pay_url);
    $('#payment_form').submit();
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
$('.account_selection').change(function () {
    let selectedOption = $(this).find(':selected');
    let name = selectedOption.attr('attr-name');
    $('.account-title').text('حساب:' + ' ');
    $('.account-name').text(name);
});

$('.transaction_details').on('click', function () {
    let btn = $(this);
    $.ajax({
        url: btn.data('action'),
        type: 'GET',
        success: function (data) {
            var detailsData = data;
            $('.modal-body').empty();
            var contentDiv = $('<div></div>');
            var $ul = $('<ul>'); // Create a ul element
            for (var key in detailsData) {
                if (key === 'سند پرداخت') {
                    $ul.append(
                        '<li class="mt-1"><div class="row"><div class="col-4"><strong>' +
                            key +
                            ':</strong></div><div class="col-8 img">'
                    );

                    // Assuming transDetails[key] is an array or object
                    $.each(detailsData[key], function (index, value) {
                        console.log(detailsData[key]);
                        // Append each image and download link to the 'col-8' div
                        if (value && /\.(jpg|jpeg|png|gif)$/i.test(value)) {
                            // Append each image and download link to the 'col-8' div
                            $ul.find('.img').append(
                                '<div style="display: inline-block; margin: 5px;position: relative;">' +
                                    '<a style=" position: absolute; display: block; padding: 5px; text-decoration: none; border-radius: 5px;"' +
                                    'class="bg-color-primary text-success"' +
                                    'href="' +
                                    value +
                                    '" download="' +
                                    value +
                                    '">' +
                                    '<span class="badge badge-success badge-pill">' +
                                    '<i class="feather icon-download"></i></span>' +
                                    '</a>' +
                                    '<img style="width:50px; margin: 2px; display: inline-block;"' +
                                    'src="' +
                                    value +
                                    '"/>' +
                                    '</div>'
                            );
                        } else {
                            // Append download button for other file types
                            $ul.find('.img').append(
                                '<div style="display: inline-block; margin: 5px;">' +
                                    '<a style="position: relative; display: block; padding: 5px; text-decoration: none; border-radius: 5px;"' +
                                    'class="bg-color-primary text-success"' +
                                    'href="' +
                                    value +
                                    '" download="' +
                                    value +
                                    '">' +
                                    '<span class="badge badge-success badge-pill">' +
                                    '<i class="feather icon-download"></i>Download</span>' +
                                    '</a>' +
                                    '</div>'
                            );
                        }
                    });
                    // Close the remaining tags after the loop
                    $ul.append('</div></div></li>');
                } else if (key === 'سند درخواست') {
                    $ul.append(
                        '<li class="mt-1"><div class="row"><div class="col-4"><strong>' +
                            key +
                            ':</strong></div><div class="col-8 img1">'
                    );
                    // Assuming transDetails[key] is an array or object
                    $.each(detailsData[key], function (index, value) {
                        // Append each image and download link to the 'col-8' div
                        if (value && /\.(jpg|jpeg|png|gif)$/i.test(value)) {
                            // Append each image and download link to the 'col-8' div
                            $ul.find('.img1').append(
                                '<div style="display: inline-block; margin: 5px;position: relative;">' +
                                    '<a style=" position: absolute; display: block; padding: 5px; text-decoration: none; border-radius: 5px;"' +
                                    'class="bg-color-primary text-success"' +
                                    'href="' +
                                    value +
                                    '" download="' +
                                    value +
                                    '">' +
                                    '<span class="badge badge-success badge-pill">' +
                                    '<i class="feather icon-download"></i></span>' +
                                    '</a>' +
                                    '<img style="width:50px; margin: 2px; display: inline-block;"' +
                                    'src="' +
                                    value +
                                    '"/>' +
                                    '</div>'
                            );
                        } else {
                            // Append download button for other file types
                            $ul.find('.img1').append(
                                '<div style="display: inline-block; margin: 5px;">' +
                                    '<a style="position: relative; display: block; padding: 5px; text-decoration: none; border-radius: 5px;"' +
                                    'class="bg-color-primary text-success"' +
                                    'href="' +
                                    value +
                                    '" download="' +
                                    value +
                                    '">' +
                                    '<span class="badge badge-success badge-pill">' +
                                    '<i class="feather icon-download"></i>Download</span>' +
                                    '</a>' +
                                    '</div>'
                            );
                        }
                    });
                    // Close the remaining tags after the loop
                    $ul.append('</div></div></li>');
                } else if (key === 'سند مالی') {
                    $ul.append(
                        '<li class="mt-1"><div class="row"><div class="col-4"><strong>' +
                            key +
                            ':</strong></div><div class="col-8 img2">'
                    );
                    // Assuming transDetails[key] is an array or object
                    $.each(detailsData[key], function (index, value) {
                        // Append each image and download link to the 'col-8' div
                        if (value && /\.(jpg|jpeg|png|gif)$/i.test(value)) {
                            // Append each image and download link to the 'col-8' div
                            $ul.find('.img2').append(
                                '<div style="display: inline-block; margin: 5px;position: relative;">' +
                                    '<a style=" position: absolute; display: block; padding: 5px; text-decoration: none; border-radius: 5px;"' +
                                    'class="bg-color-primary text-success"' +
                                    'href="' +
                                    value +
                                    '" download="' +
                                    value +
                                    '">' +
                                    '<span class="badge badge-success badge-pill">' +
                                    '<i class="feather icon-download"></i></span>' +
                                    '</a>' +
                                    '<img style="width:50px; margin: 2px; display: inline-block;"' +
                                    'src="' +
                                    value +
                                    '"/>' +
                                    '</div>'
                            );
                        } else {
                            // Append download button for other file types
                            $ul.find('.img2').append(
                                '<div style="display: inline-block; margin: 5px;">' +
                                    '<a style="position: relative; display: block; padding: 5px; text-decoration: none; border-radius: 5px;"' +
                                    'class="bg-color-primary text-success"' +
                                    'href="' +
                                    value +
                                    '" download="' +
                                    value +
                                    '">' +
                                    '<span class="badge badge-success badge-pill">' +
                                    '<i class="feather icon-download"></i>Download</span>' +
                                    '</a>' +
                                    '</div>'
                            );
                        }
                    });
                    // Close the remaining tags after the loop
                    $ul.append('</div></div></li>');
                } else {
                    $ul.append(
                        '<li class="mt-1"><div class="row"><div class="col-5"><strong>' +
                            key +
                            '</strong></div><div class="col-7"> ' +
                            detailsData[key] +
                            '</div></div></li>'
                    );
                }
            }
            contentDiv.append($ul);
            $('.modal-body').append(contentDiv); // Append the ul element
            $('.transaction_details_modal').modal();
        },
        beforeSend: function (xhr) {
            block(btn);
        },
        complete: function () {
            unblock(btn);
        }
    });
});
