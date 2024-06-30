Dropzone.autoDiscover = false;
var physicalDropzone;
var post = 0,
    step = 0;
var selectedImages = [];
const nextButton = $('#next');
$(document).ready(function () {
    // alert('hey you!!');
});

function swiper() {
    new Swiper('.mySwiper', {
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        }
    });
}

$(document).on('change', '.post-id', function () {
    step = 0;
    selectedImages = [];
});

function participantFormSubmit() {
    const form = document.getElementById('participantForm');
    const formData = new FormData(form);
    finalShow(5);
}
$('.fitting-image-download').on('click', function () {
    let btn = $(this);
    $.ajax({
        url: btn.data('action'),
        method: 'POST',

        success: function (response) {
            console.log(response);
            var link = document.createElement('a');
            link.href = 'data:image/png;base64,' + response.image;
            link.download = 'watermarked-image.png';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },
        error: function (xhr, status, error) {
            console.error('An error occurred:', status, error);
        },
        beforeSend: function (xhr) {
            // block('#main-card');
            xhr.setRequestHeader(
                'X-CSRF-TOKEN',
                $('meta[name="csrf-token"]').attr('content')
            );
        }
    });
});

$(document).on('click', '.fittin-link-copy-button', function () {
    let btn = $(this);
    const dataToCopy = btn.data('link');
    navigator.clipboard.writeText(dataToCopy);
    toastr.success('لینک با موفقیت کپی شد!');
});

function toggleSelect(element) {
    const img = element.querySelector('img');
    img.classList.toggle('selected');
    // console.log(img);
    // console.log(img.getAttribute('src'));

    const imgSrc = img.getAttribute('src');
    const index = selectedImages.indexOf(imgSrc);

    if (index === -1) {
        // Image was not selected, push it to the array
        selectedImages.push(imgSrc);
    } else {
        // Image was selected, remove it from the array
        selectedImages.splice(index, 1);
    }

    // console.log(selectedImages); // Log the array of selected image srcs
}

