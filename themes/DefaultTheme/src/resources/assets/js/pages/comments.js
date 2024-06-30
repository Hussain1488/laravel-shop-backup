function swiper() {
    new Swiper('.mySwiper', {
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        }
    });
}
$('#comments-form').submit(function (e) {
    e.preventDefault();
    var form = $(this);
    var btn = $('.comment-submit-btn');

    var formData = new FormData(this);

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: formData,
        success: function (data) {
            Swal.fire({
                text: 'نظر شما با موفقیت ثبت شد و پس از تایید نمایش داده خواهد شد.',
                type: 'success',
                showCancelButton: false,
                confirmButtonText: 'باشه'
            });

            form.trigger('reset');

            $('.comment-replay-to').hide();
        },

        beforeSend: function (xhr) {
            xhr.setRequestHeader(
                'X-CSRF-TOKEN',
                $('meta[name="csrf-token"]').attr('content')
            );
            block(btn);
        },
        complete: function () {
            unblock(btn);
        },

        cache: false,
        contentType: false,
        processData: false
    });

    // Get the target div ID from the URL (after "#")
});

$('.comment-replay').click(function (e) {
    e.preventDefault();
    var a = $(this);

    $('.comment-replay-to')
        .find('span')
        .text('در پاسخ به: ' + a.data('name'));
    $('#comments-form input[name="comment_id"]').val(a.data('id'));
    $('.comment-replay-to').show();

    $('html, body').animate(
        {
            scrollTop: $('.comment--form').offset().top
        },
        700
    );

    $('#comments-form textarea').focus();
});

$('.comment-replay-to a').click(function (e) {
    e.preventDefault();
    $('#comments-form input[name="comment_id"]').val('');
    $('.comment-replay-to').hide();
});

$(document).ready(function () {
    var swiper = new Swiper('.mySwiper', {
        spaceBetween: 30,
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        }
    });
    // var targetDivId = window.location.+'?'+.substring(1);
    var urlWithoutQuery = window.location.href.split('?')[1];
    console.log(urlWithoutQuery);
    let targetDivId = urlWithoutQuery;

    if (targetDivId) {
        // Find the element with the matching ID
        var targetDiv = $('#' + targetDivId);

        if (targetDiv.length) {
            // Smooth scroll to the target div
            $('html, body').animate(
                {
                    scrollTop: targetDiv.offset().top
                },
                1000
            ); // Adjust the animation duration (in milliseconds)
        }
    }
});

$(document).on('click', '.state-comment-submit-button', function (e) {
    e.preventDefault();
    let form = $('#state-comment-form');
    // alert('اثغ');

    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: form.serialize(),
        success: function (data) {
            // console.log(data);
            if (data.status == 'success') {
                toastr.success('نظر شما با موفقیت ثبت شد.');
                $('#state-comment-modal').modal('hide');
            } else {
                $('#state-comment-modal').modal('hide');
                toastr.warning(data.message);
            }
        }
    });
});

// function likeAction() {
//     const random = (min, max) =>
//         Math.floor(Math.random() * (max - min + 1)) + min;
//     const COUNT = 360 / 6;
//     const BTN = document.querySelectorAll('.button--heart');
//     const setParticles = (b) => {
//         const PARTICLES = b.querySelectorAll('.heart__particle');
//         PARTICLES.forEach((particle, index) => {
//             const CHARACTER = {
//                 '--d': random(30, 60),
//                 '--r': (360 / 25) * index,
//                 '--h': random(0, 360),
//                 '--t': random(25, 50) / 100,
//                 '--s': random(20, 60) / 100
//             };

//             particle.setAttribute(
//                 'style',
//                 JSON.stringify(CHARACTER)
//                     .replace(/,/g, ';')
//                     .substring(1, JSON.stringify(CHARACTER).length - 1)
//                     .replace(/"/g, '')
//             );
//         });
//     };

//     BTN.forEach((b) => {
//         setParticles(b);
//         b.addEventListener('click', () => {
//             b.classList.toggle('button--active');
//             if (b.classList.contains('button--active')) setParticles(b);
//         });
//     });
// }

$(document).on('click', '.state-comments-button', function () {
    let btn = $(this);
    $('.comment-id').val();
    $.ajax({
        url: btn.attr('action'),
        type: 'get',
        success: function (data) {
            $('.state-comment').empty();
            $('.state-comment').append(data);
            $('.state-id').val(btn.data('id'));
            $('.comment-body').val('');
            $('#state-comment-modal').modal();
        }
    });
});
$(document).on('click', '.comment-response-button', function () {
    let btn = $(this);
    $('.comment-id').val(btn.data('id'));
    $('.comment-body').val('');
    $('.comment-body').focus();
});

$(document).on('click', '.like, .dislike', function (e) {
    let btn = $(this);

    if (!btn.hasClass('active-like', 'active-dislike')) {
        $('.' + btn.data('unique')).removeClass('active-like');
        $('.' + btn.data('unique')).removeClass('active-dislike');
        $(this).addClass(btn.data('class'));

        $.ajax({
            url: btn.attr('action'),
            type: 'post',
            data: {type: btn.data('type')},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $('span.' + btn.data('unique'))
                    .empty()
                    .text(data.like);
                $('span.dis' + btn.data('unique'))
                    .empty()
                    .text(data.dislike);
            }
        });
    }

    // alert(btn.data('id'));
});

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.show-more').forEach(function (element) {
        element.addEventListener('click', function () {
            const parent = this.parentElement;
            parent.querySelector('.truncated').classList.toggle('d-none');
            parent.querySelector('.full-comment').classList.toggle('d-none');
            this.classList.toggle('d-none');
        });
    });
});
