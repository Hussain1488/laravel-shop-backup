<!-- Product Info -->

<div class="col-lg-8 p-3 p-lg-5 mt-3 col-md-12 pb-5 product-info-block ">
    <div class="product-info dt-sl">
        <div class="product-title">
            <h1>مشخصات</h1>
            {{-- <h3 class="mb-1">{{ $shop->title_en }}</h3> --}}
        </div>
        <div class="row p-2">
            <div class="col-md-12 col-lg-12 p-2">
                <hr class="border-product-title">
                @isset($employee)
                    <div class="row mt-2">
                        @if ($employee->rating)
                            <div class="col-12 d-flex">
                                <div class="d-flex">
                                    <i class="mdi mdi mdi-star text-warning mx-0"></i>
                                    <p class="mx-1 mb-2">{{ $employee->rating }}</p>
                                </div>

                            </div>
                        @endif

                    </div>
                    <div class="row d-flex flex-wrap">
                        <div class="col col-4 mt-3">
                            <h6>
                                اسم مجموعه:
                            </h6>
                        </div>
                        <div class="col col-6 mt-3">
                            <h6>
                                {{ $employee->shop->nameofstore }}
                            </h6>
                        </div>
                        <div class="col col-4 mt-3">
                            <h6>
                                اسم پرسنل:
                            </h6>
                        </div>
                        <div class="col col-6 mt-3">
                            <h6>
                                {{ $employee->full_name }}
                            </h6>
                        </div>
                        <div class="col col-4 mt-3">
                            <h6>
                                سمت پرسنل:
                            </h6>
                        </div>
                        <div class="col col-6 mt-3">
                            <h6>
                                {{ $employee->role }}
                            </h6>
                        </div>
                        <div class="col col-4 mt-3">
                            <h6>
                                عنوان پرسنل:
                            </h6>
                        </div>
                        <div class="col col-6 mt-3">
                            <h6>
                                {{ $employee->title }}
                            </h6>
                        </div>
                    </div>
                @endisset
                @isset($service)
                    <div class="row mt-2">
                        @if ($service->rating)
                            <div class="col-12 d-flex">
                                <div class="d-flex">
                                    <i class="mdi mdi mdi-star text-warning mx-0"></i>
                                    <p class="mx-1 mb-2">{{ $service->rating }}</p>
                                </div>

                            </div>
                        @endif

                    </div>
                    <div class="row d-flex flex-wrap">
                        <div class="col col-4 mt-3">
                            <h6>
                                اسم مجموعه:
                            </h6>
                        </div>
                        <div class="col col-6 mt-3">
                            <h6>
                                {{ $service->shop->nameofstore }}
                            </h6>
                        </div>
                        <div class="col col-4 mt-3">
                            <h6>
                                اسم امکانات:
                            </h6>
                        </div>
                        <div class="col col-6 mt-3">
                            <h6>
                                {{ $service->name }}
                            </h6>
                        </div>
                        <div class="col col-4 mt-3">
                            <h6>
                                سمت امکانات:
                            </h6>
                        </div>
                        <div class="col col-6 mt-3">
                            <h6>
                                {{ $service->role }}
                            </h6>
                        </div>
                        <div class="col col-4 mt-3">
                            <h6>
                                عنوان امکانات:
                            </h6>
                        </div>
                        <div class="col col-6 mt-3">
                            <h6>
                                {{ $service->title }}
                            </h6>
                        </div>
                    </div>
                @endisset
            </div>

        </div>

    </div>
</div>
