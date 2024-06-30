$('.amount-input').attr('autocomplete', 'off');

$(document).on('keyup', '.amount-input', function () {
    if (!$(this).val()) {
        $(this).next('.form-text').remove();
        return;
    }

    if (!$(this).next('.form-text').length) {
        $(this).after(
            '<small class="form-text text-success amount-helper"></small>'
        );
    }

    var text = number_format($(this).val()) + ' ریال';

    $(this).next('.form-text').text(text);
});

$('.amount-input').trigger('keyup');

$('#wallet-create-form').validate({
    rules: {
        amount: {
            required: true,
            max: 5000000000,
            min: 10000
        },
        gateway: {
            required: true
        }
    }
});

$('.gateway-select').select2();
