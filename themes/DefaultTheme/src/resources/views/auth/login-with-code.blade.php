@extends('front::auth.layouts.master', ['title' => trans('front::messages.auth.login-with-one-time-password')])

@section('content')
    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">
            {{-- <button class="btn btn-success refral_test">تست رفرال</button> --}}
            <div class="row">
                <div class="col-xl-4 col-lg-5 col-md-7 col-12 mx-auto">
                    <div class="form-ui dt-sl dt-sn pt-4">
                        <div class="section-title title-wide mb-1 no-after-title-wide">
                            <h2 class="font-weight-bold">{{ trans('front::messages.auth.login-with-one-time-password') }}
                            </h2>
                        </div>
                        <form id="login-with-code-form" data-redirect="{{ route('one-time-login', ['type' => 'login']) }}"
                            action="{{ route('login-with-code.send') }}" method="POST">
                            @csrf
                            <div class="form-row-title">
                                <h3>{{ trans('front::messages.auth.phone-number') }}</h3>
                            </div>
                            <div class="form-row with-icon form-group">
                                <input id="mobile" type="text" name="mobile" class="input-ui pr-2"
                                    placeholder="{{ trans('front::messages.auth.enter-mobile-number') }}" value="">
                                <i class="mdi mdi-account-circle-outline"></i>
                            </div>
                            {{-- {{ dump(Session::get('capch')) }} --}}
                            @if (Session::get('captcha'))
                                <div class="form-row mt-4">
                                    <div class="col-md-8 col-6">
                                        <div class="form-group">
                                            <input type="text" class="input-ui pl-2 captcha" autocomplete="off"
                                                name="captcha"
                                                placeholder="{{ trans('front::messages.auth.security-code') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <img class="captcha w-100" src="{{ captcha_src('flat') }}" alt="captcha">
                                    </div>
                                </div>
                            @else
                                <br>
                            @endif

                            <div class="form-row mt-3">
                                <button type="submit" class="btn-primary-cm btn-with-icon mx-auto w-100">
                                    <i class="mdi mdi-lock-open-variant-outline"></i>
                                    {{ trans('front::messages.auth.request-verification-code') }}
                                </button>
                            </div>

                            <div class="form-footer text-right mt-3">
                                <a href="{{ route('login') }}"
                                    class="d-inline-block mt-2">{{ trans('front::messages.auth.login-with-password') }}</a>
                            </div>


                        </form>
                    </div>
                </div>
            </div>


        </div>
    </main>
    <!-- End main-content -->
@endsection

@push('scripts')
    <div class="modal fade" id="registerWithCode">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <span id="code_error3" class="alert alert-danger d-none m-2" role="alert">
                </span>


                <!-- Modal Header -->
                <div class="modal-header">
                    <span class="alert alert-info" id="operation_title">برای ثبت نام در سایت لطفا کد پیامکی
                        که به شماره تلفن شما ارسال شده را وارد کنید</span>

                </div>
                <div class="alert alert-success d-none" id="success-alert1"></div>
                <hr />

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="message-light dir-rtl">
                        {{ trans('front::messages.auth.for-mobile-number') }} <span
                            id="userNumber">{{ optional(Session::get('newUser'))['number'] ?? 'شما' }}
                        </span>
                        {{ trans('front::messages.auth.confirmation-code-sent') }}
                        <a href="{{ route('login-with-code.request') }}" class="btn-link-border ">
                            {{ trans('front::messages.auth.edit-number') }}
                        </a>
                    </div>

                    <form id="code_varification2" action="{{ route('register-with-code') }}" method="post">
                        @csrf

                        <input id="user_phone_number" name="mobile" type="hidden" value="">
                        <div class="email-otp-container d-flex justify-center">
                            <!-- Six input fields for OTP digits -->
                            <input type="number" class="email-otp-input" pattern="\d" maxlength="1">
                            <input type="number" class="email-otp-input" pattern="\d" maxlength="1">
                            <input type="number" class="email-otp-input" pattern="\d" maxlength="1">
                            <input type="number" class="email-otp-input" pattern="\d" maxlength="1">
                            <input type="number" class="email-otp-input" pattern="\d" maxlength="1">

                        </div>
                        <div class="numbers-verify form-content form-content1">
                            <input name="verify_code" type="hidden" id='emailverificationCode'
                                placeholder="{{ trans('front::messages.auth.enter-auth-code') }}">
                        </div>
                        <div class="form-row mt-2 dir-rtl" id="resent-counter1">
                            <span
                                class="text-primary">{{ trans('front::messages.auth.retrieve-verification-code') }}</span>
                            (<p data-action="" id="countdown-verify-end1"></p>)
                        </div>
                        <input type="button" class="btn btn-info mb-1 d-none " id='sendAgain1' value="ارسال مجدد">
                        <div class="form-row mt-3">
                            <button data-url="{{ route('front.wallet.sentCode') }}" type="button" id="sendCodeRegister"
                                class="btn-primary-cm btn-with-icon mx-auto w-100">
                                <i class="mdi mdi-check"></i>
                                {{ trans('front::messages.auth.confirm-mobile-number') }}
                            </button>
                        </div>
                    </form>
                    <form id="login-resend-sms-form1" method="post" action="{{ route('resendSmsRegistre') }}">
                        @csrf
                        <input class="user-value" type="hidden"
                            value="{{ optional(Session::get('newUser'))['number'] }}" name="user">
                        {{-- {{ optional(Session::get('newUser')) }} --}}
                    </form>
                </div>

                <!-- Modal footer -->

                <div class="modal-footer">

                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="refralModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <span id="code_error3" class="alert alert-danger d-none m-2" role="alert">
                </span>


                <!-- Modal Header -->
                <div class="modal-header">
                    <span class="alert alert-info" id="operation_title">لطفا شماره معرف خود را وارد کنید تا هر دو جایزه
                        شارژ کیف پول را دریافت کنید!</span>

                </div>
                <div class="alert alert-success d-none" id="success-alert1"></div>
                <hr />

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="{{ route('refral-number') }}" method="POST" id="refral_form">
                        @csrf
                        <input type="number" class="form-control" name="refral_number">
                        <input type="button" id="refral_send_button" class="btn btn-success" value="ارسال">
                        <button type="button" class="btn btn-danger m-1 btn-sm reject-refral"
                            id="reject-refral">ندارم</button>
                    </form>
                </div>

                <!-- Modal footer -->

                <div class="modal-footer">

                </div>

            </div>
        </div>
    </div>

    <script src="{{ theme_asset('js/pages/otp.js') }}"></script>
    <script src="{{ theme_asset('js/vendor/countdown.min.js') }}"></script>
    <script src="{{ theme_asset('js/pages/login-with-code.js') }}"></script>
@endpush
