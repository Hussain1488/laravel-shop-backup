@extends('front::auth.layouts.master', ['title' => trans('front::messages.auth.login-with-confirmation-code')])

@php
    $redirect_url = Redirect::intended()->getTargetUrl();
    $type = request()->type;
    $back_url = $type == 'login' ? route('login-with-code.request') : route('password.request');
    $action = $type == 'login' ? route('login-with-code.confirm') : route('one-time-login');
@endphp

@section('content')
    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">

            <div class="row">
                <div class="col-xl-4 col-lg-5 col-md-7 col-12 mx-auto">
                    <div class="form-ui dt-sl dt-sn pt-4">
                        <div class="section-title title-wide mb-1 no-after-title-wide">
                            <h2 class="font-weight-bold">{{ trans('front::messages.auth.login-with-confirmation-code') }}
                            </h2>
                            @if (Session::has('success'))
                                <div id="alert-div" class="alert alert-success d-none">{{ Session::get('success') }}</div>
                            @endif
                        </div>
                        <div class="message-light">
                            {{ trans('front::messages.auth.for-mobile-number') }} {{ $user->username }}
                            {{ trans('front::messages.auth.confirmation-code-sent') }}
                            <a href="{{ $back_url }}" class="btn-link-border">
                                {{ trans('front::messages.auth.edit-number') }}
                            </a>
                        </div>
                        <form id="one-time-login-form" action="{{ $action }}">
                            @csrf

                            <input name="mobile" type="hidden" value="{{ $user->username }}">
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
                            <div class="form-row mt-2" id="resent-counter">
                                <span
                                    class="text-primary">{{ trans('front::messages.auth.retrieve-verification-code') }}</span>
                                (<p data-action="{{ $back_url }}" id="countdown-verify-end"></p>)
                            </div>
                            <input type="button" class="btn btn-info mb-1 d-none " id='sendAgain' value="ارسال مجدد">
                            <div class="form-row mt-3">
                                <button type="submit" class="btn-primary-cm btn-with-icon mx-auto w-100">
                                    <i class="mdi mdi-check"></i>
                                    {{ trans('front::messages.auth.confirm-mobile-number') }}
                                </button>
                            </div>
                        </form>
                        <form id="login-resend-sms-form" method="post" action="{{ route('resendSmsRegistred') }}">
                            @csrf
                            <input class="user-value" type="hidden" value="{{ $user->username }}" name="user">
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </main>
    <!-- End main-content -->
@endsection

@push('scripts')
    <script>
        var redirect_url = '{{ $redirect_url }}';
        // var resend_time = {{ $resend_time }};
    </script>

    <script src="{{ theme_asset('js/vendor/countdown.min.js') }}"></script>
    <script src="{{ theme_asset('js/pages/one-time-login.js?v=3') }}"></script>
    <script src="{{ theme_asset('js/pages/otp.js') }}"></script>
@endpush
