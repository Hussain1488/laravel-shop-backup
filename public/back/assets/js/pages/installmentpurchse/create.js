$(document).ready(function() {
    // Listen for input in the text field
    $(".moneyInput").on("input", function() {
      var input = $(this).val();

      // Remove any non-digit characters (e.g., commas)
      var digits = input.replace(/\D/g, "");

      // Format the number with commas
      var formattedNumber = addCommas(digits);

      // Update the input field with the formatted number
      $(this).val(formattedNumber);
    });

    // Function to add commas as a thousands separator
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
  });

$('.nav-tabs a').click(function(){
    $(this).tab('show');
    // console.log('this is a test')
  })

  // Select tab by name
  $('.nav-tabs a[href="#home"]').tab('show')

  // Select first tab
  $('.nav-tabs a:first').tab('show')

  // Select last tab
  $('.nav-tabs a:last').tab('show')

  // Select fourth tab (zero-based)
  $('.nav-tabs li:eq(3) a').tab('show')


