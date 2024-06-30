<!-- Modal -->
<div class="modal fade" data-action="" id="add-shop-review-modal" tabindex="-1" aria-labelledby="add-shop-review-label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h5 class="modal-title" id="price-changes-modal-label">
                    {{ trans('front::messages.reviews.submit-comment-title') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @isset($shop)
                    <div class="row">
                        <div class="col-4">
                            <p>فروشگاه: {{ $shop->nameofstore }}</p>
                        </div>
                        <div class="col-4 d-flex justify-content-start">
                            <img style="max-width:100px" src="{{ $shop->photo }}" alt="{{ $shop->nameofstore }}">
                        </div>
                    </div>
                @endisset
                @isset($employee)
                    <div class="row">
                        <div class="col-4">
                            <p>پرسنل: {{ $employee->full_name }}</p>
                        </div>
                        <div class="col-4 d-flex justify-content-start">
                            <img style="max-width:100px" src="{{ $employee->photo }}" alt="{{ $employee->full_name }}">
                        </div>
                    </div>
                @endisset
                @isset($service)
                    <div class="row">
                        <div class="col-4">
                            <p>امکانات: {{ $service->name }}</p>

                        </div>
                        <div class="col-4 d-flex justify-content-start">
                            <img style="max-width:100px" src="{{ $service->photo }}" alt="{{ $service->name }}">
                        </div>
                    </div>
                @endisset
                <hr>
                <div class="row comments-add-col--content">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-ui">
                            <form id="add-shop-review-form" action="" class="px-2" method="post">
                                @isset($shop)
                                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                                @endisset
                                @isset($employee)
                                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                @endisset
                                @isset($service)
                                    <input type="hidden" name="service_id" value="{{ $service->id }}">
                                @endisset
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-row-title mb-2">
                                            {{ trans('front::messages.reviews.rate') }}
                                            <span class="text-danger">*</span> (<span
                                                id="selected-rating-text">{{ trans('front::messages.reviews.rate') }}</span>)
                                        </div>
                                        <div class="shop-review-rate d-flex">

                                            <input data-title="{{ trans('front::messages.reviews.review-angry') }}"
                                                type="radio" id="review-angry" name="rating" value="1">
                                            <div title="{{ trans('front::messages.reviews.review-angry') }}"
                                                class="review-rate-item">
                                                <label for="review-angry">
                                                    <i class="mdi mdi-emoticon-sad-outline"></i>
                                                </label>
                                            </div>

                                            <input data-title="{{ trans('front::messages.reviews.review-sad') }}"
                                                type="radio" id="review-sad" name="rating" value="2">
                                            <div title="{{ trans('front::messages.reviews.review-sad') }}"
                                                class="review-rate-item">
                                                <label for="review-sad">
                                                    <i class="mdi mdi-emoticon-neutral-outline"></i>
                                                </label>
                                            </div>

                                            <input data-title="{{ trans('front::messages.reviews.review-neutral') }}"
                                                type="radio" id="review-neutral" name="rating" value="3">
                                            <div title="{{ trans('front::messages.reviews.review-neutral') }}"
                                                class="review-rate-item">
                                                <label for="review-neutral">
                                                    <i class="mdi mdi-emoticon-happy-outline"></i>
                                                </label>
                                            </div>

                                            <input data-title="{{ trans('front::messages.reviews.review-emoticon') }}"
                                                type="radio" id="review-emoticon" name="rating" value="4">
                                            <div title="{{ trans('front::messages.reviews.review-emoticon') }}"
                                                class="review-rate-item">
                                                <label for="review-emoticon">
                                                    <i class="mdi mdi-emoticon-outline"></i>
                                                </label>
                                            </div>

                                            <input data-title="{{ trans('front::messages.reviews.review-excited') }}"
                                                type="radio" id="review-excited" name="rating" value="5">
                                            <div title="{{ trans('front::messages.reviews.review-excited') }}"
                                                class="review-rate-item">
                                                <label for="review-excited">
                                                    <i class="mdi mdi-emoticon-excited-outline"></i>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-row-title mb-2">
                                            {{ trans('front::messages.reviews.title') }}
                                            <span class="text-danger">*</span>
                                        </div>
                                        <div class="form-row">
                                            <input class="input-ui pr-2" name="title" type="text"
                                                placeholder="{{ trans('front::messages.reviews.title-placeholder') }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-12 form-comment-title--positive mt-2">
                                        <div class="form-row-title mb-2 pr-2">
                                            {{ trans('front::messages.reviews.advantages') }}
                                        </div>
                                        <div id="advantages" class="form-row">
                                            <div class="ui-input--add-point">
                                                <input class="input-ui pr-2 ui-input-field" type="text"
                                                    id="advantage-input" autocomplete="off" value="">
                                                <button class="ui-input-point js-icon-form-add"
                                                    type="button"></button>
                                            </div>
                                            <div class="form-comment-dynamic-labels js-advantages-list"></div>
                                        </div>
                                    </div>
                                    <div class="col-12 form-comment-title--negative mt-2">
                                        <div class="form-row-title mb-2 pr-2">
                                            {{ trans('front::messages.reviews.disadvantages') }}
                                        </div>
                                        <div id="disadvantages" class="form-row">
                                            <div class="ui-input--add-point">
                                                <input class="input-ui pr-2 ui-input-field" type="text"
                                                    id="disadvantage-input" autocomplete="off" value="">
                                                <button class="ui-input-point js-icon-form-add"
                                                    type="button"></button>
                                            </div>
                                            <div class="form-comment-dynamic-labels js-disadvantages-list">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-2">
                                        <div class="form-row-title mb-2">
                                            {{ trans('front::messages.reviews.body') }}
                                            <span class="text-danger">*</span>
                                        </div>
                                        <div class="form-row">
                                            <textarea name="body" class="input-ui pr-2 pt-2" rows="5"
                                                placeholder="{{ trans('front::messages.reviews.body-placeholder') }}" required></textarea>
                                        </div>
                                    </div>



                                    <div class="col-12 mt-2">
                                        <button class="btn btn btn-primary btn-block px-3 review_submit_button"
                                            type="button">
                                            {{ trans('front::messages.reviews.submit-comment-btn') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        @if (option('dt_product_reviews_description'))
                            {!! option('dt_product_reviews_description') !!}
                        @else
                            <h3>دیگران را با نوشتن نظرات خود، برای انتخاب این مجموعه راهنمایی کنید.</h3>
                            <div class="desc-comment">
                                <p>لطفا پیش از ارسال نظر، خلاصه قوانین زیر را مطالعه کنید:</p>
                                <p>فارسی بنویسید و از کیبورد فارسی استفاده کنید. بهتر است از فضای خالی (Space)
                                    بیش‌از‌حدِ معمول، شکلک یا ایموجی استفاده نکنید و از کشیدن حروف یا کلمات با
                                    صفحه‌کلید بپرهیزید.</p>
                                <p>نظرات خود را براساس تجربه و استفاده‌ی عملی و با دقت به نکات فنی ارسال کنید؛
                                    بدون
                                    تعصب به محصول خاص، مزایا و معایب را بازگو کنید و بهتر است از ارسال نظرات
                                    چندکلمه‌‌ای خودداری کنید.</p>
                                <p>بهتر است در نظرات خود از تمرکز روی عناصر متغیر مثل قیمت، پرهیز کنید.</p>
                                <p>به کاربران و سایر اشخاص احترام بگذارید. پیام‌هایی که شامل محتوای توهین‌آمیز و
                                    کلمات نامناسب باشند، حذف می‌شوند.</p>
                                <p>از ارسال لینک‌های سایت‌های دیگر و ارایه‌ی اطلاعات شخصی خودتان مثل شماره تماس،
                                    ایمیل و آی‌دی شبکه‌های اجتماعی پرهیز کنید.</p>
                                <p>با توجه به ساختار بخش نظرات، از پرسیدن سوال یا درخواست راهنمایی در این بخش
                                    خودداری کرده و سوالات خود را در بخش «پرسش و پاسخ» مطرح کنید.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
