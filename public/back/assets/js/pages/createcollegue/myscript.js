// const {last} = require('lodash');

$(document).ready(function () {
    noneProfitChack();
    $('#no_profit_installment').on('change', function () {
        noneProfitChack();
    });

    $('.select2').select2();

    var userSelect2 = $('.user_select2');

    userSelect2.select2({
        placeholder: 'شماره کاربر را وارد کنید',
        tags: false,
        ajax: {
            url: url,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                console.log(data);
                // Assuming data is an array of user objects with 'id', 'name', 'firstname', and 'lastname' properties
                var results = $.map(data, function (user) {
                    return {
                        id: user.id,
                        text: user.username,
                        name: user.first_name,
                        lastname: user.last_name,
                        credit: user.purchasecredit
                    };
                });

                return {
                    results: results
                };
            },
            cache: true
        },
        minimumInputLength: 11,
        maximumInputLength: 11,
        headers: {
            'X-CSRF-TOKEN': window.Laravel.csrfToken
        },
        language: {
            inputTooShort: function (args) {
                var remainingChars = args.minimum - args.input.length;
                return 'حداقل ' + remainingChars + ' کاراکتر دیگر وارد کنید';
            },
            inputTooLong: function (args) {
                var overChars = args.input.length - args.maximum;
                return 'شما ' + overChars + ' کاراکتر وارد اضافه کرده‌اید';
            },
            noResults: function () {
                return 'نتیجه‌ای یافت نشد، لطفا شماره درست وارد کنید!';
            }
        }
    });

    // Optional: Handle the selection event if needed
    userSelect2.on('select2:select', function (e) {
        console.log('Selected:', e.params.data);
    });

    // Listen for the 'select2:selecting' event to dynamically update the options
    userSelect2.on('select2:selecting', function (e) {
        console.log(e.params.args.data);
        var option = new Option(
            e.params.args.data.text,
            e.params.args.data.id,
            true,
            true
        );

        $(option).attr({
            'data-name': e.params.args.data.name,
            'data-lastname': e.params.args.data.lastname,
            creadit_attr: e.params.args.data.credit
            // Add more attributes as needed
        });

        userSelect2.append(option).trigger('change');
    });

    $('#summit_button').on('click', function (e) {
        e.preventDefault(); // Prevent the default form submission

        var persianDate = $('#enddate_persian').val();

        $('#store_create_form').submit();
    });
    $('#summit_button1').on('click', function (e) {
        e.preventDefault(); // Prevent the default form submission
        // console.log('hey');
        let moneyInput = $('#recredition_amount');
        let money = moneyInput.val();
        let money_changed = money.replace(/,/g, '');
        moneyInput.val(money_changed);
        // console.log(moneyInput.val());

        $('#reaccreditationStoreForm').submit();
    });
    $('#submit_button2').on('click', function (e) {
        e.preventDefault(); // Prevent the default form submission
        let moneyInput1 = $('#ReCredintAmount');
        let money = moneyInput1.val();
        let money_changed1 = money.replace(/,/g, '');
        moneyInput1.val(money_changed1);
        if (money_changed1 < 10000) {
            toastr.error('مبلغ شارژ کمتر از مقدار معتبر میباشد!');
        } else {
            $('#colleagueDocumentStore').submit();
        }
    });
    $('.moneyInputReady').each(function () {
        var input = $(this).val();
        var digits = input.replace(/\D/g, '');
        var formattedNumber = addCommas(digits);
        $(this).val(formattedNumber);
    });
    $('.moneyInput').on('input', function () {
        var input = $(this).val();
        var digits = input.replace(/\D/g, '');
        var formattedNumber = addCommas(digits);
        $(this).val(formattedNumber);
        if (input < 10000) {
            $('.price_limit_message').text(
                'مبلغ وارده باید بیشتر از ' + addCommas(10000) + 'ریال باشد!'
            );
        } else {
            $('.price_limit_message').text('');
        }
    });
    $('#ReCredintAmount').on('input', function () {
        if (input < 10000) {
            $('.price_limit_message').text(
                'مبلغ وارده باید بیشتر از ' + addCommas(10000) + 'ریال باشد!'
            );
        } else {
            $('.price_limit_message').text('');
        }
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
    //

    $('#enddate_field').ready(function () {});

    $('#user_selection').change(function () {
        let selectedOption = $(this).find(':selected');
        let name = selectedOption.attr('data-name');
        let lastname = selectedOption.attr('data-lastname');
        $('#user_title').text('کاربر:' + ' ');
        $('#user_name').text(name + ' ' + lastname);
        // console.log(name + ' ' + lastname);
    });
    $('.account_selection').change(function () {
        let selectedOption = $(this).find(':selected');
        let name = selectedOption.attr('attr-name');
        $('.account-title').text('حساب:' + ' ');
        $('.account-name').text(name);
    });
});

function noneProfitChack() {
    let none_profit = $('#no_profit_installment').val();

    if (none_profit == 'no') {
        $('.no_profit_fee').hide(500);
    } else {
        $('.no_profit_fee').show(500);
    }
}
$('.imageInput2').on('change', function (e) {
    // Get the selected files
    var files = e.target.files;

    if (files && files.length > 0) {
        // Clear existing images
        $('.imgContainer2').empty();

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
                    $('.imgContainer2').append(image);
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
