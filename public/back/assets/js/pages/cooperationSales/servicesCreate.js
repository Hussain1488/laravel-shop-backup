CKEDITOR.replace('description');
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

$('.galleryImageDeleteButton').on('click', function () {
    $.ajax({
        url: $(this).data('action'),
        type: 'get',
        success: function (data) {
            toastr.success('عکس با موفقیت حذف شد!');
            $('div.' + $(this).data('class')).remove();
        }
    });
});
