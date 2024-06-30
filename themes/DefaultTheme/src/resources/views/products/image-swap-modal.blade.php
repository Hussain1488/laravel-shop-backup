<div class="modal fade" id="image-swap-modal">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel19">فرم ارسال عکس پرو مجازی</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" id="image-swap-form">
                <div class="alert alert-danger">برای پرو مجازی شما مجاز خواهید بود در روز فقط سه درخواست بفرستید!
                </div>
                <div class="row p-3 d-flex justify-content-center">
                    <div class="col-10 col-md-5 col-lg-5 p-2 d-flex align-items-center justify-content-center">
                        <div class="p-2">
                            <input type="hidden" class="target_image" name="target_image" value="">
                            <input type="hidden" class="product_image" name="image" value="">
                            <img src="" class="target_image w-100" alt="">
                        </div>
                    </div>

                    <div class="col-12 col-md-2 d-flex align-items-center justify-content-center">
                        <i class="mdi mdi-plus-box-outline mdi-36px"></i>
                    </div>

                    <div class="col-10 col-md-5 col-lg-5 p-2 d-flex align-items-center justify-content-center">
                        <div class="w-100">

                            <div class="dropzone dropzone-area mb-2 swap_image">
                                <div class="dz-message h-auto">
                                    تصاویر را به اینجا بکشید
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="flex p-1 justify-content-center row">
                    <div class="col">

                        <input data-action="{{ route('front.faceSwap.createJob') }}" type="button"
                            class="image-swap-submit-button btn btn-success w-100 btn-sm" value="ارسال">
                    </div>
                    <div class="col">
                        <button type="button" class="btn btn-danger w-100 btn-sm" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">×</span> انصراف
                        </button>
                        {{-- <input type="button" data-dismis="modal" class="btn btn-danger w-100 btn-sm" value="انصراف"> --}}
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
