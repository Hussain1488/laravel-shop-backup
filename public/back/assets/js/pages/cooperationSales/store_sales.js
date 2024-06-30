$(document).ready(function () {
    $('.monyInputSpan').each(function () {
        var input = $(this).text();
        var digits = input.replace(/\D/g, '');
        var formattedNumber = addCommas(digits);
        $(this).text(formattedNumber);
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
