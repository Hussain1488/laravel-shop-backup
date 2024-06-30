<div class="ah-tab-content comments-tab dt-sl active" data-ah-tab-active="true">
    @isset($shop)
        <div class="dt-sl">

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="comments-summary-note">
                        <span>شما هم میتوانید درباره این مجموعه نظر بدهید.</span>
                        <p>برای ثبت نظر لازم است که وارد حساب کاربری خود شوید. در صورتی که وارد حساب کاربری خود نشده اید
                            لطفا قبل از اقدام برای ثبت نظر وارد حساب کاربری خود شوید.</p>
                        <div class="dt-sl mt-2 mb-4">
                            @if (auth()->check())
                                <button id="add-shop-review-modal-button"
                                    data-action="{{ route('front.shop.review', ['shop' => $shop]) }}"
                                    class="btn-primary-cm btn-with-icon add-shop-review-modal-button">
                                    <i class="mdi mdi-comment-text-outline"></i>
                                    {{ trans('front::messages.reviews.add-review') }}
                                </button>
                            @else
                                <a href="{{ route('login', ['redirect', route('front.shops.show', ['shop' => $shop])]) }}"
                                    class="btn-primary-cm btn-with-icon">
                                    <i class="mdi mdi-comment-text-outline"></i>
                                    {{ trans('front::messages.reviews.add-review') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if ($reviews->count())
                <div class="comments-area dt-sl">
                    <div class="section-title text-sm-title title-wide no-after-title-wide mb-0 dt-sl">
                        <h2>{{ trans('front::messages.reviews.user-comments') }}</h2>
                        <p class="count-comment"><span class="rate-product">(<i
                                    class="mdi mdi mdi-star text-warning mx-0"></i>{{ $shop->default_rating != 'none' ? $shop->default_rating : $shop->rating }}
                                | {{ $shop->reviews_count }})</span>
                        </p>
                    </div>
                    <ol class="comment-list">
                        @foreach ($reviews as $review)
                            <!-- #comment-## -->
                            <li>
                                <div class="comment-body">
                                    <div class="row">

                                        <div class="col-md-12 col-sm-12 comment-content">
                                            <div class="comment-title">
                                                {{ $review->title }}
                                            </div>
                                            <div class="comment-author">
                                                توسط {{ $review->user->fullname }} در تاریخ
                                                {{ jdate($review->created_at)->format('%d %B %Y') }}
                                                @if ($review->suggest)
                                                    <span class="badge badge-success">خریدار</span>
                                                @endif
                                            </div>


                                            <p>{!! nl2br(htmlentities($review->body)) !!}</p>

                                            @if ($review->point->count())
                                                <div class="row">
                                                    @if ($review->point->where('type', 'positive')->count())
                                                        <div class="col-md-4 col-sm-6 col-12">
                                                            <div class="content-expert-evaluation-positive">
                                                                <span>نقاط قوت</span>
                                                                <ul>
                                                                    @foreach ($review->point->where('type', 'positive') as $point)
                                                                        <li>{{ $point->text }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if ($review->point->where('type', 'negative')->count())
                                                        <div class="col-md-4 col-sm-6 col-12">
                                                            <div class="content-expert-evaluation-negative">
                                                                <span>نقاط ضعف</span>
                                                                <ul>
                                                                    @foreach ($review->point->where('type', 'negative') as $point)
                                                                        <li>{{ $point->text }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @switch($review->suggest)
                                                @case('yes')
                                                    <div class="user-suggest text-success"><i class="mdi mdi-thumb-up-outline"></i>
                                                        پیشنهاد می کنم</div>
                                                @break

                                                @case('not_sure')
                                                    <div class="user-suggest text-muted"><i class="mdi mdi-help"></i> مطمئن نیستم
                                                    </div>
                                                @break

                                                @case('no')
                                                    <div class="user-suggest text-danger"><i class="mdi mdi-thumb-down-outline"></i>
                                                        پیشنهاد نمی کنم</div>
                                                @break
                                            @endswitch

                                            <div class="footer">
                                                <div class="comments-likes">
                                                    <button
                                                        data-action="{{ route('front.shop.like', ['shops' => $review]) }}"
                                                        class="btn-like">
                                                        <span class="likes-count">{{ $review->likes_count }}</span> <i
                                                            class="mdi mdi-thumb-up-outline"></i>
                                                    </button>
                                                    <button
                                                        data-action="{{ route('front.shop.dislike', ['shops' => $review]) }}"
                                                        class="btn-like">
                                                        <span class="dislikes-count">{{ $review->dislikes_count }}</span>
                                                        <i class="mdi mdi-thumb-down-outline"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif
        </div>
    @endisset
    @isset($employee)
        <div class="dt-sl">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="comments-summary-note">
                        <span>شما هم میتوانید درباره این مجموعه نظر بدهید.</span>
                        <p>برای ثبت نظر لازم است که وارد حساب کاربری خود شوید. در صورتی که وارد حساب کاربری خود نشده اید
                            لطفا قبل از اقدام برای ثبت نظر وارد حساب کاربری خود شوید.</p>
                        <div class="dt-sl mt-2 mb-4">
                            @if (auth()->check())
                                <button id="add-shop-review-modal-button"
                                    data-action="{{ route('front.shop.employeeReview', ['shop' => $employee]) }}"
                                    class="btn-primary-cm btn-with-icon add-shop-review-modal-button">
                                    <i class="mdi mdi-comment-text-outline"></i>
                                    {{ trans('front::messages.reviews.add-review') }}
                                </button>
                            @else
                                <a href="{{ route('login', ['redirect', route('front.shop.employeeReview', ['shop' => $employee])]) }}"
                                    class="btn-primary-cm btn-with-icon">
                                    <i class="mdi mdi-comment-text-outline"></i>
                                    {{ trans('front::messages.reviews.add-review') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if ($reviews->count())
                <div class="comments-area dt-sl">
                    <div class="section-title text-sm-title title-wide no-after-title-wide mb-0 dt-sl">
                        <h2>{{ trans('front::messages.reviews.user-comments') }}</h2>
                        <p class="count-comment"><span class="rate-product">(<i
                                    class="mdi mdi mdi-star text-warning mx-0"></i>{{ $employee->rating }}
                                | {{ $employee->reviews_count }})</span>
                        </p>
                    </div>
                    <ol class="comment-list">
                        @foreach ($reviews as $review)
                            <!-- #comment-## -->
                            <li>
                                <div class="comment-body">
                                    <div class="row">

                                        <div class="col-md-12 col-sm-12 comment-content">
                                            <div class="comment-title">
                                                {{ $review->title }}
                                            </div>
                                            <div class="comment-author">
                                                توسط {{ $review->user->fullname }} در تاریخ
                                                {{ jdate($review->created_at)->format('%d %B %Y') }}
                                                @if ($review->suggest)
                                                    <span class="badge badge-success">خریدار</span>
                                                @endif
                                            </div>


                                            <p>{!! nl2br(htmlentities($review->body)) !!}</p>

                                            @if ($review->point->count())
                                                <div class="row">
                                                    @if ($review->point->where('type', 'positive')->count())
                                                        <div class="col-md-4 col-sm-6 col-12">
                                                            <div class="content-expert-evaluation-positive">
                                                                <span>نقاط قوت</span>
                                                                <ul>
                                                                    @foreach ($review->point->where('type', 'positive') as $point)
                                                                        <li>{{ $point->text }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if ($review->point->where('type', 'negative')->count())
                                                        <div class="col-md-4 col-sm-6 col-12">
                                                            <div class="content-expert-evaluation-negative">
                                                                <span>نقاط ضعف</span>
                                                                <ul>
                                                                    @foreach ($review->point->where('type', 'negative') as $point)
                                                                        <li>{{ $point->text }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @switch($review->suggest)
                                                @case('yes')
                                                    <div class="user-suggest text-success"><i class="mdi mdi-thumb-up-outline"></i>
                                                        پیشنهاد می کنم</div>
                                                @break

                                                @case('not_sure')
                                                    <div class="user-suggest text-muted"><i class="mdi mdi-help"></i> مطمئن نیستم
                                                    </div>
                                                @break

                                                @case('no')
                                                    <div class="user-suggest text-danger"><i class="mdi mdi-thumb-down-outline"></i>
                                                        پیشنهاد نمی کنم</div>
                                                @break
                                            @endswitch

                                            <div class="footer">
                                                <div class="comments-likes">
                                                    <button
                                                        data-action="{{ route('front.shop.like', ['shops' => $review]) }}"
                                                        class="btn-like">
                                                        <span class="likes-count">{{ $review->likes_count }}</span> <i
                                                            class="mdi mdi-thumb-up-outline"></i>
                                                    </button>
                                                    <button
                                                        data-action="{{ route('front.shop.dislike', ['shops' => $review]) }}"
                                                        class="btn-like">
                                                        <span class="dislikes-count">{{ $review->dislikes_count }}</span>
                                                        <i class="mdi mdi-thumb-down-outline"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif
        </div>
    @endisset
    @isset($service)
        <div class="dt-sl">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="comments-summary-note">
                        <span>شما هم میتوانید درباره این مجموعه نظر بدهید.</span>
                        <p>برای ثبت نظر لازم است که وارد حساب کاربری خود شوید. در صورتی که وارد حساب کاربری خود نشده اید
                            لطفا قبل از اقدام برای ثبت نظر وارد حساب کاربری خود شوید.</p>
                        <div class="dt-sl mt-2 mb-4">
                            @if (auth()->check())
                                <button id="add-shop-review-modal-button"
                                    data-action="{{ route('front.shop.serviceReview', ['shop' => $service]) }}"
                                    class="btn-primary-cm btn-with-icon add-shop-review-modal-button">
                                    <i class="mdi mdi-comment-text-outline"></i>
                                    {{ trans('front::messages.reviews.add-review') }}
                                </button>
                            @else
                                <a href="{{ route('login', ['redirect', route('front.shop.serviceReview', ['shop' => $service])]) }}"
                                    class="btn-primary-cm btn-with-icon">
                                    <i class="mdi mdi-comment-text-outline"></i>
                                    {{ trans('front::messages.reviews.add-review') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if ($reviews->count())
                <div class="comments-area dt-sl">
                    <div class="section-title text-sm-title title-wide no-after-title-wide mb-0 dt-sl">
                        <h2>{{ trans('front::messages.reviews.user-comments') }}</h2>
                        <p class="count-comment"><span class="rate-product">(<i
                                    class="mdi mdi mdi-star text-warning mx-0"></i>{{ $service->rating }}
                                | {{ $service->reviews_count }})</span>
                        </p>
                    </div>
                    <ol class="comment-list">
                        @foreach ($reviews as $review)
                            <!-- #comment-## -->
                            <li>
                                <div class="comment-body">
                                    <div class="row">

                                        <div class="col-md-12 col-sm-12 comment-content">
                                            <div class="comment-title">
                                                {{ $review->title }}
                                            </div>
                                            <div class="comment-author">
                                                توسط {{ $review->user->fullname }} در تاریخ
                                                {{ jdate($review->created_at)->format('%d %B %Y') }}
                                                @if ($review->suggest)
                                                    <span class="badge badge-success">خریدار</span>
                                                @endif
                                            </div>


                                            <p>{!! nl2br(htmlentities($review->body)) !!}</p>

                                            @if ($review->point->count())
                                                <div class="row">
                                                    @if ($review->point->where('type', 'positive')->count())
                                                        <div class="col-md-4 col-sm-6 col-12">
                                                            <div class="content-expert-evaluation-positive">
                                                                <span>نقاط قوت</span>
                                                                <ul>
                                                                    @foreach ($review->point->where('type', 'positive') as $point)
                                                                        <li>{{ $point->text }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if ($review->point->where('type', 'negative')->count())
                                                        <div class="col-md-4 col-sm-6 col-12">
                                                            <div class="content-expert-evaluation-negative">
                                                                <span>نقاط ضعف</span>
                                                                <ul>
                                                                    @foreach ($review->point->where('type', 'negative') as $point)
                                                                        <li>{{ $point->text }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @switch($review->suggest)
                                                @case('yes')
                                                    <div class="user-suggest text-success"><i
                                                            class="mdi mdi-thumb-up-outline"></i>
                                                        پیشنهاد می کنم</div>
                                                @break

                                                @case('not_sure')
                                                    <div class="user-suggest text-muted"><i class="mdi mdi-help"></i> مطمئن نیستم
                                                    </div>
                                                @break

                                                @case('no')
                                                    <div class="user-suggest text-danger"><i
                                                            class="mdi mdi-thumb-down-outline"></i>
                                                        پیشنهاد نمی کنم</div>
                                                @break
                                            @endswitch

                                            <div class="footer">
                                                <div class="comments-likes">
                                                    <button
                                                        data-action="{{ route('front.shop.like', ['shops' => $review]) }}"
                                                        class="btn-like">
                                                        <span class="likes-count">{{ $review->likes_count }}</span> <i
                                                            class="mdi mdi-thumb-up-outline"></i>
                                                    </button>
                                                    <button
                                                        data-action="{{ route('front.shop.dislike', ['shops' => $review]) }}"
                                                        class="btn-like">
                                                        <span class="dislikes-count">{{ $review->dislikes_count }}</span>
                                                        <i class="mdi mdi-thumb-down-outline"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif
        </div>
    @endisset
</div>
