@extends('front::layouts.master')
{{--
@push('meta')
    @include('front::products.partials.product-meta')
@endpush --}}

@push('styles')
    <link rel="stylesheet" href="{{ theme_asset('css/vendor/fancybox.min.css') }}">
@endpush

@section('content')
    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">
            <!-- Start title - breadcrumb -->
            <div class="title-breadcrumb-special dt-sl mb-3">
                <div class="breadcrumb dt-sl">
                    <nav>
                        <a href="{{ route('front.index') }}">{{ trans('front::messages.products.home') }}</a>
                        <a href="{{ route('front.shops.index') }}">مجموعه ها</a>
                        <span>{{ $shop->nameofstore }}</span>
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
                                <div class="product-special">
                                    گالری مجموعه
                                </div>
                            </div>

                        </div>

                        <ul class="gallery-options">


                            @if (option('show_product_share_links', 1) == 1)
                                <li>
                                    <button data-toggle="modal" data-target="#shareproduct"><i
                                            class="mdi mdi-share-variant"></i></button>
                                    <span class="tooltip-option">اشتراک گذاری</span>
                                </li>
                            @endif

                            @can('createcolleague')
                                <li>
                                    <a href="{{ route('admin.createcolleague.shopedit', [$shop->id]) }}" target="_blank">
                                        <button><i class="mdi mdi-pencil text-warning"></i></button>
                                    </a>
                                    <span class="tooltip-option">{{ trans('front::messages.products.edit') }}</span>
                                </li>
                            @endcan

                        </ul>

                        @if ($shop)
                            <div class="product-gallery mt-3">
                                <div class="product-carousel owl-carousel" style="max-width:400px; max-height:600px">
                                    <div class="item">
                                        <a class="gallery-item mt-3" href="{{ asset($shop->photo) }}"
                                            data-fancybox="gallery" data-owl="one0">
                                            <img src="{{ asset('images/600-600.png') }}"
                                                data-src="{{ asset($shop->photo) }}" alt="{{ $shop->title }}">
                                        </a>
                                    </div>
                                    @foreach ($shop->gallery as $item)
                                        <div class="item">
                                            <a class="gallery-item mt-3" href="{{ asset($item->photo) }}"
                                                data-fancybox="gallery" data-owl="one{{ $loop->index + 1 }}">
                                                <img src="{{ asset('images/600-600.png') }}"
                                                    data-src="{{ asset($item->photo) }}" alt="{{ $shop->title }}">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                <hr class="border-product" />
                                <ul
                                    class="product-thumbnails product-carousel owl-carousel carousel-products d-flex justify-content-center">
                                    <li class="{{ true ? 'active' : '' }}">
                                        <a href="#one0" class="owl-thumbnail" data-slide="0">
                                            <img src="{{ theme_asset('images/600-600.png') }}"
                                                data-src="{{ asset($shop->photo) }}" alt="{{ $shop->title }}">
                                        </a>
                                    </li>
                                    @foreach ($shop->gallery as $item)
                                        <li class="">
                                            <a href="#one{{ $loop->index + 1 }}" class="owl-thumbnail"
                                                data-slide="{{ $loop->index + 1 }}">
                                                <img src="{{ theme_asset('images/600-600.png') }}"
                                                    data-src="{{ asset($item->photo) }}" alt="{{ $shop->title }}">
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if ($shop->location)
                            <a target="_blank" href="{{ $shop->location }}">
                                <div class='text-center p-5 bg-gray-400 rounded-lg '
                                    style="background-image: url({{ theme_asset('img/location.webp') }}); background-repeat:no-repeat; background-size: cover;background-position: center;">
                                    <span class="" style="font-size:20px; color:rgb(40, 54, 248)">برو به نقشه</span>
                                </div>
                            </a>
                        @endif
                        @if ($shop->addressofstore)
                            <p class="text- m-2"><i class="mdi mdi-map-marker"></i>آدرس:
                                <strong>{{ $shop->addressofstore }}
                                </strong>
                            </p>
                        @endif
                        @if ($shop->phone)
                            <p class="text- m-2"><i class="mdi mdi-phone"></i>شماره تماس:
                                <strong>{{ $shop->phone }}
                                </strong>
                            </p>
                        @endif

                    </div>
                    @include('front::shop.partials.shop-info')
                </div>
            </div>
            <div class="dt-sn mb-3 px-0 dt-sl pt-0">
                <!-- Start tabs -->
                <section class="tabs-product-info mb-3 dt-sl">
                    <div class="ah-tab-wrapper dt-sl">
                        <div class="ah-tab dt-sl">

                            <a class="ah-tab-item" href="javascript:void(0)" data-ah-tab-active='true'><i
                                    class="mdi mdi-comment-text-multiple-outline"></i>{{ trans('front::messages.products.comment') }}</a>

                            <a class="ah-tab-item" href="javascript:void(0)"><i class="mdi mdi-account"></i>پرسنل
                            </a>
                            <a class="ah-tab-item" href="javascript:void(0)"><i class="mdi mdi-tools"></i>امکانات </a>




                        </div>
                    </div>

                    <div class="ah-tab-content-wrapper shop-info px-4 dt-sl">
                        @include('front::shop.partials.reviews')

                        @if ($shop->employee->count() != 0)
                            <div class="ah-tab-content dt-sl">
                                <div class="d-flex flex-wrap">

                                    @foreach ($shop->employee as $key)
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 px-10 mb-1 px-res-0">
                                            <div class="product-card mb-2 mx-res-0 category-index">
                                                <div class="product-card-body">
                                                    <h5 class="product-title">
                                                        <a
                                                            href="{{ route('front.shop.employee', [$key->id]) }}">{{ $key->role }}</a>
                                                    </h5>
                                                </div>
                                                <a class="product-thumb"
                                                    href="{{ route('front.shop.employee', [$key->id]) }}">
                                                    <img data-src="{{ $key->photo }}" alt="{{ $key->full_name }}">
                                                </a>

                                                <a href="{{ route('front.shop.employee', [$key->id]) }}"
                                                    class="more-cat">{{ $key->full_name }}
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="ah-tab-content dt-sl">
                                <div class="section-title text-sm-title title-wide no-after-title-wide mb-0 dt-sl">
                                    <h2>{{ trans('front::messages.products.general-specifications') }}</h2>
                                </div>

                                <div class="description-shop dt-sl mt-3 mb-3">
                                    <div class="container">
                                        <p>پرسنلی برای این مجموعه موجود نیست!</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($shop->service->count())
                            <div class="ah-tab-content dt-sl ">
                                <div class="d-flex flex-wrap">

                                    @foreach ($shop->service as $key)
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 px-10 mb-1 px-res-0">
                                            <div class="product-card mb-2 mx-res-0 category-index">
                                                <div class="product-card-body">
                                                    <h5 class="product-title">
                                                        <a
                                                            href="{{ route('front.shop.service', [$key->id]) }}">{{ $key->role }}</a>
                                                    </h5>
                                                </div>
                                                <a class="product-thumb"
                                                    href="{{ route('front.shop.service', [$key->id]) }}">
                                                    <img data-src="{{ $key->photo }}" alt="{{ $key->name }}">
                                                </a>

                                                <a href="{{ route('front.shop.service', [$key->id]) }}"
                                                    class="more-cat">{{ $key->name }}
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="ah-tab-content dt-sl">
                                <div class="section-title text-sm-title title-wide no-after-title-wide mb-0 dt-sl">
                                    <h2>{{ trans('front::messages.products.general-specifications') }}</h2>
                                </div>

                                <div class="description-shop dt-sl mt-3 mb-3">
                                    <div class="container">
                                        <p>امکاناتی برای این مجموعه موجود نیست!</p>
                                    </div>
                                </div>
                            </div>
                        @endif


                        <div class="ah-tab-content dt-sl">

                        </div>
                    </div>
                </section>
                <!-- End tabs -->
            </div>
            <!-- End Product -->



        </div>
    </main>
    <!-- End main-content -->


    @if (option('show_product_share_links', 1) == 1)
        <!-- Modal -->
        <div class="modal fade" id="shareproduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">اشتراک گذاری</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">



                        <div>
                            <p>با استفاده از روش‌های زیر می‌توانید این صفحه را با دوستان خود به اشتراک بگذارید.</p>
                        </div>
                        <ul class="share-product">

                            <a target="_blank" class="telegram"
                                href="https://t.me/share/url?url={{ route('front.products.shortLink', ['id' => $shop->id]) }}">
                                <li class="custom-mdi mdi mdi-telegram"></li>
                            </a>

                            <a target="_blank" class="whatsapp"
                                href="https://api.whatsapp.com/send?text={{ route('front.products.shortLink', ['id' => $shop->id]) }}">
                                <li class="custom-mdi mdi mdi-whatsapp"></li>
                            </a>
                            <a target="_blank" class="twiiter"
                                href="https://twitter.com/intent/tweet?url={{ route('front.products.shortLink', ['id' => $shop->id]) }}">
                                <li class="custom-mdi mdi mdi-twitter"></li>
                            </a>
                            <a target="_blank" class="linkedin"
                                href="https://www.linkedin.com/sharing/share-offsite/?url= {{ route('front.products.shortLink', ['id' => $shop->id]) }}">
                                <li class="custom-mdi mdi mdi-linkedin"></li>
                            </a>
                        </ul>
                        <hr>
                        <div class="filed-link dir-ltr copy-text">
                            <input id="shareLink" type="text" disabled
                                value="{{ route('front.products.shortLink', ['id' => $shop->id]) }}" readonly="">
                            <div class="copy-text-btn" data-toggle="tooltip" data-placement="right" title=""
                                data-original-title="کپی لینک">
                                <i class="mdi mdi-content-copy"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif

    <!-- product review add modal -->
    @if (auth()->check())
        @include('front::shop.partials.add-review')
    @endif
    @include('front::shop.partials.shop-info-mobile')
@endsection

@push('scripts')
    <script src="{{ theme_asset('js/vendor/jquery.fancybox.min.js') }}"></script>
    <script src="{{ theme_asset('js/plugins/apexcharts/apexcharts.js') }}"></script>
    <script src="{{ theme_asset('js/pages/shop/show.js') }}?v=51"></script>
    <script src="{{ theme_asset('js/pages/comments.js') }}?v=51"></script>
@endpush
