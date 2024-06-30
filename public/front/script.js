$(document).ready(function () {
    $('.moneyInputSpan').each(function () {
        var input = $(this).text();

        // Remove any non-digit characters (e.g., commas)
        var digits = input.replace(/\D/g, '');

        // Format the number with commas
        var formattedNumber = addCommas(digits);

        // Update the input field with the formatted number
        $(this).text(formattedNumber);
    });

    $('.moneyInput').on('input', function () {
        var input = $(this).val();

        // Remove any non-digit characters (e.g., commas)
        var digits = input.replace(/\D/g, '');

        // Format the number with commas
        var formattedNumber = addCommas(digits);

        // Update the input field with the formatted number
        $(this).val(formattedNumber);
    });

    $('.moneyInput').each(function () {
        var input = $(this).val();

        // Remove any non-digit characters (e.g., commas)
        var digits = input.replace(/\D/g, '');

        // Format the number with commas
        var formattedNumber = addCommas(digits);

        // Update the input field with the formatted number
        $(this).val(formattedNumber);
    });

    // Function to add commas as a thousands separator
    $('.activation-code-input').activationCodeInput({
        number: 5
    });
    function loadDataBasedOnScreenSize() {
        // Get the screen width
        var screenWidth = $(window).width();

        // Check if it's a mobile screen (you can adjust the breakpoint as needed)
        if (screenWidth < 1000) {
            // Load mobile-size data
            loadMobileSizeData();
        } else {
            // Load pc-size data
            loadPcSizeData();
        }
    }

    // Function to load data for pc-size
    function loadPcSizeData() {
        // Add your logic to load data for pc-size
        console.log('Loading PC-size data');
        // For example, you can make an AJAX request to fetch data
    }

    // Function to load data for mobile-size
    function loadMobileSizeData() {
        // Add your logic to load data for mobile-size
        console.log('Loading Mobile-size data');
        // For example, you can make an AJAX request to fetch data
    }

    // Call the function when the page loads and on window resize
    loadDataBasedOnScreenSize();
    $(window).resize(loadDataBasedOnScreenSize);
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
