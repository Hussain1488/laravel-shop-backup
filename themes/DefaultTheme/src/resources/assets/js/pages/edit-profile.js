$(document).ready(function () {
    $('.persian-date-picker').customPersianDate();
});

(function ($) {
    $.fn.customPersianDate = function () {
        $(this).each(function (i, el) {
            var pickerId = 'customPersianDate' + Date.now();
            var input = this;

            if ($(input).data('persian-date')) return;

            $(input).after(
                '<input type="hidden" name="' +
                    $(input).attr('name') +
                    '" id="' +
                    pickerId +
                    '">'
            );
            $(input).removeAttr('name');
            $(input).attr('autocomplete', 'off');
            $(input).attr('data-persian-date', true);

            $(input).on('keydown', function (e) {
                e.preventDefault();
                $(input).val('');
                $('#' + pickerId).val('');
                $(input).trigger('change');
            });

            let pickerOptions = {
                toolbox: {
                    calendarSwitch: {
                        enabled: false
                    }
                },
                format: 'YYYY-MM-DD',
                initialValue: false,
                altField: '#' + pickerId,
                altFormat: 'YYYY-MM-DD',
                altFieldFormatter: function (unixDate) {
                    var self = this;
                    var thisAltFormat = self.altFormat.toLowerCase();
                    if (
                        thisAltFormat === 'gregorian' ||
                        thisAltFormat === 'g'
                    ) {
                        return new Date(unixDate);
                    }
                    if (thisAltFormat === 'unix' || thisAltFormat === 'u') {
                        return unixDate;
                    } else {
                        var pd = new persianDate(unixDate);
                        pd.formatPersian = this.persianDigit;

                        setTimeout(function () {
                            $(input).trigger('change');
                        }, 100);

                        return pd.format(self.altFormat).toEnglishDigit();
                    }
                },
                onSelect: function (unixDate) {
                    // $(input).trigger('change');
                },
                onSet: function (unixDate) {
                    // $(input).trigger('change');
                }
            };

            if ($(input).data('timestamps')) {
                pickerOptions.timePicker = {
                    enabled: true,
                    meridian: {
                        enabled: false
                    },
                    second: {
                        enabled: false
                    }
                };

                pickerOptions.altFormat = 'YYYY-MM-DD HH:mm:ss';
                pickerOptions.format = 'YYYY-MM-DD HH:mm:ss';
            }

            var publishDatePicker = $(input).pDatepicker(pickerOptions);

            var date = $(input).val();

            if (date) {
                publishDatePicker.setDate(parseInt(date + '000'));
            }
        });

        return this;
    };
})(jQuery);
String.prototype.toEnglishDigit = function () {
    var find = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    var replace = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    var replaceString = this;
    var regex;
    for (var i = 0; i < find.length; i++) {
        regex = new RegExp(find[i], 'g');
        replaceString = replaceString.replace(regex, replace[i]);
    }
    return replaceString;
};
$.validator.addMethod(
    'regex',
    function (value, element, regexp) {
        var re = new RegExp(regexp);
        return this.optional(element) || re.test(value);
    },
    'لطفا یک مقدار معتبر وارد کنید'
);

jQuery('#profile-form').validate({
    rules: {
        first_name: {
            required: true
        },
        last_name: {
            required: true
        },
        gender: {
            required: true
        },
        birth_date: {
            required: true
        },
        mobile: {
            required: true,
            regex: '(09)[0-9]{9}'
        },
        Id_number: {
            required: true,
            regex: '[0-9]{10}'
        },

        province_id: {
            required: true
        },
        city_id: {
            required: true
        }
    }
});

$.validator.addMethod(
    'regex',
    function (value, element, regexp) {
        var re = new RegExp(regexp);
        return this.optional(element) || re.test(value);
    },
    'لطفا یک مقدار معتبر وارد کنید'
);

$('#profile-form').submit(function (e) {
    let id = $('.id_number').val();
    e.preventDefault();
    if ($(this).valid() && vmsNationalCode(id)) {
        $('.id_number_error_message').addClass('d-none');
        var formData = new FormData(this);
        var btn = $('#submit-btn');
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function (data) {
                Swal.fire({
                    title: 'تغییرات با موفقیت ثبت شد',
                    type: 'success',
                    showCancelButton: false,
                    confirmButtonText: 'باشه',
                    closeOnConfirm: false,
                    closeOnCancel: false
                });
            },
            beforeSend: function (xhr) {
                block(btn);
                xhr.setRequestHeader(
                    'X-CSRF-TOKEN',
                    $('meta[name="csrf-token"]').attr('content')
                );
            },
            complete: function () {
                unblock(btn);
            },

            cache: false,
            contentType: false,
            processData: false
        });
    } else {
        $(this).valid();
        if (!vmsNationalCode(id)) {
            $('.id_number_error_message').removeClass('d-none');
        }
    }
});

function vmsNationalCode(input) {
    if (
        !/^\d{10}$/.test(input) ||
        input == '0000000000' ||
        input == '1111111111' ||
        input == '2222222222' ||
        input == '3333333333' ||
        input == '4444444444' ||
        input == '5555555555' ||
        input == '6666666666' ||
        input == '7777777777' ||
        input == '8888888888' ||
        input == '9999999999'
    )
        return false;
    var check = parseInt(input[9]);
    var sum = 0;
    var i;
    for (i = 0; i < 9; ++i) {
        sum += parseInt(input[i]) * (10 - i);
    }
    sum %= 11;
    return (sum < 2 && check == sum) || (sum >= 2 && check + sum == 11);
}
