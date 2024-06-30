var inter = 'pass';
var loading = {
    message:
        '<div class="loadingio-spinner-eclipse-mbdtacxsn3d"> ' +
        '<div class="ldio-ffthf4779sc">' +
        '<div></div>' +
        '</div>' +
        '</div>',
    css: {
        width: 'auto' /* Auto width for the blockUI container */,
        top: '50%' /* Center vertically */,
        left: '50%' /* Center horizontally */,
        transform: 'translate(-50%, -50%)' /* Center the element itself */,
        backgroundColor: 'transparent' /* No background color */,
        border: 'none' /* No border */,
        color: '#333' /* Text color */
    }
};

$(document).ready(function () {
    $('#login-form').submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    window.location.href = redirect_url;
                }
            },
            beforeSend: function (xhr) {
                block('#main-card');
                xhr.setRequestHeader(
                    'X-CSRF-TOKEN',
                    $('meta[name="csrf-token"]').attr('content')
                );
            },
            complete: function () {
                unblock('#main-card');
            },

            cache: false,
            contentType: false,
            processData: false
        });
    });
});

$('#sms-verify').on('click', function () {
    if ($(this).text() == 'ورود با پیامک') {
        $('#flag').val('sms');
        $('#pass-verify-fieldset').addClass('d-none');
        $('#sms-verify-fieldset').removeClass('d-none');
        $(this).text(' ورود با رمز عبور');
        inter = 'sms';
        block('#main-card');
        $.ajax({
            url: sms_varify,
            type: 'GET',
            success: function () {
                
                unblock('#main-card');
            }
        });
        // $.blockUI(loading);
    } else {
        $('#sms-verify-fieldset').addClass('d-none');
        $('#pass-verify-fieldset').removeClass('d-none');
        $(this).text('ورود با پیامک');
        $('#flag').val('pass');
        inter = 'pass';
    }
});
$('#inter-button').on('click', function () {
    $('#login-form').submit();
});
