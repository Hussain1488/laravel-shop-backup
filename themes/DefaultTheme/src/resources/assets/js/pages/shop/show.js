$(document).ready(function () {
    var inputs = $('#advantage-input, #disadvantage-input');
    var inputChangeCallback = function () {
        var self = $(this);
        if (self.val().trim().length > 0) {
            self.siblings('.js-icon-form-add').show();
        } else {
            self.siblings('.js-icon-form-add').hide();
        }
    };
    $('#advantages')
        .delegate('.js-icon-form-add', 'click', function (e) {
            var parent = $('.js-advantages-list');
            if (parent.find('.js-advantage-item').length >= 5) {
                return;
            }

            var advantageInput = $('#advantage-input');

            if (advantageInput.val().trim().length > 0) {
                parent.append(
                    `<div class="ui-dynamic-label ui-dynamic-label--positive js-advantage-item">${advantageInput.val()}
                        <button type="button" class="ui-dynamic-label-remove js-icon-form-remove"></button>
                        <input type="hidden" name="review[advantages][]" value="${advantageInput.val()}">
                    </div>`
                );

                advantageInput.val('').change();
                advantageInput.focus();
            }
        })
        .delegate('.js-icon-form-remove', 'click', function (e) {
            $(this).parent('.js-advantage-item').remove();
        });

    $('#disadvantages')
        .delegate('.js-icon-form-add', 'click', function (e) {
            var parent = $('.js-disadvantages-list');
            if (parent.find('.js-disadvantage-item').length >= 5) {
                return;
            }

            var disadvantageInput = $('#disadvantage-input');

            if (disadvantageInput.val().trim().length > 0) {
                parent.append(
                    `<div class="ui-dynamic-label ui-dynamic-label--negative js-disadvantage-item">${disadvantageInput.val()}
                        <button type="button" class="ui-dynamic-label-remove js-icon-form-remove"></button>
                        <input type="hidden" name="review[disadvantages][]" value="${disadvantageInput.val()}">
                    </div>`
                );

                disadvantageInput.val('').change();
                disadvantageInput.focus();
            }
        })
        .delegate('.js-icon-form-remove', 'click', function (e) {
            $(this).parent('.js-disadvantage-item').remove();
        });
});
$('.shop-review-rate input').on('change', function () {
    $('#selected-rating-text').text($(this).data('title'));
});
$('.review_submit_button').on('click', function (e) {
    var commentState = localStorage.getItem('commentState');
    e.preventDefault();
    var formData = new FormData($('#add-shop-review-form').get(0));

    $.ajax({
        url: $('#add-shop-review-form').attr('action'),
        type: 'POST',
        data: formData,
        success: function (data) {
            if (data == 'success') {
                Swal.fire({
                    text: 'نظر شما با موفقیت ثبت شد و پس از تایید مدیر نمایش داده خواهد شد.',
                    type: 'success',
                    showCancelButton: false,
                    confirmButtonText: 'باشه'
                });
            } else {
                Swal.fire({
                    text: 'به دلیل تخلف در نظر دادن و استفاده از کلمات رکیک و الفاظ نا مربوط، شما مجاز به نظر دادن نمیباشید!',
                    type: 'error',
                    showCancelButton: false,
                    confirmButtonText: 'باشه'
                });
            }
            $('#add-shop-review-form').trigger('reset');
            $('.js-icon-form-remove').trigger('click');
            $('#add-shop-review-modal').modal('hide');
        },

        beforeSend: function (xhr) {
            block('#add-product-review-form');
            xhr.setRequestHeader(
                'X-CSRF-TOKEN',
                $('meta[name="csrf-token"]').attr('content')
            );
        },
        complete: function () {
            unblock('#add-product-review-form');
        },

        cache: false,
        contentType: false,
        processData: false
    });
});
$('.add-shop-review-modal-button').on('click', function () {
    let btn = $('#add-shop-review-modal-button');
    console.log(btn.data('action'));
    $('#add-shop-review-form').attr('action', btn.data('action'));
    $('#add-shop-review-modal').modal();
});
$('.comments-likes button').on('click', function (e) {
    let btn = $(this);

    $.ajax({
        url: $(this).data('action'),
        type: 'POST',
        success: function (data) {
            btn.closest('.comments-likes')
                .find('.likes-count')
                .text(data.review.likes_count);

            btn.closest('.comments-likes')
                .find('.dislikes-count')
                .text(data.review.dislikes_count);
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
        }
    });
});
