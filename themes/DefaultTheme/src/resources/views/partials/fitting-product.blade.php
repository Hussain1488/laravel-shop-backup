<div class="item col-12 col-lg-3 col-md-4">
    <div class="product-card">
        <div class="product-head">
            @if ($key->product->labels->count())
                <div class="row">
                    <div class="btn-group" role="group">
                        @foreach ($key->product->labels as $label)
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
            @if ($key->product->discount)
                <div class="discount">
                    <span>{{ $key->product->discount }} %</span>
                </div>
            @endif
            <span class="fitting-image-download badge badge-danger mt-5 mr-5"
                data-action="{{ route('front.fitting.downloadImage', ['VirtualFitting' => $key]) }}">دانلود<i
                    class="mdi mdi-download "></i>

            </span>

        </div>

        <a class="product-thumb" href="{{ route('front.fitting.show', ['fitting' => $key]) }}">

            <img data-src="{{ $key->photo ? asset($key->photo) : asset('/no-image-product.png') }}"
                src="{{ theme_asset('images/600-600.png') }}" alt="{{ $key->product->title }}">

        </a>
        <div class="product-card-body">

            <h5 class="product-title">
                <a href="{{ route('front.fitting.show', ['fitting' => $key]) }}">{{ $key->product->title }}</a>
            </h5>
            <a class="product-meta"
                href="{{ $key->product->category ? $key->product->category->link : '#' }}">{{ $key->product->category ? $key->product->category->title : trans('front::messages.partials.no-category') }}</a>
            <div class="price-index-h">
                <div class="product-prices-div">
                    <span class="product-price">{{ $key->product->getLowestPrice() }}</span>

                    @if ($key->product->getLowestDiscount())
                        <del class="product-price-del">{{ $key->product->getLowestDiscount() }}</del>
                    @endif
                </div>
            </div>
            <div class=" mobile-social-share">
                <div id="socialHolder" class="col-md-1">
                    <div id="socialShare" class="btn-group share-group">
                        <a data-toggle="dropdown" class="btn p-0">
                            <i class="mdi mdi-share-variant mdi-24px"></i>
                        </a>

                        <ul class="dropdown-menu">

                            <li>
                                <a href="https://eitaa.com/share/url?url={{ url('fitting/' . $key->id) }}"
                                    data-original-title="LinkedIn" rel="tooltip" href="#"
                                    class="btn btn-linkedin" data-placement="left">
                                    <img class="w-100" src="{{ theme_asset('img/eitaa.svg') }}" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="https://api.whatsapp.com/send?text={{ url('fitting/' . $key->id) }}"
                                    data-original-title="Pinterest" rel="tooltip" class="btn btn-pinterest"
                                    data-placement="left">
                                    <i class="mdi mdi-whatsapp mdi-24px"></i>
                                </a>

                            </li>
                            <li>
                                <a href="https://telegram.me/share/url?url={{ url('fitting/' . $key->id) }}"
                                    target="_blank" data-original-title="Pinterest" rel="tooltip"
                                    class="btn btn-telegram" data-placement="left">
                                    <i class="mdi mdi-telegram mdi-24px"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" data-link="{{ url('fitting/' . $key->id) }}"
                                    rel="tooltip" class="btn btn-telegram fittin-link-copy-button"
                                    data-placement="left">
                                    <i class="mdi mdi-content-copy mdi-24px"></i>
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>

            </div>


        </div>
    </div>
</div>
