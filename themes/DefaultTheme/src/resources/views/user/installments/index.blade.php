@extends('front::layouts.master')

@section('content')
    <!-- Start Content -->

    {{-- @use \Morilog\Jalali\Jalalian; --}}
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">


                    <div class="row">
                        <div class=" page col-12">
                            <div class="section-title text-sm-title title-wide mb-1 no-after-title-wide dt-sl px-res-1">
                                {{-- <h2>{{ trans('front::messages.wallet.wallet-history') }}</h2>
                                <a href="{{ route('front.wallet.create') }}"
                                    class="m-0 d-block">{{ trans('front::messages.wallet.increase-wallet-inventory') }}</a> --}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="page dt-sl dt-sn pt-3">
                                <div class="content-body">
                                    <section class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">{{ $user->first_name . ' ' . $user->last_name }}</h4>
                                        </div>
                                        <div class="card-content">
                                            <div class="container mt-3">
                                                {{--
                                                <input type="button" class="btn btn-primary" value="Click Me"
                                                    id="myButton"> --}}
                                                @if (session('warning'))
                                                    <div class="alert alert-danger" role="alert">
                                                        {{ session('warning') }}
                                                    </div>
                                                @endif
                                                @if (session('success'))
                                                    <div class="alert alert-success" role="alert">
                                                        {{ session('success') }}
                                                    </div>
                                                @endif
                                                <div class="alert alert-danger d-none alert-message">

                                                </div>
                                                <div class="row">

                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group d-flex align-items-center">
                                                            <label for="first_name" class="mr-2">
                                                                مقدار اعتبار خرید اقساطی
                                                            </label>
                                                            <div class="d-flex">
                                                                <input readonly type="text"
                                                                    class="form-control moneyInput" id="credit-value"
                                                                    name="first_name"
                                                                    value="{{ $user->purchasecredit ?? 0 }}"
                                                                    style="margin-left: 4px">
                                                                <span> ریال</span>

                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 col-12 d-flex justify-content-between">
                                                        <div class="form-group d-flex align-items-center">
                                                            <label for="first_name" class="mr-2">
                                                                موجودی نقدی کیف پول </label>
                                                            <div class="d-flex ">
                                                                <input readonly type="text"
                                                                    class="form-control moneyInput" id="wallet-value"
                                                                    value="{{ $user->wallet->balance ?? 0 }}"
                                                                    style="margin-left: 4px"><span> ریال</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12 d-flex justify-content-end">
                                                        <div class=""><input type="button" value="شارژ کیف پول"
                                                                class="btn btn-success" id="wallet_recharg_button1"></div>

                                                    </div>
                                                </div>
                                                <!-- Nav tabs -->
                                                <ul class="nav nav-tabs">
                                                    <li class="nav-item">
                                                        <a class="nav-link {{ request('tab') == 'insta1' || request('tab') == 'insta2' ? '' : 'active' }}"
                                                            style="font-size: .625rem" data-toggle="tab" href="#home">در
                                                            انتظار تأیید</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link {{ request('tab') == 'insta1' ? 'active' : '' }}"
                                                            style="font-size: .625rem" data-toggle="tab"
                                                            href="#menu1">اقساط
                                                            پرداخت
                                                            نشده</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link {{ request('tab') == 'insta2' ? 'active' : '' }}"
                                                            style="font-size: .625rem" data-toggle="tab"
                                                            href="#menu2">اقساط
                                                            پرداخت
                                                            شده</a>
                                                    </li>

                                                </ul>



                                                <!-- Tab panes -->
                                                <div class="tab-content">
                                                    <div id="home"
                                                        class="container tab-pane {{ request('tab') == 'insta1' || request('tab') == 'insta2' ? 'fade' : 'active' }} my-2 py-3">
                                                        <br>

                                                        @if ($installmentsm->count() > 0)
                                                            @foreach ($installmentsm as $key)
                                                                <div class="border rounded p-2 my-2">
                                                                    <div class="row text-center"
                                                                        style="flex-direction: column;">
                                                                        <h5>
                                                                            فروشگاه: {{ $key->store->nameofstore }}
                                                                        </h5>
                                                                    </div>

                                                                    <div class="row my-1">
                                                                        <div class="col">
                                                                            مبلغ خرید: <span
                                                                                class="moneyInputSpan">{{ $key->Creditamount }}</span>
                                                                            ریال
                                                                        </div>

                                                                    </div>
                                                                    <div class="row px-3">
                                                                        <div class="">

                                                                            نوع خرید:
                                                                            @if ($key->typeofpayment == 'cash')
                                                                                نقدی
                                                                            @elseif ($key->typeofpayment == 'monthly_installment')
                                                                                اقاسط ماهانه
                                                                            @elseif ($key->typeofpayment == 'weekly_installment')
                                                                                اقساط هفته ای
                                                                            @elseif ($key->typeofpayment == 'w_none_profit')
                                                                                اقساط بدون کارمز ۱۲ هفته ای
                                                                            @elseif ($key->typeofpayment == 'm_none_profit')
                                                                                اقساط بدون کارمز ۴ ماهه
                                                                            @endif
                                                                        </div>

                                                                    </div>
                                                                    <div class="row px-3">
                                                                        @if ($key->typeofpayment !== 'cash')
                                                                            <div class="">
                                                                                مقدار پیش پرداخت: <span
                                                                                    class="moneyInputSpan">
                                                                                    {{ $key->prepaidamount }} </span> ریال
                                                                            </div>
                                                                        @else
                                                                            <div class="">
                                                                                مبلغ قابل پرداخت: <span
                                                                                    class="moneyInputSpan">
                                                                                    {{ $key->prepaidamount }} </span> ریال
                                                                            </div>
                                                                        @endif

                                                                    </div>

                                                                    <div class="row px-3">

                                                                        @if ($key->typeofpayment !== 'cash')
                                                                            <div class="">
                                                                                {{ $key->numberofinstallments }} قسط به
                                                                                مبلغ:
                                                                                <span
                                                                                    class="moneyInputSpan">{{ $key->amounteachinstallment }}</span>
                                                                                ریال
                                                                            </div>
                                                                        @endif

                                                                        <div class="col d-flex justify-content-end">

                                                                            <a href="{{ route('front.installments.usrestatus.refuse', [$key->id]) }}"
                                                                                class="btn btn-warning ml-1"
                                                                                style="">انصراف</a>
                                                                            <input type="button"
                                                                                data-amount="{{ $key->Creditamount }}"
                                                                                data-prepay="{{ $key->prepaidamount }}"
                                                                                data-url="{{ route('front.wallet.codeGenerate', ['PRE_PAY_CODE']) }}"
                                                                                data-href="{{ route('front.installments.usrestatus.edit', [$key->id]) }}"
                                                                                class="btn btn-success smsGeneratButton pre_pay_button"
                                                                                id="pre_pay_button" style=""
                                                                                value="پرداخت">
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="alert alert-warning">
                                                                قسطی برای شما وجود ندارد
                                                            </div>
                                                        @endif
                                                        <div class="m-2">
                                                            {{ $installmentsm->appends(['tab' => 'insta', 'page' => $installmentsm->currentPage()])->links('front::components.paginate') }}
                                                        </div>

                                                    </div>

                                                    <div id="menu1"
                                                        class="container tab-pane {{ request('tab') == 'insta1' ? 'active' : 'fade' }} my-2 py-3">
                                                        <br>
                                                        @if ($userstat > 0)
                                                            @foreach ($installmentsm1 as $key)
                                                                {{-- @foreach ($value->installments as $key) --}}
                                                                @if ($key->paymentstatus == 0)
                                                                    <div class="border rounded p-2 my-1">
                                                                        <div class="row text-center "
                                                                            style="flex-direction: column;">
                                                                            <h5>
                                                                                اقساط فروشگاه:

                                                                                {{ $key->installments->store->nameofstore ?? '...' }}
                                                                            </h5>
                                                                        </div>

                                                                        <div class="row mr-2">

                                                                            مبلغ کل فروش: <span class="moneyInputSpan">
                                                                                {{ $key->installments->Creditamount }}
                                                                            </span>
                                                                            ریال
                                                                        </div>

                                                                        <div class="row m-2">
                                                                            مقدار جریمه دیر کرد <span class="text-danger">
                                                                                (بعدا محاسبه
                                                                                میگردد)
                                                                            </span>
                                                                        </div>
                                                                        <div class="row mr-2">

                                                                            مبلغ قسط: <span class="moneyInputSpan">
                                                                                {{ $key->installmentprice }}
                                                                            </span>
                                                                            ریال
                                                                        </div>



                                                                        <div class="row ">
                                                                            <div class="col m-2">
                                                                                قسط
                                                                                {{ $key->installments->typeofpayment == 'weekly_installment' || $key->installments->typeofpayment == 'w_none_profit' ? 'هفته' : 'ماه' }}
                                                                                {{ $key->installmentnumber }}
                                                                                به
                                                                                سر
                                                                                رسید تاریخ:
                                                                                {{ jdate($key->duedate)->format('Y/m/d') }}
                                                                            </div>
                                                                            <div class="col  d-flex justify-content-end">
                                                                                @if ($key->state == 1)
                                                                                    <input type='button'
                                                                                        data-amount="{{ $key->installmentprice }}"
                                                                                        data-url="{{ route('front.wallet.codeGenerate', ['INSTA_CODE']) }}"
                                                                                        data-href="{{ route('front.installments.paymentStatus.edit', ['id1' => $key->id, 'id2' => $key->installments->id]) }}"
                                                                                        class="btn btn-info btn-sm insta_pay_button"
                                                                                        style=""value="پرداخت"
                                                                                        id="insta_pay_button" />
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                {{-- @endforeach --}}
                                                            @endforeach
                                                        @else
                                                            <div class="alert alert-warning">
                                                                قسطی برای شما وجود ندارد
                                                            </div>
                                                        @endif
                                                        <div class="m-2">
                                                            {{ $installmentsm1->appends(['tab' => 'insta1', 'page' => $installmentsm1->currentPage()])->links('front::components.paginate') }}
                                                        </div>

                                                    </div>
                                                    <div id="menu2"
                                                        class="container tab-pane {{ request('tab') == 'insta2' ? 'active' : 'fade' }} my-2 py-3">
                                                        <br>

                                                        @if ($paystatus > 0)
                                                            @foreach ($installmentsm2 as $value)
                                                                <div class="border rounded p-2 my-1">
                                                                    <div class="row text-center "
                                                                        style="flex-direction: column;">
                                                                        <h5>
                                                                            اقساط فروشگاه:
                                                                            {{ $value->installments->store->nameofstore ?? '...' }}
                                                                        </h5>
                                                                    </div>

                                                                    <div class="row mr-2">

                                                                        مبلغ کل فروش <span class="moneyInputSpan">
                                                                            {{ $value->Creditamount }} </span>
                                                                        ریال

                                                                    </div>

                                                                    <div class="row m-2">
                                                                        مقدار جریمه دیر کرد ۰ ریال
                                                                    </div>
                                                                    <div>

                                                                    </div>

                                                                    <div class="row m-2">
                                                                        پرداخت شده در تاریخ:
                                                                        {{ jdate(\Carbon\Carbon::parse($value->updated_at))->format('Y/m/d') }}
                                                                    </div>
                                                                    <div class="row mr-2">

                                                                        مبلغ قسط: <span class="moneyInputSpan">
                                                                            {{ $value->installmentprice }}
                                                                        </span>
                                                                        ریال
                                                                    </div>
                                                                    <div class="row m-2">
                                                                        قسط
                                                                        {{ $value->installments->typeofpayment == 'weekly_installment' ? 'هفته' : 'ماه' }}
                                                                        {{ $value->installmentnumber }}
                                                                        به
                                                                        سر
                                                                        رسید تاریخ:
                                                                        {{ jdate(\Carbon\Carbon::parse($value->duedate))->format('Y/m/d') }}
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="alert alert-warning">
                                                                قسطی برای شما وجود ندارد
                                                            </div>
                                                        @endif
                                                        <div class="m-2">
                                                            {{ $installmentsm2->appends(['tab' => 'insta2', 'page' => $installmentsm2->currentPage()])->links('front::components.paginate') }}
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </section>


                                </div>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </main>

    <!-- End Content -->
@endsection
@include('back.partials.plugins', ['plugins' => ['jquery.validate']])
@push('scripts')
    <!-- show Modal -->
    <div class="modal fade" id="rechargeForm1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">شارژ کیف پول:<span class="text-success" id="deposit_amount_show"></span>
                    </h4>
                </div>
                <hr />

                <!-- Modal body -->
                <div class="modal-body p-2">
                    {{-- <form id="payment_form1" action="{{ route('front.wallet.rechargeVarify') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input id="user_id" type="hidden" value="{{ Auth::user()->id }}" name="user_id">
                        <div class="d-flex justify-content-around my-1">
                            <div class="col">
                                <label for="">مقدار واریز:</label>
                            </div>
                            <div class="col">
                                <input class="form-control moneyInput" name="recharge_amount">
                            </div>
                        </div>
                        <div class="row d-flex justify-center my-1">
                            <div id="validation-messages1"></div>

                            <span class="text-danger" style="display: none">لطفا فرم را دقیق پر کرده بعد کلید
                                تأیید را بزنید.</span>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <input type="submit" id="submit_form_pay1" class="btn btn-success" value="تأیید">
                            <input type="button" class="btn btn-danger" data-dismiss="modal" value="انصراف">
                        </div>
                    </form> --}}
                    <form id="wallet-create-form" action="{{ route('front.wallet.store') }}" class="setting_form"
                        method="POST">
                        @csrf

                        <div class="row d-flex justify-content-center">
                            <div class="col-lg-8">
                                <div class="form-row-title">
                                    <h6>{{ trans('front::messages.wallet.amount') }}
                                        ({{ trans('front::messages.currency.prefix') }}{{ trans('front::messages.currency.suffix') }})
                                    </h6>
                                </div>
                                <div class="form-row form-group">
                                    <input type="number" class="form-control input-ui pr-2 amount-input" name="amount">
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="col-lg-8">
                                <div class="form-row-title">
                                    <h6>{{ trans('front::messages.wallet.select-payment-gateway') }}</h6>
                                </div>
                                <div class="form-row form-group">
                                    <select class="form-control py-0 gateway-select" name="gateway" required>
                                        <option class="" value="">
                                            {{ trans('front::messages.wallet.select') }}</option>
                                        @foreach ($gateways as $gateway)
                                            <option class="" value="{{ $gateway->key }}">
                                                {{ $gateway->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-row mt-3 justify-content-center">
                            <button id="submit-btn" type="submit" class="btn-primary-cm btn-with-icon ml-2">
                                <i class="mdi mdi-arrow-left"></i>
                                {{ trans('front::messages.wallet.increase-inventory') }}
                            </button>
                        </div>
                    </form>

                </div>

                <!-- Modal footer -->

                <div class="modal-footer">

                </div>

            </div>
        </div>
    </div>
    <!-- show Modal -->
    <div class="modal fade" id="smsVarifyModal1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <span id="code_error1" class="alert alert-danger d-none m-2" role="alert">
                </span>
                <br>
                <div class="alert alert-success d-none" id="success-alert2"></div>

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title"><span class="text-info" id="operation_title"></span>
                    </h4>
                </div>
                <hr />

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="message-light">
                        {{ trans('front::messages.auth.for-mobile-number') }} {{ $user->username }}
                        {{ trans('front::messages.auth.confirmation-code-sent') }}
                    </div>


                    <form id="code_varification1" action="{{ route('front.wallet.sentCode') }}" method="post">
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
                        <div class="form-row mt-2 resent-counter1" id="resent-counter2">
                            <span
                                class="text-primary">{{ trans('front::messages.auth.retrieve-verification-code') }}</span>
                            (<p data-action="{{ route('front.wallet.codeGenerate', ['RESEND_VERIFY_CODE']) }}"
                                id="countdown-verify-end2"></p>)
                        </div>
                        <input type="button" class="btn btn-info mb-1 d-none " id='sendAgain1' value="ارسال مجدد">

                        <div class="form-row mt-3">
                            <button data-url="{{ route('front.wallet.sentCode') }}" type="button" id="sendCode1"
                                class="btn-primary-cm btn-with-icon mx-auto w-100">
                                <i class="mdi mdi-check"></i>
                                تأیید پرداخت
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Modal footer -->

                <div class="modal-footer">

                </div>

            </div>
        </div>
    </div>

    <script>
        $('refuse_button').on('click', function() {
            $('#history-show-modal').modal('show');

        });
        var codeResentAddress = "{{ route('front.resentSms') }}";
    </script>

    <script src="{{ theme_asset('js/vendor/countdown.min.js') }}"></script>
    <script src="{{ theme_asset('js/pages/installments/index.js') }}"></script>
    <script src="{{ theme_asset('js/pages/otp.js') }}"></script>
    <script src="{{ theme_asset('js/pages/wallet/create.js') }}"></script>

    <script src='{{ asset('front/script.js') }}'></script>
@endpush
