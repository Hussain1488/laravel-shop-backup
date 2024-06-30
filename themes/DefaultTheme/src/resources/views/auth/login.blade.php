@extends('front::auth.layouts.master', ['title' => trans('front::messages.auth.sign-in-to-site')])

@php
    $redirect_url = request('redirect') ?: Redirect::intended()->getTargetUrl();
@endphp

@section('content')
    <style>

    </style>
    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">

            <div class="row">
                <div class="col-xl-4 col-lg-5 col-md-7 col-12 mx-auto">
                    <div class="form-ui dt-sl dt-sn pt-4">
                        <div class="section-title title-wide mb-1 no-after-title-wide">
                            <h2 class="font-weight-bold">{{ trans('front::messages.auth.sign-in-to-site') }}</h2>
                        </div>
                        <form id="login-form" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-row-title">
                                <h3>{{ trans('front::messages.auth.phone-number') }}</h3>
                            </div>
                            <div class="form-row with-icon form-group">
                                <input type="text" name="username" class="input-ui pr-2"
                                    placeholder="{{ trans('front::messages.auth.enter-mobile-number') }}">
                                <i class="mdi mdi-account-circle-outline"></i>
                            </div>
                            <div class="form-row-title">
                                <h3>{{ trans('front::messages.auth.password') }}</h3>
                            </div>
                            <div class="form-row with-icon form-group">
                                <input id="passInput" type="password" name="password" class="input-ui pr-2"
                                    placeholder="{{ trans('front::messages.auth.enter-your-password') }}">
                                <i class="mdi mdi-eye" id="showPassword"></i>
                            </div>
                            <div class="form-row mt-2">
                                <div class="custom-control custom-checkbox float-right mt-2">
                                    <input type="checkbox" name="remember" class="custom-control-input" id="customCheck3">
                                    <label class="custom-control-label" for="customCheck3">
                                        {{ trans('front::messages.auth.remember-me') }}
                                    </label>
                                </div>
                            </div>
                            <div class="form-row mt-3">
                                <button type="submit" class="btn-primary-cm btn-with-icon mx-auto w-100">
                                    <i class="mdi mdi-login-variant"></i>
                                    {{ trans('front::messages.auth.sign-in-to-site') }}
                                </button>
                            </div>
                            <div class="row mt-2">
                                @if (option('forgot_password_link', 'off') == 'on')
                                    <div class="col-md-6">
                                        <div class="form-footer text-right">
                                            <a href="{{ route('password.request') }}" class="d-inline-block mt-2">
                                                {{ trans('front::messages.auth.forget-password') }}</a>
                                        </div>
                                    </div>
                                @endif

                                @if (option('login_with_code', 'off') == 'on')
                                    <div class="col-md-6">
                                        <div class="form-footer text-right">
                                            <a href="{{ route('login-with-code.request') }}"
                                                class="d-inline-block mt-2">{{ trans('front::messages.auth.login-with-one-time-password') }}</a>
                                        </div>
                                    </div>
                                @endif


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
    <script>
        var redirect_url = '{{ $redirect_url }}';
    </script>

    <script src="{{ theme_asset('js/pages/login.js') }}"></script>
@endpush
