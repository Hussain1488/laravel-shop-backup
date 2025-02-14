@extends('front::layouts.master', ['title' => trans('front::messages.products.products')])

@push('meta')
    <meta name="description" content="{{ option('info_short_description') }}">
    <meta name="keywords" content="{{ option('info_tags') }}">
    <link rel="canonical" href="{{ route('front.products.index') }}" />
@endpush
@section('content')
    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">

            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 search-card-res">
                    <!-- Start Content -->
                    <div class="title-breadcrumb-special dt-sl mb-3">
                        <div class="breadcrumb dt-sl">
                            <nav>
                                <a href="/">{{ trans('front::messages.products.home') }}</a>
                                <span>مجموعه ها</span>
                            </nav>
                        </div>
                    </div>
                    @if ($shop->count())
                        <div class="dt-sl dt-sn px-0 search-amazing-tab">
                            <div class="row mb-3 mx-0 px-res-0">

                                @foreach ($shop as $shop)
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 px-10 mb-1 px-res-0">
                                        <div class="product-card mb-2 mx-res-0 category-index">
                                            <div class="product-card-body">
                                                <h5 class="product-title">
                                                    <a
                                                        href="{{ route('front.shops.show', [$shop->id]) }}">{{ $shop->nameofstore }}</a>
                                                </h5>
                                            </div>
                                            <a class="product-thumb" href="{{ route('front.shops.show', [$shop->id]) }}">
                                                <img data-src="{{ $shop->photo }}" alt="{{ $shop->nameofstore }}">
                                            </a>

                                            <a href="{{ route('front.shops.show', [$shop->id]) }}" class="more-cat">مشاهده
                                                مجموعه</a>
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                        </div>
                    @else
                        @include('front::partials.empty')
                    @endif
                </div>
                <!-- End Content -->
            </div>

        </div>
    </main>
    <!-- End main-content -->
@endsection
