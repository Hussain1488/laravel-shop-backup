<!-- Product Info -->

<div class="col-12 button_sticky_container p-0">
    <div class="card box-card px-2 pb-1 ">
        <div class=""></div>
        <div class="dt-sl box-Price-number box-margin">
            <div class="section-title text-sm-title no-after-title-wide mb-0 dt-sl">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            @isset($shop)
                                <p class="count-comment"><span class="rate-product">امتیاز:(<i
                                            class="mdi mdi mdi-star text-warning mx-0"></i>{{ $shop->default_rating != 'none' ? $shop->default_rating : $shop->rating }}
                                        )</span>
                                </p>
                            @endisset
                            @isset($employee)
                                <p class="count-comment"><span class="rate-product">امتیاز:(<i
                                            class="mdi mdi mdi-star text-warning mx-0"></i>{{ $employee->rating }}
                                        )</span>
                                </p>
                            @endisset
                            @isset($service)
                                <p class="count-comment"><span class="rate-product">امتیاز:(<i
                                            class="mdi mdi mdi-star text-warning mx-0"></i>{{ $service->rating }}
                                        )</span>
                                </p>
                            @endisset
                            <button data-toggle="modal" data-target="#add-shop-review-modal"
                                class="btn-info-cm bg-info btn-with-icon mb-2 w-50 add-shop-review-modal-button">
                                <i class="mdi mdi-comment-text-outline "></i>
                                {{ trans('front::messages.reviews.add-review') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
