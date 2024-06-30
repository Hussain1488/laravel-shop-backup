<!-- Product Info -->

<div class="col-12 button_sticky_container p-0">


    @if ($product->isPhysical())
        <div class="card box-card px-2 pb-1 ">
            <div class=""></div>
            <div class="dt-sl box-Price-number box-margin">
                @if ($get_child->isEmpty())


                    @if ($product->addableToCart())

                        <div class="section-title text-sm-title no-after-title-wide mb-0 dt-sl">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <button data-toggle="modal" data-target="#add-product-review-modal"
                                            class="btn-info-cm bg-info btn-with-icon mb-2 w-50">
                                            <i class="mdi mdi-comment-text-outline"></i>
                                            {{ trans('front::messages.reviews.add-review') }}
                                        </button>

                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-end">
                                                @if ($selected_price->hasDiscount())
                                                    <del>
                                                        {{ number_format($selected_price->regularPrice()) }}
                                                    </del>
                                                    <div class="discount show-discount mr-3 ">
                                                        <span>{{ $selected_price->discount() }}%</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-12 text-left">
                                                <span class="price text-danger">
                                                    {{ trans('front::messages.currency.prefix') }}
                                                    {{ number_format($selected_price->salePrice()) }}
                                                </span>
                                                <span class="currency">
                                                    {{ trans('front::messages.currency.suffix') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-3 pl-0" style="margin-top:2px;">
                                <a href="{{ route('front.products.imageSwap', [$product->id]) }}"
                                    class="btn btn-primary-cm btn-with-icon w-100">
                                    پرو مجازی
                                </a>
                            </div>
                            <div class="col-9" style="margin-top:2px;margin-x:0px">
                                <button data-price_id="{{ $selected_price->id }}"
                                    data-action="{{ route('front.cart.store', ['product' => $product]) }}"
                                    data-product="{{ $product->slug }}" type="button"
                                    class="btn btn-primary-cm btn-with-icon add-to-cart btn-show-product w-100">
                                    {{ trans('front::messages.products.add-to-cart') }}
                                </button>
                            </div>
                        </div>


                        {{-- </div> --}}
                    @elseif (!$product->addableToCart())
                        <div class="infoSection ">
                            <div class="box-product-unavailable">
                                <div class="unavailable d-flex justify-content-center">
                                    <h5 class="">{{ trans('front::messages.products.unavailable') }}
                                    </h5>
                                </div>
                                <p class="text-justify">
                                    {{ trans('front::messages.products.text-unavailable') }}</p>
                            </div>
                            <div class="text-center">
                                <button id="stock_notify_btn"
                                    data-user="{{ auth()->check() ? auth()->user()->id : '' }}"
                                    data-product="{{ $product->id }}" type="button"
                                    class="btn-primary-cm bg-secondary btn-with-icon cart-not-available ">
                                    <i class="mdi mdi-information"></i>
                                    {{ trans('front::messages.products.let-me-know') }}
                                </button>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="section-title text-sm-title no-after-title-wide mb-0 dt-sl">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <button data-toggle="modal" data-target="#add-product-review-modal"
                                        class="btn-info-cm bg-info btn-with-icon mb-2 w-50">
                                        <i class="mdi mdi-comment-text-outline"></i>
                                        {{ trans('front::messages.reviews.add-review') }}
                                    </button>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            {{-- @if ($selected_price->hasDiscount())
                                            <del>
                                                {{ number_format($selected_price->regularPrice()) }}
                                            </del>
                                            <div class="discount show-discount mr-3 ">
                                                <span>{{ $selected_price->discount() }}%</span>
                                            </div>
                                        @endif --}}
                                        </div>
                                        <div class="col-12 text-left">
                                            {{-- <span class="price text-danger">
                                            {{ trans('front::messages.currency.prefix') }}
                                            {{ number_format($selected_price->salePrice()) }}
                                        </span>
                                        <span class="currency">
                                            {{ trans('front::messages.currency.suffix') }}
                                        </span> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-3 pl-0" style="margin-top:2px;">
                            <a href="{{ route('front.products.imageSwap', [$product->id]) }}"
                                class="btn btn-primary-cm btn-with-icon w-100">
                                پرو مجازی
                            </a>
                        </div>
                        <div class="col-9" style="margin-top:2px;margin-x:0px">
                            <button" type="button" data-toggle="modal" data-target="#productsModal"
                                class="btn btn-primary-cm btn-with-icon btn-show-product w-100">
                                {{ trans('front::messages.products.add-to-cart') }}
                                </button>
                        </div>
                    </div>




                @endif

            </div>

        </div>
    @endif
</div>
<div class="modal fade" id="productsModal" tabindex="-1" aria-labelledby="productsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productsModalLabel">محصولات مرتبط</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    {{-- حلقه @foreach برای نمایش محصولات --}}
                    @foreach ($get_child as $related_product)
                        <div class="col-md-4">
                            <div class="product-card">
                                <div class="product-card-body">
                                    <h5 class="product-title">
                                        <p>{{ $related_product->title }}</p>
                                    </h5>
                                    <div class="price-index-h">
                                        <div class="product-prices-div">
                                            <span class="product-price">{{ $related_product->getLowestPrice() }}</span>
                                            @if ($related_product->getLowestDiscount())
                                                <del
                                                    class="product-price-del">{{ $related_product->getLowestDiscount() }}</del>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($related_product->isSinglePrice())
                                        <div class="cart">
                                            <a data-action="{{ route('front.cart.store', ['product' => $related_product]) }}"
                                                class="d-flex align-items-center add-to-cart-single"
                                                href="javascript:void(0)">
                                                <i class="mdi mdi-plus px-2"></i>
                                                <span>{{ trans('front::messages.partials.add-to-cart') }}</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
