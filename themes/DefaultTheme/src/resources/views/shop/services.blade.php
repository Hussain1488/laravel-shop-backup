@extends('front::layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ theme_asset('css/vendor/fancybox.min.css') }}">
@endpush

@section('content')
    <!-- Start main-content -->
    @isset($employee)
        <main class="main-content dt-sl mt-4 mb-3">
            <div class="container main-container">
                <!-- Start title - breadcrumb -->
                <div class="title-breadcrumb-special dt-sl mb-3">
                    <div class="breadcrumb dt-sl">
                        <nav>
                            <a href="{{ route('front.index') }}">{{ trans('front::messages.products.home') }}</a>
                            <a href="{{ route('front.shops.index') }}">مجموعه: {{ $employee->shop->nameofstore }}</a>
                            {{ $employee ? 'پرسنل: ' : 'امکانات:‌ ' }}
                            <span>{{ $employee->full_name }}</span>
                        </nav>
                    </div>
                </div>
                <!-- End title - breadcrumb -->

                <!-- Start Product -->
                <div class="dt-sn mb-3 dt-sl">
                    <div class="row">
                        <!-- Product Gallery-->
                        <div class="col-lg-4 col-md-12 ps-relative ">
                            <div class="product-timeout position-relative pt-5 mb-4">
                                <div class="promotion-badge">

                                </div>

                            </div>
                            </ul>

                            @if ($employee)
                                <div class="product-gallery mt-3">
                                    <div class="product-carousel owl-carousel" style="max-width:300px; max-height:450px">
                                        <div class="item">
                                            <a class="gallery-item mt-3" href="{{ asset($employee->photo) }}"
                                                data-fancybox="gallery" data-owl="">
                                                <img src="{{ asset('images/600-600.png') }}"
                                                    data-src="{{ asset($employee->photo) }}" alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <hr class="border-product" />
                                </div>
                            @endif

                        </div>
                        @include('front::shop.partials.services-info')
                        @include('front::shop.partials.services-description')

                    </div>
                </div>

                <div class="dt-sn mb-3 px-0 dt-sl pt-0">
                    <!-- Start tabs -->
                    <section class="tabs-product-info mb-3 dt-sl">
                        <div class="ah-tab-wrapper dt-sl">
                            <div class="ah-tab dt-sl">


                                <a class="ah-tab-item "data-ah-tab-active='true' href="javascript:void(0)"><i
                                        class="mdi mdi-comment-text-multiple-outline"></i>نظرات</a>

                            </div>
                        </div>

                        <div class="ah-tab-content-wrapper active shop-info px-4 dt-sl">

                            @include('front::shop.partials.reviews')




                            <div class="ah-tab-content dt-sl">

                            </div>
                        </div>

                    </section>
                    <!-- End tabs -->
                </div>
                <!-- End Product -->



            </div>
        </main>
    @endisset
    @isset($service)
        <main class="main-content dt-sl mt-4 mb-3">
            <div class="container main-container">
                <!-- Start title - breadcrumb -->
                <div class="title-breadcrumb-special dt-sl mb-3">
                    <div class="breadcrumb dt-sl">
                        <nav>
                            <a href="{{ route('front.index') }}">{{ trans('front::messages.products.home') }}</a>
                            <a href="{{ route('front.shops.index') }}">مجموعه: {{ $service->shop->nameofstore }}</a>
                            {{ $service ? 'پرسنل: ' : 'امکانات:‌ ' }}
                            <span>{{ $service->name }}</span>
                        </nav>
                    </div>
                </div>
                <!-- End title - breadcrumb -->

                <!-- Start Product -->
                <div class="dt-sn mb-3 dt-sl">
                    <div class="row">
                        <!-- Product Gallery-->
                        <div class="col-lg-4 col-md-12 ps-relative">
                            <div class="product-timeout position-relative pt-5 mb-4">
                                <div class="promotion-badge">

                                </div>

                            </div>
                            </ul>
                            @if ($service)
                                <div class="product-gallery mt-3">
                                    <div class="product-carousel owl-carousel" style="max-width:300px; max-height:450px">
                                        <div class="item">
                                            <a class="gallery-item mt-3" href="{{ asset($service->photo) }}"
                                                data-fancybox="gallery" data-owl="">
                                                <img src="{{ asset('images/600-600.png') }}"
                                                    data-src="{{ asset($service->photo) }}" alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <hr class="border-product" />
                                </div>
                            @endif

                        </div>
                        @include('front::shop.partials.services-info')
                        @include('front::shop.partials.services-description')
                    </div>
                </div>

                <div class="dt-sn mb-3 px-0 dt-sl pt-0">
                    <!-- Start tabs -->
                    <section class="tabs-product-info mb-3 dt-sl">
                        <div class="ah-tab-wrapper dt-sl">
                            <div class="ah-tab dt-sl">


                                <a class="ah-tab-item" href="javascript:void(0)" data-ah-tab-active='true'><i
                                        class="mdi mdi-comment-text-multiple-outline"></i>نظرات</a>


                            </div>
                        </div>

                        <div class="ah-tab-content-wrapper shop-info px-4 dt-sl">

                            @include('front::shop.partials.reviews')



                        </div>

                    </section>
                    <!-- End tabs -->
                </div>
                <!-- End Product -->



            </div>
        </main>
    @endisset
    <!-- End main-content -->



    <!-- product review add modal -->
    @if (auth()->check())
        @include('front::shop.partials.add-review')
    @endif
    @include('front::shop.partials.shop-info-mobile')

    {{-- @include('front::products.partials.sizes-modal') --}}
    {{-- @include('front::products.partials.product-info-mobile') --}}
@endsection

@push('scripts')
    <script src="{{ theme_asset('js/vendor/jquery.fancybox.min.js') }}"></script>
    <script src="{{ theme_asset('js/plugins/apexcharts/apexcharts.js') }}"></script>
    <script src="{{ theme_asset('js/pages/shop/show.js') }}?v=51"></script>
    <script src="{{ theme_asset('js/pages/comments.js') }}?v=51"></script>
@endpush
