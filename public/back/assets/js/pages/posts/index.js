var submitVar = false;

$(document).on('click', '.btn-delete', function () {
    $('#post-delete-form').attr(
        'action',
        BASE_URL + '/posts/' + $(this).data('post')
    );
    $('#post-delete-form').data('id', $(this).data('id'));
});

$('#post-delete-form').submit(function (e) {
    e.preventDefault();

    $('#delete-modal').modal('hide');

    var form = this;

    var formData = new FormData(this);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function (data) {
            //get current url
            var url = window.location.href;

            //remove post tr
            $('#post-' + $(form).data('id') + '-tr').remove();

            toastr.success('پست با موفقیت حذف شد.');

            //refresh posts list
            $('.app-content').load(url + ' .app-content > *');
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

$('#add-participant-form').submit(function (e) {
    e.preventDefault();

    // $('#delete-modal').modal('hide');
    $('#participant-add-modal').modal('hide');

    var form = this;

    var formData = new FormData(this);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function (data) {
            console.log(data);
            toastr.success('اشتراک کننده با موفقیت ایجاد شد.');
            $('#add-participant-form')[0].reset();
            physicalDropzone.removeAllFiles();
            if (submitVar) {
                $('.participant-name').text(data.name);
                $('.participant-username').text(data.username);
                $('.participant-phone').text(data.phone);
                $('.participant-id').val(data.id);
                $('#participant-state-add-modal').modal();
            }
            submitVar = false;
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

$('.submit-add-state').on('click', function () {
    submitVar = true;
    $('#add-participant-form').submit();
});

$(document).on('click', '.add-participant', function () {
    let btn = $(this);
    $('.post-name').text(btn.data('name'));
    $('.post-id').val(btn.data('id'));
    $('#participant-add-modal').modal();
});

var physicalDropzone = new Dropzone('div.participant-images', {
    url: BASE_URL + '/products/image-store',
    addRemoveLinks: true,
    acceptedFiles: 'image/*',

    dictInvalidFileType: 'آپلود فایل با این فرمت ممکن نیست',
    dictRemoveFile: 'حذف',
    dictCancelUpload: 'لغو آپلود',
    dictResponseError: 'خطایی در بارگذاری فایل رخ داده است',

    init: function () {
        this.on('success', function (file, response) {
            file.upload.filename = response.imagename;

            $(file.previewElement).data('photo', response.imagename);
            $(file.previewElement).attr('id', response.imagename);
        });
    },

    removedfile: function (file) {
        var name = file.upload.filename;

        if (file.accepted) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: BASE_URL + '/products/image-delete',
                data: {filename: name},
                success: function (data) {
                    // console.log("File has been successfully removed!!");
                },
                error: function (e) {
                    // console.log(e);
                }
            });
        }

        var fileRef;
        return (fileRef = file.previewElement) != null
            ? fileRef.parentNode.removeChild(file.previewElement)
            : void 0;
    },

    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('click', '.submit-state-add-state', function () {
    submitVar = true;
    $('#add-participant-state-form').submit();
});

$('#add-participant-state-form').submit(function (e) {
    e.preventDefault();

    if ($(this).valid() && !$(this).data('disabled')) {
        if (physicalDropzone.getUploadingFiles().length) {
            toastr.error('لطفا تا اتمام آپلود تصاویر منتظر بمانید', 'خطا', {
                positionClass: 'toast-bottom-left',
                containerId: 'toast-bottom-left'
            });
            return;
        }
        $('#participant-state-add-modal').modal('hide');
        $('.dropzone-area').sortable();
        // Now you can safely call the toArray method
        var images = $('.dropzone-area').sortable('toArray');
        var formData = new FormData(this);
        formData.append('images', images);
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function (data) {
                // console.log('data is here' + data);
                toastr.success('پست جدید برای اشتراک کننده ثبت شد!');
                $('#add-participant-state-form')[0].reset();
                physicalDropzone.removeAllFiles();
                if (submitVar) {
                    $('.participant-id').val(data.id);
                    $('#participant-state-add-modal').modal();
                    submitVar = false;
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
    }
});
