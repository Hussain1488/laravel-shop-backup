var loading = {
    message:
        '<div class="loadingio-spinner-eclipse-mbdtacxsn3d"> ' +
        '<div class="ldio-ffthf4779sc">' +
        '<div></div>' +
        '</div>' +
        '</div>',
    css: {
        width: 'auto' /* Auto width for the blockUI container */,
        top: '50%' /* Center vertically */,
        left: '50%' /* Center horizontally */,
        transform: 'translate(-50%, -50%)' /* Center the element itself */,
        backgroundColor: 'transparent' /* No background color */,
        border: 'none' /* No border */,
        color: '#333' /* Text color */
    }
};
$('.show-history').on('click', function () {
    var btn = $(this);

    $.ajax({
        url: btn.data('action'),
        type: 'GET',
        success: function (data) {
            $('#history-detail').empty();
            $('#history-detail').append(data);
            $('#history-show-modal').modal('show');
        },
        beforeSend: function (xhr) {
            block(btn);
        },
        complete: function () {
            unblock(btn);
        }
    });
});

$('#wallet_recharg_button').on('click', function () {
    $('#rechargeForm').modal();
    // $.blockUI(loading);
    // $.ajax({
    //     url: $(this).data('url'),
    //     type: 'GET',
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     },
    //     success: function () {
    //         $.unblockUI();
    //         $('#operation_title1').text(
    //             'جهت تأیید شارژ کیف پول کد ارسال شده را وارد کرده و کلید تأیید را بزنید!'
    //         );
    //         $('#smsVarifyModal').modal();
    //         $('#code_error').addClass('d-none');
    //     }
    // });
});
// $('#sendCode').on('click', function () {
//     $.blockUI(loading);

//     let form = $('#code_varification');
//     var formData = new FormData(form[0]);

//     $.ajax({
//         url: form.attr('action'),
//         method: 'POST',
//         data: formData,
//         contentType: false,
//         processData: false,
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function () {
//             $.unblockUI();
//             $('#smsVarifyModal').modal('hide');
//             $('#rechargeForm').modal();
//         },
//         error: function (xhr) {
//             $.unblockUI();
//             $('#code_error').removeClass('d-none');
//             if (xhr.responseJSON.error) {
//                 // Display the error message in #code_error
//                 $('#code_error').text(xhr.responseJSON.error);
//             } else {
//                 // Handle other errors if needed
//                 $('#code_error').text(
//                     'کد وارده اشتباه است، لطفا کد درست را وارد کنید'
//                 );
//                 console.log('Unexpected error:', xhr);
//             }
//         }
//     });
// });