function InitializeDropzone(div) {
    physicalDropzone = new Dropzone('div.' + div, {
        url: BASE_URL + '/products/image-store',
        addRemoveLinks: true,
        acceptedFiles: 'image/*',

        dictInvalidFileType: 'آپلود فایل با این فرمت ممکن نیست',
        dictRemoveFile: 'حذف',
        dictCancelUpload: 'لغو آپلود',
        dictResponseError: 'خطایی در بارگذاری فایل رخ داده است',

        //
        init: function () {
            // Initially disable the next button

            // Event when file is added and sending
            this.on('sending', function (file, xhr, formData) {
                // Disable the button before sending the file
                if (nextButton) {
                    nextButton.disabled = true;
                }
                console.log(nextButton);
            });

            // Event when upload is successful
            this.on('success', function (file, response) {
                file.upload.filename = response.imagename;
                console.log('true');

                $(file.previewElement).data('name', response.imagename);
                $(file.previewElement).attr('id', response.imagename);
            });

            // Event when upload is complete (either success or error)
            this.on('complete', function (file) {
                // Enable the button again
                if (nextButton) {
                    nextButton.disabled = false;
                }
                console.log(nextButton);
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

function initializeStepper() {
    const progress = document.querySelector('#progress');
    const submit = document.querySelector('#submit');
    const next = document.querySelector('#next');
    const prev = document.querySelector('#prev');
    const circles = document.querySelectorAll('.circle');
    const steps = document.querySelectorAll('.stepper-content');

    let currentActive = 1;
    console.log(currentActive);

    next.addEventListener('click', async () => {
        step++;
        currentActive++;
        console.log(circles.length, currentActive);

        if (currentActive > circles.length) {
            currentActive = circles.length;
        }
        if (currentActive == 2 || currentActive == 3) {
            // Fetch data for the next tab
            console.log($('.post-id').val() == post && step < currentActive);
            console.log($('.post-id').val() == post);
            console.log(step < currentActive);
            try {
                if (step < currentActive) {
                    const data = await fetchDataForTab(currentActive);

                    // Update the content of the next tab

                    steps[currentActive - 1].innerHTML = data;
                    if (currentActive == 3) {
                        InitializeDropzone('participant-state-images');
                    }
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        } else if (currentActive == 4) {
            // Fetch data for the next tab
            try {
                const data = await finalShow(currentActive);
                // Update the content of the next tab
                steps[currentActive - 1].innerHTML = data;
                captionMore('.show-more');
                swiper();
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }
        update();
    });

    prev.addEventListener('click', () => {
        currentActive--;

        if (currentActive < 1) {
            currentActive = 1;
        }

        update();
    });

    function update() {
        circles.forEach((circle, idx) => {
            if (idx < currentActive) {
                circle.classList.add('stepper-active');
            } else {
                circle.classList.remove('stepper-active');
            }
        });

        steps.forEach((step, idx) => {
            if (idx === currentActive - 1) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });

        const actives = document.querySelectorAll('.stepper-active');

        progress.style.width =
            ((actives.length - 1) / (circles.length - 1)) * 100 + '%';

        if (currentActive == 1) {
            prev.disabled = true;
            submit.disabled = true;
        } else if (circles.length == currentActive) {
            submit.disabled = false;
            next.disabled = true;
        } else {
            next.disabled = false;
            prev.disabled = false;
        }
    }

    // Initialize the stepper on load
    update();
}

function fetchDataForTab(tabIndex) {
    let post_id = $('.post-id').val();
    post = post_id;

    // alert(BASE_URL + '/participant/participateGetdata/' + tabIndex);
    return new Promise((resolve, reject) => {
        $.ajax({
            url: BASE_URL + '/participant/participateGetdata', // Adjust the URL to your endpoint
            type: 'post',
            data: {
                id: tabIndex,
                post_id: post_id
            },
            success: function (data) {
                // console.log(data);
                resolve(data);
            },
            error: function (xhr, status, error) {
                console.error(`Error fetching data: ${status}, ${error}`);
                reject(xhr);
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                    'X-CSRF-TOKEN',
                    $('meta[name="csrf-token"]').attr('content')
                );
            }
        });
    });
}

function finalShow(tabIndex) {
    // Check if there are any uploading files
    if (physicalDropzone.getUploadingFiles().length) {
        toastr.error('لطفا تا اتمام آپلود تصاویر منتظر بمانید', 'خطا', {
            positionClass: 'toast-bottom-left',
            containerId: 'toast-bottom-left'
        });
        return;
    }

    let post_id = $('.post-id').val();
    const form = document.getElementById('participantForm');
    const formData = new FormData(form);
    formData.append('id', tabIndex);
    formData.append('post_id', post_id);
    formData.append('photos', selectedImages);

    // Log formData for debugging purposes

    // Initialize sortable and get the array of sorted image IDs
    // console.log($('.participant-state-images'));
    $('.participant-state-images').sortable();

    var images = $('.participant-state-images').sortable('toArray');

    formData.append('images', images);

    return new Promise((resolve, reject) => {
        $.ajax({
            url: BASE_URL + '/participant/participateGetdata', // Adjust the URL to your endpoint
            type: 'post',
            processData: false, // Important: prevent jQuery from processing the data
            contentType: false, // Important: prevent jQuery from setting content type
            data: formData,
            success: function (data) {
                if (data.message && data.result) {
                    toastr.success(data.message);
                    selectedImages = [];
                    $('#match-participation-modal').modal('hide');
                } else if (data.result == false) {
                    toastr.error(data.message);
                }
                resolve(data);
            },
            error: function (xhr, status, error) {
                console.error(`Error fetching data: ${status}, ${error}`);
                reject(xhr);
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                    'X-CSRF-TOKEN',
                    $('meta[name="csrf-token"]').attr('content')
                );
            }
        });
    });
}

$(document).on('click', '.match-participation', function () {
    let btn = $(this);

    $.ajax({
        url: btn.data('action'),
        type: 'post',
        beforeSend: function (xhr) {
            xhr.setRequestHeader(
                'X-CSRF-TOKEN',
                $('meta[name="csrf-token"]').attr('content')
            );
        },
        success: function (data) {
            post = 0;
            step = 0;
            $('.match-participation-modal-content').empty();
            $('.match-participation-modal-content').append(data);
            initializeStepper();
            $('#match-participation-modal').modal();
        }
    });
});

function captionMore(button) {
    document.querySelectorAll(button).forEach(function (element) {
        element.addEventListener('click', function () {
            const parent = this.parentElement;
            parent.querySelector('.truncated').classList.toggle('d-none');
            parent.querySelector('.full-comment').classList.toggle('d-none');
            this.classList.toggle('d-none');
        });
    });
}
$(document).on('click', '.participant-more', function () {
    let btn = $(this);
    // alert(btn.data('id'));

    $.ajax({
        url: btn.data('action'),
        type: 'post',
        success: function (data) {
            $('.match-participation-modal-content').empty();
            $('.match-participation-modal-content').append(data);
            $('#match-participation-modal').modal('show');
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error: ', status, error);
            alert('Failed to load participant data. Please try again.');
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader(
                'X-CSRF-TOKEN',
                $('meta[name="csrf-token"]').attr('content')
            );
        }
    });
});
$(document).on('click', '.participant-edit-button', function () {
    let btn = $(this);
    // alert(btn.data('id'));

    $('#match-participation-modal').modal('hide');
    $.ajax({
        url: btn.data('action'),
        type: 'post',
        success: function (data) {
            console.log(data);
            $('.match-participation-modal-content').empty();
            $('.match-participation-modal-content').append(data);
            InitializeDropzone('state-edit-images');
            $('#match-participation-modal').modal('show');
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error: ', status, error);
            alert('Failed to load participant data. Please try again.');
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader(
                'X-CSRF-TOKEN',
                $('meta[name="csrf-token"]').attr('content')
            );
        }
    });
});

$(document).on('click', '.state-update-form-submit', function () {
    let form = $('#state-update-form');

    if (physicalDropzone.getUploadingFiles().length) {
        toastr.error('لطفا تا اتمام آپلود تصاویر منتظر بمانید', 'خطا', {
            positionClass: 'toast-bottom-left',
            containerId: 'toast-bottom-left'
        });
        return;
    }

    if (!form.length) {
        console.error('Form not found');
        return;
    }

    const formData = new FormData(form[0]); // Use form[0] to get the HTMLFormElement
    formData.append('photos', selectedImages);

    // Log formData for debugging purposes
    for (let pair of formData.entries()) {
        console.log(pair[0] + ', ' + pair[1]);
    }

    // Initialize sortable and get the array of sorted image IDs
    $('.state-edit-images').sortable();

    var images = $('.state-edit-images').sortable('toArray');

    formData.append('images', images);

    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: formData,
        processData: false, // Prevent jQuery from automatically transforming the data into a query string
        contentType: false, // Tell jQuery not to set the Content-Type header
        success: function (response) {
            selectedImages = [];
            $('#match-participation-modal').modal('hide');
            if (response == 'success') {
                toastr.success('پست با موفقیت اصلاح شد!');
            } else {
                toastr.error('خطایی رخ داده است!');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error(
                'Error during AJAX request:',
                textStatus,
                errorThrown
            );
        }
    });
});

function deletePhoto(button) {
    let btn = $(button);

    $.ajax({
        url: btn.data('action'),
        type: 'post',
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
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader(
                'X-CSRF-TOKEN',
                $('meta[name="csrf-token"]').attr('content')
            );
        }
    });
}

/* config dropzone uploader for uploading images */
