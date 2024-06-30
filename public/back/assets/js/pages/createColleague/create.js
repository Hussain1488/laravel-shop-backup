$(document).ready(function () {
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


    $('#User_selected').change(function () {
        var selectedOption = $(this).find(':selected');
        var creditAttrValue = selectedOption.attr('credit_attr_value');
        // console.log(creditAttrValue);
        // var orginal_value = selectedOption.attr('creadit_attr');

        if (creditAttrValue == '') {
            creditAttrValue = 0;
        }
        var formattedNumber = addCommas(creditAttrValue);
        $('#inventory').val(formattedNumber);
        $('#Creadit_hidden').val(creditAttrValue);
    });

    $('.monyInputSpan').each(function () {
        var input = $(this).text();
        var digits = input.replace(/\D/g, '');
        var formattedNumber = addCommas(digits);
        $(this).text(formattedNumber);
    });
});

function addCommas(nStr) {
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

$('#user_selection').change(function () {
    let selectedOption = $(this).find(':selected');
    let name = selectedOption.attr('data-name');
    let lastname = selectedOption.attr('data-lastname');
    $('#user_title').text('کاربر:' + ' ');
    $('#user_name').text(name + ' ' + lastname);
    // console.log(name + ' ' + lastname);
});
