@isset($post)
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel19">لیست پست های تعریف شده برای {{ $post->participant->name }}
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body modal-state-content">
            <div class="row my-2 p-1">
                <div class="col-12 col-md-6 col-lg-3">
                    اسم اشتراک کننده:
                </div>
                <div class="col-12 col-md-6 col-lg-3 alert alert-success">
                    {{ $post->participant->name }}
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    تاریخ ثبت:
                </div>
                <div class="col-12 col-md-6 col-lg-3 alert alert-success">
                    {{ Jdate($post->participant->created_at)->format('Y/m/d') }}
                </div>
            </div>
            <div class="tab-content ">
                <div class="row my-1">
                    <div class="col-4 col-md-3 col-lg-2">
                        اسم پست:
                    </div>
                    <div class="col">
                        {{ $post->name }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 my-1">
                        <h2>گپشن:</h2>

                        <div class="">
                            {{ $post->caption }}

                        </div>
                    </div>

                    <div class="col-12 my-1">
                        <h2>تصاویر:</h2>
                        <div class="row">
                            @if ($post->photo)
                                @php
                                    $photos = explode(',', $post->photo);
                                @endphp
                                @foreach ($photos as $img)
                                    <div class="d-flex flex-wrap">
                                        <div class="d-flex align-items-start">
                                            <img src="{{ $img }}" class="GalleryImage" alt="Photo">
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-warning">
                                    تصویری برای نمایش وجود ندارد
                                </div>
                            @endif


                        </div>
                    </div>



                </div>
            </div>
        </div>
        <div class="modal-footer dir-ltr">
            <button data-id="{{ $post->id }}"class="btn btn-outline-danger btn-sm delete-state-button"
                style="margin-right:2px; margin-left:2px">حذف پست</button>

        </div>

    </div>
@else
    <div class="d-flex justify-content-center align-items-center text-center">
        <div class="alert alert-warning">
            پستی برای این شرکت کننده وجود ندارد!
        </div>
    </div>
@endisset
