// const {last} = require('lodash');

var debtor_selection = $('.debtor_selection_input');
var creditor_selection = $('.creditor_selection_input');
$(document).ready(function () {
    $('.debtor-container').hide();
    $('.creditor-container').hide();
    $('.select2').select2();

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
    $('.debtor_selection').change(function () {
        let selectedOption = $(this).find(':selected');
        let code = selectedOption.attr('attr-code');
        if (selectedOption.val() == 0) {
            $('.debtor-container').hide(500);
        } else {
            // Destroy the existing Select2 instance
            if (debtor_selection.data('select2')) {
                // Destroy the existing Select2 instance
                debtor_selection.val(null).trigger('change');
            }

            // Depending on the selected code, call the appropriate function
            if (code == 29) {
                debterUserList(url, debtor_selection, $('.debtor-container'));
            } else if (code == 28) {
                sellerList(sellerUrl, debtor_selection, $('.debtor-container'));
            } else {
                accountList(
                    accountUrl,
                    debtor_selection,
                    $('.debtor-container'),
                    $('.debtor_selection')
                );
            }
        }

        // Reinitialize Select2 after calling the appropriate function
        // debtor_selection.select2();
    });
    $('.creditor_selection').change(function () {
        let selectedOption = $(this).find(':selected');
        let code = selectedOption.attr('attr-code');
        if (selectedOption.val() == 0) {
            $('.creditor-container').hide(500);
        } else {
            // Destroy the existing Select2 instance
            if (creditor_selection.data('select2')) {
                // Destroy the existing Select2 instance
                creditor_selection.val(null).trigger('change');
            }

            // Depending on the selected code, call the appropriate function
            if (code == 29) {
                debterUserList(
                    url,
                    creditor_selection,
                    $('.creditor-container')
                );
            } else if (code == 28) {
                sellerList(
                    sellerUrl,
                    creditor_selection,
                    $('.creditor-container')
                );
            } else {
                accountList(
                    accountUrl,
                    creditor_selection,
                    $('.creditor-container'),
                    $('.creditor_selection')
                );
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
    function debterUserList(url, selector, container) {
        clearSelect2Options(selector);
        $('.debtor-container').removeClass('d-none');
        selector.select2({
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
                cache: false
            },
            minimumInputLength: 11,
            maximumInputLength: 11,
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken
            },
            language: {
                inputTooShort: function (args) {
                    var remainingChars = args.minimum - args.input.length;
                    return (
                        'حداقل ' + remainingChars + ' کاراکتر دیگر وارد کنید'
                    );
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
        selector.on('select2:select', function (e) {
            console.log('Selected:', e.params.data);
        });

        // Listen for the 'select2:selecting' event to dynamically update the options
        selector.on('select2:selecting', function (e) {
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

            selector.append(option).trigger('change');
        });
        container.show(500);
    }

    function accountList(url, selector, container, type_selector) {
        clearSelect2Options(selector);
        // console.log(type_selector.val());
        selector.select2({
            placeholder: 'حساب مد نظر را انتخاب کنید!',
            tags: false,
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function () {
                    return {
                        id: type_selector.val()
                    };
                },
                processResults: function (data) {
                    // Log the data to the console for debugging
                    console.log(data);

                    var results = $.map(data, function (account) {
                        return {
                            id: account.id,
                            text: account.bankname // 'text' property is required by Select2
                        };
                    });

                    return {
                        results: results
                    };
                },
                cache: false
            },
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken
            },
            language: {}
        });

        // Optional: Handle the selection event if needed
        selector.on('select2:select', function (e) {
            console.log('Selected:', e.params.data);
        });
        container.show(500);
    }
    function sellerList(url, selector, container) {
        clearSelect2Options(selector);
        // $('.debtor-container').removeClass('d-none');
        selector.select2({
            placeholder: 'شماره فروشنده را وارد کنید',
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
                cache: false
            },
            minimumInputLength: 11,
            maximumInputLength: 11,
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken
            },
            language: {
                inputTooShort: function (args) {
                    var remainingChars = args.minimum - args.input.length;
                    return (
                        'حداقل ' + remainingChars + ' کاراکتر دیگر وارد کنید'
                    );
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
        selector.on('select2:select', function (e) {
            console.log('Selected:', e.params.data);
        });

        // Listen for the 'select2:selecting' event to dynamically update the options
        selector.on('select2:selecting', function (e) {
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

            selector.append(option).trigger('change');
        });
        container.show(500);
    }
    function clearSelect2Options(selector) {
        selector.empty();
        selector.val(null).trigger('change');
    }
});
