CKEDITOR.replace('description');
$(document).ready(function () {
    // initializeDropzones('div.gallery-images');
});

$('.imageInput2').on('change', function (e) {
    // Get the selected files
    var files = e.target.files;

    if (files && files.length > 0) {
        // Clear existing images
        $('.imgContainer').empty();

        // Loop through each selected file
        for (var i = 0; i < files.length; i++) {
            // Check if the file is an image
            if (files[i].type.startsWith('image/')) {
                // If it's an image, create a FileReader to display the image
                var reader = new FileReader();

                // Set the callback function to display the image after reading
                reader.onload = function (event) {
                    // Create an image element
                    var image = $('<img>')
                        .attr('src', event.target.result)
                        .css({
                            'max-width': '100px',
                            border: '2px solid #ccc',
                            margin: '5px',
                            'box-shadow': '0 0 5px rgba(0, 0, 0, 0.3)',
                            display: 'inline'
                        });

                    // Append the image to the container
                    $('.imgContainer2').append(image);
                };

                reader.readAsDataURL(files[i]);
            } else {
                // If it's not an image, just display the file name
                var fileName = $('<span>').text(files[i].name).css({
                    border: '2px solid #ccc',
                    padding: '5px',
                    margin: '5px',
                    'box-shadow': '0 0 5px rgba(0, 0, 0, 0.3)',
                    display: 'inline-block'
                });

                // Append the file name to the container
                $('.imgContainer').append(fileName);
            }
        }
    }
});
$('.imageInput').on('change', function (e) {
    // Get the selected files
    var files = e.target.files;

    if (files && files.length > 0) {
        // Clear existing images
        $('.imgContainer').empty();

        // Loop through each selected file
        for (var i = 0; i < files.length; i++) {
            // Check if the file is an image
            if (files[i].type.startsWith('image/')) {
                // If it's an image, create a FileReader to display the image
                var reader = new FileReader();

                // Set the callback function to display the image after reading
                reader.onload = function (event) {
                    // Create an image element
                    var image = $('<img>')
                        .attr('src', event.target.result)
                        .css({
                            'max-width': '100px',
                            border: '2px solid #ccc',
                            margin: '5px',
                            'box-shadow': '0 0 5px rgba(0, 0, 0, 0.3)',
                            display: 'inline'
                        });

                    // Append the image to the container
                    $('.imgContainer').append(image);
                };

                reader.readAsDataURL(files[i]);
            } else {
                // If it's not an image, just display the file name
                var fileName = $('<span>').text(files[i].name).css({
                    border: '2px solid #ccc',
                    padding: '5px',
                    margin: '5px',
                    'box-shadow': '0 0 5px rgba(0, 0, 0, 0.3)',
                    display: 'inline-block'
                });

                // Append the file name to the container
                $('.imgContainer').append(fileName);
            }
        }
    }
});

$('#submit_button').on('click', function () {
    $('#shop-update-form').submit();
});

$('#shop-update-form').submit(function (e) {
    e.preventDefault();
    var form = $(this);

    if (form.valid() && !form.data('disabled')) {
        if (physicalDropzone.getUploadingFiles().length) {
            toastr.error('لطفا تا اتمام آپلود تصاویر منتظر بمانید', 'خطا', {
                positionClass: 'toast-bottom-left',
                containerId: 'toast-bottom-left'
            });
            return;
        }

        $('.gallery-images').sortable();

        // Now you can safely call the toArray method
        var images = $('.gallery-images').sortable('toArray');

        var formData = new FormData(form[0]);
        formData.append('images', images);
        
        formData.append(
            'description',
            CKEDITOR.instances['description'].getData()
        );

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                toastr.success('اطلاعات فروشگاه شما با موفقیت ثبت شد!');
            },
            error: function (xhr) {
                toastr.error('خطایی رخ داده است. لطفاً دوباره تلاش کنید.');
            }
        });
    }
});

physicalDropzone = new Dropzone('div.gallery-images', {
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

$('.galleryImageDeleteButton').on('click', function () {
    let btn = $(this);
    $.ajax({
        url: $(this).data('action'),
        type: 'get',
        success: function (data) {
            toastr.success('عکس با موفقیت حذف شد!');
            var $container = $('div.' + btn.data('class'));
            $container.fadeOut('slow', function () {
                $(this).remove();
                // Select the parent flex container
                var $parentContainer = $('.flex-wrap');
                // Check if any images left
                if ($parentContainer.find('.gallery_container').length === 0) {
                    $parentContainer.animate({height: 'auto'}, 'slow');
                }
            });
        }
    });
});
