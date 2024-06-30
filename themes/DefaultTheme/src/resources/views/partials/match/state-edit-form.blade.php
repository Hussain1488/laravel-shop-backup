{{-- <input type="hidden" name="state_id" value="{{ $state->id }}"> --}}
<div class="modal-content">

    <div class="modal-body modal-state-content">
        <form action="{{ route('front.participant.postUpdate', [$state->id]) }}" method="post" id="state-update-form">
            @csrf
            <div class="m-1 border rounded-md p-1 dir-rtl text-right">
                <div class="row">
                    <div class="col-12 col-lg-6 col-xl-6">
                        <div class="m-1">
                            اسم پست:
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 col-xl-6">
                        <div class="m-1">
                            <input type="text" value="{{ $state->name }}" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 col-xl-6">
                        <div class="m-1">
                            لینک ویدئو:
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 col-xl-6">
                        <div class="m-1">
                            <input type="text" value="{{ $state->video }}" name="video" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 col-xl-6">
                        <div class="m-1">
                            کپشن پست:
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 col-xl-6">
                        <div class="m-1">
                            <textarea name="caption" class="form-control">{{ $state->caption }}</textarea>
                        </div>
                    </div>



                </div>
                <div class="row">

                    <h6>تصاویر:</h6>
                    <div class="d-flex flex-wrap col-12">
                        @if ($state->photo)
                            @php
                                $photos = explode(',', $state->photo);
                            @endphp
                            @foreach ($photos as $img)
                                <div class="d-flex align-items-start gallery_container_{{ $loop->index }}">
                                    <img src="{{ $img }}" class="GalleryImage" alt="Photo">
                                    <button type="button"
                                        class="badge badge-pill badge-danger galleryImageDeleteButton"
                                        data-class="gallery_container_{{ $loop->index }}" onclick="deletePhoto(this)"
                                        data-img="{{ $img }}"
                                        data-action="{{ route('front.participant.deletePhoto', [$state->id]) }}">
                                        <i class="mdi mdi-delete-forever mdi-18px"></i>
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
                <div class="row text-right mt-1">

                    <div class="col">
                        <h6>تصاویر پرو مجازی:</h6>

                        <small class="text-info">برای انتخاب عکس های پرو مجازی روی عکس کلیک کنید!</small>
                        <div class="d-flex flex-wrap">
                            @if ($fitting)
                                @foreach ($fitting as $img)
                                    <div class="d-flex align-items-start" onclick="toggleSelect(this)">
                                        <img src="{{ $img->photo }}" class="GalleryImage"
                                            data-photo="{{ $img->photo }}" alt="Photo">
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
                <div class="h-4">
                    <label>تصاویر پست ( <small>بهترین اندازه <span
                                class="text-danger">{{ config('front.imageSizes.productGalleryImage') }}</span>
                            پیکسل میباشد.</small> )</label>
                    <div class="dropzone dropzone-area mb-2 state-edit-images">
                        <div class="dz-message h-auto">
                            تصاویر را به اینجا بکشید
                        </div>
                    </div>
                </div>
            </div>
            <input type="button" class="btn btn-primary state-update-form-submit" value="ارسال">
            <input type="button" class="btn btn-warning" value="انصراف">
        </form>
    </div>
</div>
