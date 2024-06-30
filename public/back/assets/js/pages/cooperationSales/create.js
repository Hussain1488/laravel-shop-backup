// const {isTypedArray} = require('lodash');
$(document).ready(function () {
    $('.installment_fileds_container').hide();
    $('.monthly_instalment').hide();
    $('.weekly_instalment').hide();
    var userSelect = $('.user_select2');

    userSelect.select2({
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
                        credit: user.purchasecredit,
                        wallet: user.wallet.balance
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
    userSelect.on('select2:select', function (e) {
        console.log('Selected:', e.params.data);
    });

    // Listen for the 'select2:selecting' event to dynamically update the options
    userSelect.on('select2:selecting', function (e) {
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
            creadit_attr: e.params.args.data.credit,
            'inventory-attr': e.params.args.data.wallet
            // Add more attributes as needed
        });

        userSelect.append(option).trigger('change');
    });

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

    $('#cash_status').on('change', function () {
        var cash_status = $('#cash_status').val();

        if (cash_status == 'monthly_installment') {
            $('.installment_fileds_container').show(500);
            $('.monthly_instalment').show(500);
            $('.weekly_instalment').hide(500);
            $('#monthly_payment').prop('disabled', false);
            updatePaymentMonthly();
        } else if (cash_status == 'weekly_installment') {
            $('.installment_fileds_container').show(500);
            $('.monthly_instalment').hide(500);
            $('.weekly_instalment').show(500);
            $('#weekly_payment').prop('disabled', false);
            updatePaymentWeekly();
            // alert('weekly');
        } else if (cash_status == 'm_none_profit') {
            $('.installment_fileds_container').show(500);
            $('.monthly_instalment').hide(500);
            $('.weekly_instalment').hide(500);
            $('#weekly_payment').prop('disabled', false);
            updateNonProfitPaymentMonthly();
        } else if (cash_status == 'w_none_profit') {
            $('.installment_fileds_container').show(500);
            $('.monthly_instalment').hide(500);
            $('.weekly_instalment').hide(500);
            $('#weekly_payment').prop('disabled', false);
            updateNonProfitPaymentWeekly();
        } else {
            $('.installment_fileds_container').hide(500);
            $('.monthly_instalment').hide(500);
            $('.weekly_instalment').hide(500);
            $('#payment_method').prop('disabled', true);
            $('#payment_method').val($('#payment_method option:first').val());
            var payment1 = $('#main_price').val();
            // console.log(payment1);
            $('#prepayment').val(payment1);
            $('#each_pay').val(0);
        }
    });
    $('#main_price').on('input', function () {
        let price = $('#main_price').val().replace(/\D/g, '');
        console.log(price);
        if (price < 1000000) {
            $('.price_limit_error').text(
                'قیمت اصلی باید بیشتر از' + addCommas(1000000) + 'ریال باشد'
            );
        } else if (price > 100000000) {
            $('.price_limit_error').text(
                'قیمت اصلی باید کمتر از' + addCommas(100000000) + 'ریال باشد'
            );
        } else {
            $('.price_limit_error').text('');
        }
        if ($('#cash_status').val() == 'monthly_installment') {
            updatePaymentMonthly();
        } else if ($('#cash_status').val() == 'weekly_installment') {
            updatePaymentWeekly();
        } else if ($('#cash_status').val() == 'm_none_profit') {
            updateNonProfitPaymentMonthly();
        } else if ($('#cash_status').val() == 'w_none_profit') {
            updateNonProfitPaymentWeekly();
        } else {
            let payment = $('#main_price').val();
            $('#prepayment').val(payment);
            $('#each_pay').val(0);
        }
    });
    $('#monthly_payment').on('change', function () {
        if ($('#cash_status').val() == 'monthly_installment') {
            updatePaymentMonthly();
        }
    });
    $('#weekly_payment').on('change', function () {
        if ($('#cash_status').val() == 'weekly_installment') {
            updatePaymentWeekly();
        }
    });
    function updatePaymentMonthly() {
        let payment = parseFloat($('#main_price').val().replace(/,/g, ''));
        let installment = parseFloat($('#monthly_payment').val());
        let total_pay = payment + payment * ((installment * 3) / 100);
        let prepayment = total_pay * 0.3;
        let rest_pay = total_pay - prepayment;
        let each_pay = Math.round(rest_pay / $('#monthly_payment').val());
        $('#prepayment').val(addCommas(prepayment));
        $('#each_pay').val(addCommas(each_pay));
    }
    function updatePaymentWeekly() {
        let payment = parseFloat($('#main_price').val().replace(/,/g, ''));
        let installment = parseFloat($('#weekly_payment').val());
        let total_pay = payment + payment * ((installment * 0.9) / 100);
        let prepayment = total_pay * 0.3;
        let rest_pay = total_pay - prepayment;
        let each_pay = Math.round(rest_pay / $('#weekly_payment').val());
        $('#prepayment').val(addCommas(prepayment));
        $('#each_pay').val(addCommas(each_pay));
    }
    function updateNonProfitPaymentWeekly() {
        let payment = parseFloat($('#main_price').val().replace(/,/g, ''));
        let prepayment = payment * 0.3;
        let rest_pay = payment - prepayment;
        let each_pay = Math.round(rest_pay / 12);
        $('#prepayment').val(addCommas(prepayment));
        $('#each_pay').val(addCommas(each_pay));
    }
    function updateNonProfitPaymentMonthly() {
        let payment = parseFloat($('#main_price').val().replace(/,/g, ''));
        let prepayment = payment * 0.3;
        let rest_pay = payment - prepayment;
        let each_pay = Math.round(rest_pay / 4);
        $('#prepayment').val(addCommas(prepayment));
        $('#each_pay').val(addCommas(each_pay));
    }
    $('.custom-file-input').on('change', function () {
        var fileName = $(this).val();
        $(this).next('.custom-file-label').html(fileName);
    });
    $('.moneyInput').on('input', function () {
        var input = $(this).val();
        var digits = input.replace(/\D/g, '');
        var formattedNumber = addCommas(digits);
        $(this).val(formattedNumber);
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

    $('.persian-date-picker').customPersianDate();

    if ($('#user_select').find('option').length === 1) {
        selectElement.find('option:first').prop('selected', true);
        var creditAttrValue1 = selectedOption.attr('creadit_attr');
        var formattedNumber1 = addCommas(creditAttrValue1);
        $('#purchase_creadite').val(formattedNumber1);
    }

    $('#user_select').change(function () {
        var selectedOption = $(this).find(':selected');
        var creditAttrValue = selectedOption.attr('creadit_attr');
        // var orginal_value = selectedOption.attr('creadit_attr');

        let name = selectedOption.attr('data-name');
        let lastname = selectedOption.attr('data-lastname');
        $('#user_title').text(':');
        $('#user_name').text(name + ' ' + lastname);

        if (creditAttrValue == '') {
            creditAttrValue = 0;
        }
        var formattedNumber = addCommas(creditAttrValue);
        $('#purchase_creadite').val(formattedNumber);
        // $('#Creadit_hidden').val(orginal_value);
    });

    $('#submit_button').click(function () {
        // Get the values of input fields

        let end_date = new Date($(this).attr('data-end-date'));

        var creadit = parseInt(
            $('#purchase_creadite').val().replace(/,/g, ''),
            10
        );

        // console.log(creadit);
        $('#purchase_creadite').val();
        var main_price = $('#main_price').val().replace(/,/g, '');
        var store_creadit = parseFloat($('#store_creadit').val());
        let toDay = new Date();
        console.log(store_creadit, main_price, toDay <= end_date);
        if (toDay <= end_date) {
            if (main_price == '' || isNaN(main_price) || main_price <= 0) {
                // console.log('this is NaN');
                $('#myModal3').modal();
            } else {
                if (store_creadit >= main_price) {
                    if (creadit >= main_price) {
                        if (main_price < 1000000) {
                            toastr.warning(
                                'قیمت فروش شما کمتر از مقدار معتبر میباشد!'
                            );
                        } else if (main_price > 100000000) {
                            toastr.warning(
                                'قیمت فروش شما بیشتر از مقدار معتبر میباشد!'
                            );
                        } else {
                            $('#user-create-form').submit();
                        }
                    } else {
                        toastr.warning(
                            'مقدار قیمت اصلی نباید از اعتبار خریدار بیشتر باشد.'
                        );
                    }
                } else {
                    $('#store_creadit2').text(addCommas(store_creadit));
                    $('#myModal2').modal();
                }
            }
        } else {
            toastr.warning(
                'مهلت اعتبار فروشگاه شما به اتمام رسیده است و شما نمیتوانید فروشی انجام دهید!'
            );
        }
    });
    $('#User_selected').change(function () {
        var selectedOption = $(this).find(':selected');
        var creditAttrValue = selectedOption.attr('inventory-attr');
        if (creditAttrValue == '') {
            creditAttrValue = 0;
        }
        var formattedNumber = addCommas(creditAttrValue);
        $('#inventory').val(formattedNumber);
        $('#Creadit_hidden').val(creditAttrValue);
    });

    $('.settlementtime_button').click(function () {
        let data_day = $(this).attr('data_day');
        let data_date = $(this).attr('data_date');
        let dateA = new Date(data_date);
        let dateNew = new Date();
        let timeDifference = dateNew.getTime() - dateA.getTime();
        let daysDifference = timeDifference / (1000 * 60 * 60 * 24);
        var DaysDiff = parseInt(daysDifference);
        var data_date_update = parseInt(data_day);

        if (data_date_update > DaysDiff) {
            let time = data_date_update - DaysDiff;
            $('#user_day_time').text(time);
            $('#myModal').modal();
        } else {
            window.location.href = $(this).attr('data-route');
        }
    });

    // console.log(user);
});

$('#clearing_button').on('click', function () {
    let total_amount = parseFloat($('#total_amount').val().replace(/\D/g, ''));
    let reqest_amount = parseFloat(
        $('#reqest_amount').val().replace(/\D/g, '')
    );
    let shaba_number = $('#shaba_number').val();
    // let digitPattern16 = /^\d{16}$/;
    // let digitPattern26 = /^\d{26}$/;
    // console.log(shaba_number);

    if (total_amount >= reqest_amount) {
        if (reqest_amount <= 0) {
            $('.modal-body').html(
                '<p>' + 'مقدار درخواست واریز معتبر نمیباشد' + '</p>'
            );
            $('#myModal').modal();
        } else {
            if (shaba_number.length == 16 || shaba_number.length == 26) {
                $('#reqest_amount').val(reqest_amount);
                // console.log($('#reqest_amount').val());
                $('#clearing_form').submit();
            } else {
                $('.modal-body').html(
                    '<p>' +
                        'شماره ثبت شبا درست نمیباشد،‌لطفا اصلاح کرده دوباره امتهان کنید.' +
                        '</p>'
                );
                $('#myModal').modal();
            }
        }
    } else {
        let a = $('#total_amount').val();
        $('.modal-body').html(
            '<p>' +
                'شما نمیتوانید از موجودی قابل برداشت خود بیشتر درخواست بدهید.' +
                '<br />' +
                ' موجودی قابل برداشت شما فعلا:' +
                '<span class="text-success" id="total_amount_text" >' +
                '</span> ' +
                ' ریال میباشد.' +
                '</p>'
        );
        $('#total_amount_text').text(a);
        $('#myModal').modal();
    }
});

$('#summit_button1').on('click', function (e) {
    e.preventDefault();

    let purchase_credit = $('#purchase_credit').val().replace(/,/g, '');

    $('#purchase_credit').val(purchase_credit);

    $('#createCredit').submit();
});

$('.user_selection').change(function () {
    let selectedOption = $(this).find(':selected');
    let name = selectedOption.attr('data-name');
    let lastname = selectedOption.attr('data-lastname');
    $('.user_title').text('کاربر:' + ' ');
    $('.user_name').text(name + ' ' + lastname);
    // console.log(name + ' ' + lastname);
});
$('.imageInput').on('change', function (e) {
    // Get the selected files
    var files = e.target.files;

    if (files && files.length > 0) {
        // Clear existing images
        $('.imgContainer').empty();
        for (var i = 0; i < files.length; i++) {
            if (files[i].type.startsWith('image/')) {
                var reader = new FileReader();
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
                    $('.imgContainer').append(image);
                };

                reader.readAsDataURL(files[i]);
            } else {
                var fileName = $('<span>').text(files[i].name).css({
                    border: '2px solid #ccc',
                    padding: '5px',
                    margin: '5px',
                    'box-shadow': '0 0 5px rgba(0, 0, 0, 0.3)',
                    display: 'inline-block'
                });
                $('.imgContainer').append(fileName);
            }
        }
    }
});
