<div class="participant-stat m-1 border rounded-md">
    <div class="row">
        <div class="col-12 col-lg-6 col-xl-6">
            <div class="m-1">
                اسم پست:
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xl-6">
            <div class="m-1">
                <input type="text" name="name" class="form-control">
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xl-6">
            <div class="m-1">
                لینک ویدئو:
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xl-6">
            <div class="m-1">
                <input type="text" name="video" class="form-control">
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xl-6">
            <div class="m-1">
                تصاویر پست
            </div>
        </div>
        <div class="col-12 col-lg-12 col-xl-12">
            <label>تصاویر محصول ( <small>بهترین اندازه <span
                        class="text-danger">{{ config('front.imageSizes.productGalleryImage') }}</span>
                    پیکسل میباشد.</small> )</label>

            <div class="dropzone dropzone-area mb-2 participant-images" id="">

                <div class="dz-message  h-auto">تصاویر را به اینجا بکشید
                </div>
            </div>
        </div>
    </div>
</div>
