@isset($post)

    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel19">فرم اصلاح وضعیت</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row mb-1">
                <div class="col-4 ">
                    اسم شرکت کننده:
                </div>
                <div class="col-6">
                    {{ $post->participant->name }}
                </div>

            </div>
            <div class="row mx-1">
                <div class="col-4">
                    اسم مستعار شرکت کننده:
                </div>
                <div class="col-6">
                    {{ $post->participant->username }}
                </div>
            </div>
            <div class="row m-1">
                <div class="col-4">
                    شماره شرکت کننده:
                </div>
                <div class="col-6">
                    {{ $post->participant->phone }}
                </div>
            </div>

            <form action="{{ route('admin.participant.postUpdate') }}" method="post" id="update-participant-post-form">
                @csrf
                <input type="hidden" name="stat_id" value="{{ $post->id }}" class="participant-id">
                <input type="hidden" id="state-images" name="images" value="">
                <div class="participant-stat border rounded-md p-1">
                    <div class="row">
                        <div class="col-12 col-lg-6 col-xl-6">
                            <div class="m-1">
                                اسم پست:
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-xl-6">
                            <div class="m-1">
                                <input type="text" value="{{ $post->name }}" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-xl-6">
                            <div class="m-1">
                                لینک ویدئو:
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-xl-6">
                            <div class="m-1">
                                <input type="text" value="{{ $post->video }}" name="video" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-xl-6">
                            <div class="m-1">
                                کپشن پست:
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-xl-6">
                            <div class="m-1">
                                <textarea name="caption" class="form-control">{{ $post->caption }}</textarea>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-xl-6">
                            <div class="m-1">
                                پست:
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-xl-6">
                            <div class="m-1">
                                <select name="state" id="" class="form-control">
                                    <option value="valid" {{ $post->state == 'valid' ? 'selected' : '' }}>تأیید</option>
                                    <option value="pending" {{ $post->state == 'pending' ? 'selected' : '' }}>انتظار
                                    </option>
                                    <option value="not-valid" {{ $post->state == 'not-valid' ? 'selected' : '' }}>رد
                                    </option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row my-1">

                        <h2>تصاویر:</h2>
                        <div class="d-flex flex-wrap col-12">
                            @if ($post->photo)
                                @php
                                    $photos = explode(',', $post->photo);
                                @endphp
                                @foreach ($photos as $img)
                                    <div class="d-flex align-items-start gallery_container_{{ $loop->index }}">
                                        <img src="{{ $img }}" class="GalleryImage" alt="Photo">
                                        <button type="button"
                                            class="badge badge-pill badge-danger galleryImageDeleteButton"
                                            data-class="gallery_container_{{ $loop->index }}" onclick="deletePhoto(this)"
                                            data-img="{{ $img }}"
                                            data-action="{{ route('admin.participant.deletePhoto', [$post->id]) }}">
                                            <i class="feather icon-trash"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-warning">
                                    تصویری برای نمایش وجود ندارد
                                </div>
                            @endif

                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-12 col-xl-12">
                            <label> بهتر است که تمامی عکس ها به یک اندازه باشند.
                            </label>

                            <div class="dropzone dropzone-area participant-state-images">
                                <div class="dz-message h-auto">
                                    تصاویر را به اینجا بکشید
                                </div>
                            </div>
                        </div>
                    </div>


                </div>


            </form>
        </div>
        <div class="modal-footer ">
            <button type="button" class="btn btn-success waves-effect waves-light participant-update-button">ثبت</button>
            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal">انصراف</button>
        </div>
    </div>
@else
    <div class="d-flex justify-content-center align-items-center text-center">
        <div class="alert alert-warning">
            پستی برای این شرکت کننده وجود ندارد!
        </div>
    </div>
@endisset
