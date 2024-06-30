<div class="product-card mb-2 mx-res-0">
    @if($fitting->product->isSpecial())
        <div class="promotion-badge">
         {{ trans('front::messages.categories.special-sale') }}
        </div>
    @endif
    <div class="product-head">
        @if ($fitting->product->labels->count())
            <div class="row">
                <div class="btn-group" role="group">
                    @foreach ($fitting->product->labels as $label)
                        <div class="fild_products">
                            <span>{{ $label->title }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="rating-stars">
            <i class="mdi mdi-star active"></i>
            <i class="mdi mdi-star active"></i>
            <i class="mdi mdi-star active"></i>
            <i class="mdi mdi-star active"></i>
            <i class="mdi mdi-star active"></i>
        </div>
        @if($fitting->product->discount)
            <div class="discount">
                <span>{{ $fitting->product->discount }}%</span>
            </div>
        @endif
    </div>
    <a class="product-thumb" href="{{ route('front.products.show', ['product' => $fitting->product]) }}">
        <img data-src="{{ $fitting->product->image ? asset($fitting->product->image) : asset('/no-image-product.png') }}" src="{{ theme_asset('images/600-600.png') }}" alt="{{ $fitting->product->title }}">
    </a>
    <div class="product-card-body">

        <h5 class="product-title">
            <a href="{{ route('front.products.show', ['product' => $fitting->product]) }}">{{ $fitting->product->title }}</a>
        </h5>

        @if($fitting->product->category)
            <a class="product-meta" href="{{ route('front.products.category', ['category' => $fitting->product->category]) }}">{{ $fitting->product->category->title }}</a>
        @endif

        <div class="product-prices-div">
            <span class="product-price">{{ $fitting->product->getLowestPrice() }}</span>

            @if($fitting->product->getLowestDiscount())
                <del class="product-price text-danger">{{ $fitting->product->getLowestDiscount() }}</del>
            @endif
        </div>

        @if ($fitting->product->isSinglePrice())
            <div class="cart">
                <a data-action="{{ route('front.cart.store', ['product' => $fitting->product]) }}" class="d-flex align-items-center add-to-cart-single" href="javascript:void(0)"><i class="mdi mdi-plus px-2"></i>
                    <span>{{ trans('front::messages.categories.add-to-cart') }}</span>
                </a>
            </div>
        @endif

    </div>
</div>
