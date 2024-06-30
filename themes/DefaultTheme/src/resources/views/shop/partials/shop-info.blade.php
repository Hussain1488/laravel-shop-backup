<!-- Product Info -->
<div class="col-lg-8 mt-3 col-md-12 pb-5 product-info-block">
    <div class="product-info dt-sl">
        <div class="product-title">
            <h1>{{ $shop->title }}</h1>
            <h3 class="mb-1">{{ $shop->title_en }}</h3>
        </div>
        <div class="row pt-2">
            <div class="col-md-12 col-lg-12">
                <hr class="border-product-title">
                <div class="row mt-2">
                    @if ($shop->rating || $shop->default_rating != 'none')
                        <div class="col-12 d-flex">
                            <div class="d-flex">
                                <i class="mdi mdi mdi-star text-warning mx-0"></i>
                                <p class="mx-1 mb-2">
                                    {{ $shop->default_rating != 'none' ? $shop->default_rating : $shop->rating }}</p>
                            </div>

                        </div>
                    @endif

                </div>
                @if ($shop->description)
                    <p class="little-des pt-0 mt-0">{!! nl2br($shop->description) !!}</p>
                @endif

            </div>

        </div>
    </div>
</div>
