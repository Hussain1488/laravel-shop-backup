var table = $('.state_datatable');
var state = 'all';
var submitVar = false;
let physicalDropzone;
$(document).ready(function () {
    table.DataTable({
        searching: true,
        processing: true,
        language: {
            url: window.Laravel.datatable_fa
        },

        initComplete: function () {
            $('.dataTables_filter').append(
                '<input type="button" data-value="all"  class="btn btn-dark code-filter text-white end_date" value="همه">'
            );
            $('.dataTables_filter').append(
                '<input type="button" data-value="valid"  class="btn btn-dark code-filter text-white start_date" value="تأیید شده">'
            );
            $('.dataTables_filter').append(
                '<input type="button" data-value="not-valid"  class="btn btn-dark code-filter text-white end_date" value="رد شده">'
            );
            $('.dataTables_filter').append(
                '<input type="button" data-value="pending"  class="btn btn-dark code-filter text-white end_date" value="انتظار تأیید">'
            );

            // Add event listener to trigger search on date inputs
            $('.code-filter').on('click', function () {
                // Convert Persian dates to Gregorian before sending to server
                // alert($(this).data('value'));

                state = $(this).data('value');

                // alert(codes);
                table.DataTable().draw();
            });
        },
        ajax: {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: table.attr('action'), // Replace with the actual URL of your data endpoint
            type: 'POST',
            data: function (d) {
                d.state = state;
            }
        },

        serverSide: true,
        columnDefs: [],
        columns: [
            {data: 'counter', name: 'counter', title: '#'},
            {
                data: 'participant',
                name: 'participant',
                title: 'اسم شرکت کننده'
            },

            {
                data: 'name',
                name: 'name',
                title: 'پست',
                searchable: false
            },
            {
                data: 'like_count',
                name: 'like_count',
                title: 'تعداد لایک'
            },
            {
                data: 'dislike_count',
                name: 'dislike_count',
                title: 'تعداد دیسلایک',
                searchable: true
            },
            {
                data: 'comment_count',
                name: 'comment_count',
                title: 'تعداد نظرات',
                searchable: true
            },
            {
                data: 'state',
                name: 'state',
                title: 'وضعیت',
                render: function (data, type, row, meta) {
                    if (data == 'valid') {
                        return (
                            '<span class="badge badge-success">' +
                            'تأیید شده</span>'
                        );
                    } else if (data == 'pending') {
                        return (
                            '<span class="badge badge-warning">' +
                            'در انتضار تأیید</span>'
                        );
                    } else {
                        return (
                            '<span class="badge badge-danger">' +
                            'رد شده</span>'
                        );
                    }
                },
                searchable: true
            },
            {
                data: 'action',
                name: 'action',
                title: 'عملیات',
                render: function (data, type, row, meta) {
                    if (!row) {
                        return '<div class="loading">Loading data...</div>'; // Or any loader element
                    }
                    return (
                        '<button class="btn btn-success btn-sm state-edit-button"' +
                        'data-id="' +
                        data +
                        '"> اصلاح پست' +
                        '</button>' +
                        '<button data-id="' +
                        data +
                        '"class="btn btn-danger btn-sm delete-state-button"' +
                        ' style="margin-right:2px; margin-left:2px" >حذف</button>' +
                        '<button class="btn btn-info btn-sm state-more" data-id="' +
                        data +
                        '">بیشتر</button>'
                    );
                },

                searchable: false
            }
        ],
        // order: [[6, 'desc']],
        select: 'single',
        drawCallback: function (settings) {
            // console.log('Draw callback executed');
            // Reset the counter on each page change
            var api = this.api();
            var rows = api.rows({page: 'current'}).nodes();
            var last = null;
            var counter = api.page.info().start + 1; // Start counter from the correct number

            api.column(0, {page: 'current'})
                .data()
                .each(function (group, i) {
                    if (last !== group) {
                        counter = api.page.info().start + 1;
                    }

                    $(rows).eq(i).find('td:eq(0)').html(counter);
                    counter++;

                    last = group;
                });
        }
    });
    initializeDropzones('div.participant-images');
});
Dropzone.autoDiscover = false;

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

        $('.participant-images').sortable();

        // Now you can safely call the toArray method
        var images = $('.participant-images').sortable('toArray');

        var formData = new FormData(this);

        formData.append('images', images);
        $('#participant-state-add-modal').modal('hide');
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function (data) {
                toastr.success('پست جدید برای اشتراک کننده ثبت شد!');
                $('#add-participant-state-form')[0].reset();
                physicalDropzone.removeAllFiles();
                if (submitVar) {
                    $('.participant-id').val(data.id);
                    $('#participant-state-add-modal').modal();
                    // alert('its true!');
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

$(document).on('click', '.submit-state-add-state', function () {
    submitVar = true;
    $('#add-participant-state-form').submit();
});

$(document).on('click', '.add-participant-state-button', function () {
    let btn = $(this);
    $('#state-modal').modal('hide');
    $('.participant-id').val(btn.data('id'));
    $('.participant-name').text(btn.data('name'));
    $('.participant-username').text(btn.data('username'));
    $('.participant-phone').text(btn.data('phone'));
    $('#participant-state-add-modal').modal();
});

$(document).on('click', '.state-more', function () {
    let btn = $(this);

    $.ajax({
        url: BASE_URL + '/participant/postShow/' + btn.data('id'),
        type: 'POST',
        success: function (data) {
            $('#stat-content').empty();
            $('#stat-content').append(data);
            $('#state-show-modal').modal();
            // console.log(data);
        }
    });
});

$(document).on('click', '.delete-state-button', function () {
    let btn = $(this);
    $('#state-id').val(btn.data('id'));
    $('#state-delete-form').attr(
        'action',
        BASE_URL + '/participant/stateDelete'
    );
    $('#state-show-modal').modal('hide');
    $('#state-modal').modal('hide');
    $('#delete-modal').modal();
});

$(document).on('click', '.delete-state-submit', function (e) {
    let btn = $(this);
    e.preventDefault();
    let form = $('#state-delete-form');
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function (data) {
            console.log(data);
            $('#delete-modal').modal('hide');
            if (data.status == 'success') {
                table.DataTable().draw();
                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }
        },
        beforeSend: function () {
            block(btn);
        },
        complete: function () {
            unblock(btn);
        }
    });
});

