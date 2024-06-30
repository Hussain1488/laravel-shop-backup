@extends('front::layouts.master')


@section('content')
    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">
            <!-- Start title - breadcrumb -->
            <div class="title-breadcrumb-special dt-sl mb-3">
                <div class="breadcrumb dt-sl">
                    <nav>
                        <a href="{{ route('front.index') }}">{{ trans('front::messages.products.home') }}</a>
                        <a href="{{ route('front.products.index') }}">{{ trans('front::messages.products.products') }}</a>

                        <span>پرو مجازی</span>
                    </nav>
                </div>
            </div>
            <div class="dt-sn mb-3 dt-sl">
                <div class="row">
                    <!-- Product Gallery-->
                    <div class="p-4 ps-relative">

                        <div class="alert alert-success">به پرو مجازی خوش آمدید</div>
                        
                        {{-- @dump($product) --}}
                        <div class="gollery row">
                            @foreach ($product->gallery as $key)
                                <div class="col-12 col-md-6 col-lg-3 d-flex p-1">
                                    <a class="product-thumb" href="javascript:void(0)">
                                        <img class="w-100 image-target-swap" data-id="{{ $key->id }}"
                                            src="{{ $key->image ? asset($key->image) : asset('/no-image-product.png') }}"
                                            alt="{{ $product->title }}">
                                    </a>
                                </div>
                            @endforeach
                        </div>

                    </div>
                    {{-- @include('front::products.partials.product-info') --}}
                </div>
            </div>
            <!-- End title - breadcrumb -->
        </div>

        <!-- End tabs -->
        </div>
        <!-- End Product -->



        </div>
    </main>
    <!-- End main-content -->


    @include('front::products.image-swap-modal')
@endsection
@include('back.partials.plugins', [
    'plugins' => [
        'ckeditor',
        'dropzone',
        'jquery-tagsinput',
        'jquery.validate',
        'jquery-ui',
        'jquery-ui-sortable',
    ],
])

@push('scripts')
    <script src="{{ theme_asset('js/pages/imageSwap.js') }}?v=51"></script>
@endpush
