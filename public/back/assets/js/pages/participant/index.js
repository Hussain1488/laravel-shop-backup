var table = $('.participant_datatable');
var submitVar = false;
let physicalDropzone;
$(document).ready(function () {
    table.DataTable({
        searching: true,
        processing: true,
        language: {
            url: window.Laravel.datatable_fa
        },

        // initComplete: function () {
        //     // Add start_date and end_date inputs to the search input row
        //     $('.dataTables_filter').append(
        //         '<label>فیلتر از تاریخ:<input type="text" id="start_date" class="persian-date-picker" placeholder="فیلتر از تاریخ"></label>'
        //         // '<div class="col"><input type="text" id="start_date" class="form-control persian-date-picker" placeholder="فیلتر از تاریخ..."></div>'
        //     );
        //     $('.dataTables_filter').append(
        //         '<label>فیلتر تا تاریخ:<input type="text" id="end_date" class="persian-date-picker" placeholder="فیلتر تا تاریخ"></label>'
        //         // '<div class="col"><input type="text" id="end_date" class="form-control persian-date-picker" placeholder="فیلتر تا تاریخ..."></div>'
        //     );

        //     // Add event listener to trigger search on date inputs
        //     $('#start_date, #end_date').on('change', function () {
        //         // Convert Persian dates to Gregorian before sending to server
        //         var startDate = $('#start_date').val();
        //         var endDate = $('#end_date').val();

        //         $('#en_start_date').val(startDate.toEnglishDigit());
        //         $('#en_end_date').val(endDate.toEnglishDigit());

        //         $('#bank_transaction_list').DataTable().draw();
        //     });

        //     // Initialize Persian date picker
        //     $('.persian-date-picker').customPersianDate();
        // },
        ajax: {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: table.attr('action'), // Replace with the actual URL of your data endpoint
            type: 'POST'
        },

        serverSide: true,
        columnDefs: [],
        columns: [
            {data: 'counter', name: 'counter', title: '#'},
            {
                data: 'name',
                name: 'name',
                title: 'اسم اشتراک کننده'
            },

            {
                data: 'username',
                name: 'username',
                title: 'اسم مستعار',
                searchable: false
            },
            {
                data: 'post',
                name: 'post',
                title: 'پست'
            },
            {
                data: 'phone',
                name: 'phone',
                title: 'شماره تماس',
                searchable: true
            },
            {
                data: 'pending_count',
                name: 'pending_count',
                title: 'در انتظار تأیید',
                searchable: false,
                render: function (data, type, row, meta) {
                    return (
                        '<span class="badge badge-warning">' + data + '</span>'
                    );
                }
            },
            {
                data: 'valid_count',
                name: 'valid_count',
                title: 'تأیید شده',
                searchable: false,
                render: function (data, type, row, meta) {
                    return (
                        '<span class="badge badge-success">' + data + '</span>'
                    );
                }
            },
            {
                data: 'not_valid_count',
                name: 'not_valid_count',
                title: 'رد شده',
                searchable: false,
                render: function (data, type, row, meta) {
                    return (
                        '<span class="badge badge-danger">' + data + '</span>'
                    );
                }
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
                        '<button data-id="' +
                        data +
                        '" data-name="' +
                        row.name +
                        '" data-username="' +
                        row.username +
                        '" data-phone="' +
                        row.phone +
                        '" class="btn btn-success btn-sm add-participant-state-button">' +
                        'پست جدید' +
                        '</button>' +
                        '<button data-id="' +
                        data +
                        '"class="btn btn-danger btn-sm delete-participat-button"' +
                        ' style="margin-right:2px; margin-left:2px">حذف</button>' +
                        '<button class="btn btn-info btn-sm participant-more" data-id="' +
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

$(document).on('click', '.delete-participat-button', function () {
    let btn = $(this);
    // $('#participant-id').val(btn.data('id'));
    $('#post-delete-form').attr(
        'action',
        BASE_URL + '/participant/' + btn.data('id')
    );
    $('#state-modal').modal('hide');
    $('#delete-modal').modal();
});

$('#post-delete-form').submit(function (e) {
    e.preventDefault();

    $('#delete-modal').modal('hide');

    var form = this;

    var formData = new FormData(this);

    $.ajax({
        url: $(this).attr('action'),
        type: 'post',
        data: formData,
        success: function (data) {
            //get current url
            console.log(data);
            toastr.success('شرکت کننده با موفقیت حذف شد.');
            table.DataTable().draw();
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
            // $('.h_iframe-aparat_embed_frame iframe').each(function () {
            //     let src = $(this).data('src');
            //     if (src) {
            //         $(this).attr('src', src);
            //     }
            // });
            $('#state-modal').modal('show');
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error: ', status, error);
            alert('Failed to load participant data. Please try again.');
        }
    });
});
$(document).on('click', '.participant-edit-button', function () {
    let btn = $(this);
    $('#state-modal').modal('hide');
    $.ajax({
        url: btn.data('action'),
        type: 'POST',
        success: function (data) {
            // console.log();
            $('.participant-stat-content').empty();
            $('.participant-stat-content').append(data);
            initializeDropzones('div.participant-state-images');
            $('#state-modal').modal();
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
    // $('#update-participant-state-form').submit(function (e) {
    e.preventDefault();

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
        // console.log(form);
        var formData = new FormData(form[0]); // Use form[0] to get the DOM element

        // Append the images array as a JSON string
        formData.append('images', image);
        $('#state-modal').modal('hide');
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false, // Prevent jQuery from automatically transforming the data into a query string
            contentType: false, // Tell jQuery not to set contentType
            success: function (data) {
                toastr.success('پست با موفقیت اصلاح شد!');
                table.DataTable().draw();
                $('.participant-stat-content').empty();
                $('.participant-stat-content').append(data);
                $('#state-modal').modal('show');
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
