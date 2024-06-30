// if ($('#countdown-verify-end').length) {
//     var $countdownOptionEnd = $('#countdown-verify-end');

//     $countdownOptionEnd.countdown({
//         date: resend_time * 1000, // 1 minute later
//         text: '<span class="day">%s</span><span class="hour">%s</span><span>: %s</span><span>%s</span>',
//         end: function () {
//             $('#resent-counter').addClass('d-none');
//             $('#sendAgain').removeClass('d-none');
//         }
//     });
// }
var resend_time = Math.floor(Date.now() / 1000) + 60;
$(document).ready(function () {
    counterTime();
});

$('#one-time-login-form').submit(function (e) {
    e.preventDefault();

    if ($(this).valid()) {
        var formData = new FormData(this);
        var form = $(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function (data) {
                window.location.href = redirect_url;
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

$(document).ready(function () {
    $('.activation-code-input').activationCodeInput({
        number: 5
    });
});

$('#sendAgain').on('click', function () {
    $('#login-resend-sms-form').submit();
});

function counterTime() {
    resend_time = Math.floor(Date.now() / 1000) + 60;
    updateCounter();
    $('#sendAgain').addClass('d-none');
    $('#resent-counter').removeClass('d-none');
}

function updateCounter() {
    var currentTime = Math.floor(Date.now() / 1000);
    var timeRemaining = Math.max(0, resend_time - currentTime);

    var minutes = Math.floor(timeRemaining / 60);
    var seconds = timeRemaining % 60;

    // Update the countdown element
    $('#countdown-verify-end').text(pad(minutes) + ':' + pad(seconds));

    if (timeRemaining > 0) {
        // If time is remaining, schedule the next update
        setTimeout(updateCounter, 1000);
    } else {
        // If time is up, show the "Send Again" button
        $('#resent-counter').addClass('d-none');
        $('#sendAgain').removeClass('d-none');
    }
}

function pad(value) {
    return value < 10 ? '0' + value : value;
}