// $('#post-delete-form').submit(function (e) {
//     e.preventDefault();

//     $('#delete-modal').modal('hide');

//     var form = this;

//     var formData = new FormData(this);

//     $.ajax({
//         url: $(this).attr('action'),
//         type: 'post',
//         data: formData,
//         success: function (data) {
//             //get current url
//             toastr.success('شرکت کننده با موفقیت حذف شد.');
//             table.DataTable().draw();
//         },
//         beforeSend: function (xhr) {
//             block('#main-card');
//             xhr.setRequestHeader(
//                 'X-CSRF-TOKEN',
//                 $('meta[name="csrf-token"]').attr('content')
//             );
//         },
//         complete: function () {
//             unblock('#main-card');
//         },
//         cache: false,
//         contentType: false,
//         processData: false
//     });
// });

$(document).on('click', '.participant-more', function () {
    let btn = $(this);
    // alert(btn.data('id'));

    $.ajax({
        url: 'participant/more/' + btn.data('id'),
        type: 'post',
        success: function (data) {
            $('.participant-stat-content').empty();
            $('.participant-stat-content').append(data);
            // console.log(data);
            $('.h_iframe-aparat_embed_frame iframe').each(function () {
                let src = $(this).data('src');
                if (src) {
                    $(this).attr('src', src);
                }
            });
            $('#state-modal').modal('show');
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error: ', status, error);
            alert('Failed to load participant data. Please try again.');
        }
    });
});
$(document).on('click', '.state-edit-button', function () {
    let btn = $(this);
    // $('#state-modal').modal('hide');
    $.ajax({
        url: BASE_URL + '/participant/postEdit/' + btn.data('id'),
        type: 'POST',
        success: function (data) {
            console.log();
            $('.stat-edit-content').empty();
            $('.stat-edit-content').append(data);
            initializeDropzones('div.participant-state-images');
            $('#state-edit-modal').modal();
        }
    });
});

function deletePhoto(button) {
    let btn = $(button);

    $.ajax({
        url: btn.data('action'),
        type: 'GET',
        data: {
            img: btn.data('img') // Send the image name
        },
        success: function (data) {
            toastr.success('عکس با موفقیت حذف شد!');
            var $container = $('div.' + btn.data('class'));
            $container.fadeOut('slow', function () {
                $(this).remove();
                // Select the parent flex container
                var $parentContainer = $('.flex-wrap');
                // Check if any images left
                if (
                    $parentContainer.find('.' + btn.data('class')).length === 0
                ) {
                    $parentContainer.animate({height: 'auto'}, 'slow');
                }
            });
        },
        error: function (xhr, status, error) {
            toastr.error('حذف عکس ناموفق بود!');
            console.log(error);
        }
    });
}
$(document).on('click', '.participant-update-button', function (e) {
    e.preventDefault();
    // $('#update-participant-state-form').submit(function (e) {

    let form = $('#update-participant-post-form');
    if (form.valid() && !form.data('disabled')) {
        if (physicalDropzone.getUploadingFiles().length) {
            toastr.error('لطفا تا اتمام آپلود تصاویر منتظر بمانید', 'خطا', {
                positionClass: 'toast-bottom-left',
                containerId: 'toast-bottom-left'
            });
            return;
        }

        $('.participant-state-images').sortable();
        let image = $('.participant-state-images').sortable('toArray');
        // $('#state-images').val(image);

        var formData = new FormData(form[0]); // Use form[0] to get the DOM element
        // alert(image);

        // Append the images array as a JSON string
        formData.append('images', image);
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false, // Prevent jQuery from automatically transforming the data into a query string
            contentType: false, // Tell jQuery not to set contentType
            success: function (data) {
                table.DataTable().draw();
                toastr.success('پست با موفقیت اصلاح شد!');
                $('#state-edit-modal').modal('hide');
                // Optionally, refresh the participant list or update the UI as needed
            },
            error: function (xhr, status, error) {
                toastr.error('خطایی رخ داده است. لطفا مجددا تلاش کنید.');
                console.error(error);
            }
        });
    }
});
function initializeDropzones(div) {
    physicalDropzone = new Dropzone(div, {
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
}
