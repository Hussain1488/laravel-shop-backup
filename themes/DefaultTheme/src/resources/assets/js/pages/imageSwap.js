Dropzone.autoDiscover = false;

$(document).ready(function () {});

$(document).on('click', '.image-target-swap', function () {
    let image = $(this);
    $('.target_image').attr('src', image.attr('src'));
    $('.target_image').val(image.attr('src'));
    $('.product_image').val(image.data('id'));

    // Destroy any existing Dropzone instance
    if (Dropzone.instances.length > 0) {
        Dropzone.instances.forEach((dz) => dz.destroy());
    }

    // Initialize Dropzone
    var physicalDropzone = new Dropzone('div.swap_image', {
        url: BASE_URL + '/products/image-store',

        addRemoveLinks: true,
        acceptedFiles: 'image/*',
        dictInvalidFileType: 'آپلود فایل با این فرمت ممکن نیست',
        dictRemoveFile: 'حذف',
        dictCancelUpload: 'لغو آپلود',
        dictResponseError: 'خطایی در بارگذاری فایل رخ داده است',
        maxFiles: 1,

        init: function () {
            this.on('success', function (file, response) {
                file.upload.filename = response.imagename;
                $(file.previewElement).data('name', response.imagename);
                $(file.previewElement).attr('id', response.imagename);
            });
        },
        removedfile: function (file) {
            var name = file.upload.filename;

            if (file.accepted) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content'
                        )
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

    $('#image-swap-modal').modal('show');
});

$(document).on('click', '.image-swap-submit-button', function () {
    let btn = $(this);
    let form = $('#image-swap-form')[0]; // Get the HTML form element

    // Create a new FormData object from the form
    var formData = new FormData(form);

    // Get the images
    $('.swap_image').sortable();
    let images = $('.swap_image').sortable('toArray');
    // console.log(images);

    // Append images data to the FormData object
    formData.append('images', images);

    // Make AJAX request
    $.ajax({
        url: btn.data('action'),
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false, // Prevent jQuery from automatically processing the data
        contentType: false, // Prevent jQuery from setting content type
        success: function (data) {
            if (data.status) {
                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }
            console.log(data);
            // Handle success
        }
    });
});
