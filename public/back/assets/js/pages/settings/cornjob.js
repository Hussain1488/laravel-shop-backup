$(document).ready(function () {
    $('#corn_job_form .corn_job_day').on('input', function () {
        var inputValue = $(this);
        var min = inputValue.data('min');
        var max = inputValue.data('max');
        var messageClass = inputValue.data('class');

        if (inputValue.val() >= min && inputValue.val() <= max) {
            console.log(min, max, messageClass);
            $('.' + messageClass).text('');
        } else {
            console.log(min, max, messageClass);
            $('.' + messageClass).text(
                'اطلاعات نادرست! لطفا عددی بین ' +
                    min +
                    ' و ' +
                    max +
                    ' وارد کنید!'
            );
        }
    });

    $('input.checkbox').on('change', function () {
        if ($(this).prop('checked')) {
            $('.' + $(this).data('class')).prop('disabled', false);
        } else {
            $('.' + $(this).data('class')).prop('disabled', true);
        }
    });

    $('input.checkbox').trigger('change');

    $('#cornjob_form_button').on('click', function (e) {
        e.preventDefault();
        let formData = $('#corn_job_form').serialize();
        console.log(beforMessageStat(), recreditCornJob(), afterMessageStat());
        if (!recreditCornJob()) {
            toastr.error(
                'روز وارد شده برای کرن جاب اعتبار فروشگاه معتبر نمیباشد!'
            );
        } else if (!beforMessageStat()) {
            toastr.error(
                'روز وارده برای پیامک قبل از انقضای قسط معتبر نمیباشد!'
            );
        } else if (!afterMessageStat()) {
            toastr.error(
                'روز وارده برای پیامک بعد از انقضای قسط معتبر نمیباشد!'
            );
        } else {
            $.ajax({
                url: $('#corn_job_form').attr('action'),
                type: 'POST',
                data: formData,
                success: function () {
                    Swal.fire({
                        type: 'success',
                        title: 'با موفقیت انجام شد',
                        confirmButtonClass: 'btn btn-primary',
                        confirmButtonText: 'باشه',
                        buttonsStyling: false
                    });
                }
            });
        }
    });
});
function recreditCornJob() {
    let stat = $('.reccredit_day');
    if ($('#store_recredit_check').prop('checked')) {
        return stat.val() >= stat.data('min') && stat.val() <= stat.data('max');
    } else {
        $('.reccredit_day').val(1);
        return true;
    }
}
function beforMessageStat() {
    let stat = $('.befor_pay_message');
    if ($('#befor_message_stat').prop('checked')) {
        return stat.val() >= stat.data('min') && stat.val() <= stat.data('max');
    } else {
        $('.befor_pay_message').val(1);
        return true;
    }
}
function afterMessageStat() {
    let stat = $('.after_pay_message');
    if ($('#after_message_stat').prop('checked')) {
        return stat.val() >= stat.data('min') && stat.val() <= stat.data('max');
    } else {
        $('.after_pay_message').val(1);
        return true;
    }
}
