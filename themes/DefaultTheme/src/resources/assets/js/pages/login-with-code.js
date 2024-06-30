jQuery('#login-with-code-form').validate({
    rules: {
        mobile: {
            required: true
        }
    }
});

$(document).ready(function () {
    $('.activation-code-input').activationCodeInput({
        number: 5
    });
});
var resend_time = Math.floor(Date.now() / 1000) + 60; // Initial value, 2 minutes from now

$(document).ready(function () {
    $('#login-with-code-form').submit(function (e) {
        e.preventDefault();

        if ($(this).valid()) {
            var formData = new FormData(this);
            var form = $(this);

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function (data) {
                    unblock();
                    if (data.data == 'login') {
                        var url = new URL(form.data('redirect'));
                        url.searchParams.set('mobile', $('#mobile').val());
                        window.location.href = url;
                    } else {
                        counterTime();
                        $('#registerWithCode').modal();
                        resend_time = Math.floor(Date.now() / 1000) + 60;
                    }
                },

                beforeSend: function (xhr) {
                    block('.form-ui');
                    xhr.setRequestHeader(
                        'X-CSRF-TOKEN',
                        $('meta[name="csrf-token"]').attr('content')
                    );
                },
                complete: function () {
                    unblock('.form-ui');
                },

                cache: false,
                contentType: false,
                processData: false
            });
        }
    });
});
$('#sendAgain1').on('click', function () {
    let form = $('#login-resend-sms-form1');
    block('.form-ui');
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: new FormData(form[0]),
        processData: false, // Important: prevent jQuery from processing the data
        contentType: false, // Important: prevent jQuery from setting the content type
        success: function (data) {
            counterTime();
            unblock('.form-ui');
            $('#success-alert1').text('کد مجدد برای شما ارسال شد!');
            $('#success-alert1').removeClass('d-none');
            $('#registerWithCode').modal();
        },
        beforeSend: function () {
            block('.form-ui');
        } // <-- Missing closing brace here
    });
});

$('#reject-refral').on('click', function () {
    window.location.href = '/';
});

$(document).on('click', '.refral_test', function () {
    $('#refralModal').modal();
});

$('#refral_send_button').on('click', function () {
    let btn = $(this);
    let form = $('#refral_form');
    let formData = form.serialize();
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: formData,
        dataType: 'json', // Specify that the expected response type is JSON
        success: function (response) {
            if (response.status == 'success') {
                toastr.success(response.message);
                window.location.href = '/';
            } else {
                toastr.warning(response.message);
            }
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
            alert('An error occurred. Please try again.');
        },

        beforeSend: function (xhr) {
            block(btn);
        },
        complete: function () {
            unblock(btn);
        }
    });
});

$('#sendCodeRegister').on('click', function () {
    $('#registerWithCode').modal('hide');

    let form = $('#code_varification2');
    var address = form.attr('action');
    if ($(this).valid()) {
        var formData = new FormData(form[0]);
        $.ajax({
            url: address,
            type: 'POST',
            data: formData,
            processData: false, // Important: prevent jQuery from processing the data
            contentType: false, // Important: prevent jQuery from setting the content type
            success: function (data) {
                unblock();
                if (data.data == 'true') {
                    $('#refralModal').modal();
                    // window.location.href = '/';
                } else {
                    $('#code_error3')
                        .text('کد وارد شده اشتباه است')
                        .removeClass('d-none');
                    $('#registerWithCode').modal();
                }
            },
            beforeSend: function () {
                block('.form-ui');
            }
        });
    }
});

function counterTime() {
    resend_time = Math.floor(Date.now() / 1000) + 60;
    updateCounter();
    $('#sendAgain1').addClass('d-none');
    $('#resent-counter1').removeClass('d-none');
}

function updateCounter() {
    var currentTime = Math.floor(Date.now() / 1000);
    var timeRemaining = Math.max(0, resend_time - currentTime);

    var minutes = Math.floor(timeRemaining / 60);
    var seconds = timeRemaining % 60;

    // Update the countdown element
    $('#countdown-verify-end1').text(pad(minutes) + ':' + pad(seconds));

    if (timeRemaining > 0) {
        // If time is remaining, schedule the next update
        setTimeout(updateCounter, 1000);
    } else {
        // If time is up, show the "Send Again" button
        $('#sendAgain1').removeClass('d-none');
        $('#resent-counter1').addClass('d-none');
    }
}

function pad(value) {
    return value < 10 ? '0' + value : value;
}
