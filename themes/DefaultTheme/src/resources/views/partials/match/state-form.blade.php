<div class="m-1 border rounded-md p-1 dir-rtl ">
    <div class="row">
        <div class="col-12 col-lg-6 col-xl-6">
            <div class="m-1">
                اسم مستعار:
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xl-6">
            <div class="m-1">
                <span id="nick_name" class="form-control"></span>
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xl-6">
            <div class="m-1">
                لینک ویدئو:
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xl-6">
            <div class="m-1">
                <input type="text" value="" name="video" class="form-control">
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xl-6">
            <div class="m-1">
                کپشن پست:
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xl-6">
            <div class="m-1">
                <textarea name="caption" class="form-control"></textarea>
            </div>
        </div>



    </div>
    <div class="">

        <h5>تصاویر پرو مجازی:</h5><br />
        <small class="text-info">برای انتخاب عکس های پرو مجازی روی عکس کلیک کنید!</small>
        <div class="d-flex flex-wrap">
            @if ($fitting)
                @foreach ($fitting as $img)
                    <div class="d-flex align-items-start" onclick="toggleSelect(this)">
                        <img src="{{ $img->photo }}" class="GalleryImage" data-photo="{{ $img->photo }}"
                            alt="Photo">
                    </div>
                    {{-- <div class="d-flex align-items-start">
                        <span><i class="mdi mdi-checkbox-blank-outline mdi-24px"></i></span>
                        <span><i class="mdi mdi-checkbox-marked mdi-24px"></i></span>
                        <img src="{{ $img->photo }}" class="GalleryImage" alt="Photo">
                    </div> --}}
                @endforeach
            @else
                <div class="alert alert-warning">
                    تصویری برای نمایش وجود ندارد
                </div>
            @endif

        </div>

    </div>
    <div class="h-4">
        <label><small>بهتر است عکس های انتخابی در یک سایز باشند!</small></label>
        <div class="dropzone dropzone-area mb-2 participant-state-images">
            <div class="dz-message h-auto">
                تصاویر را به اینجا بکشید
            </div>
        </div>
    </div>
</div>
