@extends('back.layouts.master')

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb no-border">
                                    <li class="breadcrumb-item">مدیریت
                                    </li>
                                    <li class="breadcrumb-item">همکاران
                                    </li>
                                    <li class="breadcrumb-item active">ایجاد قسط
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">


                {{--  creating new installments form --}}
                @isset($shop)
                    <section id="main-card" class="card">
                        <div class="card-header">
                            <h4 class="card-title">پنل اصلی فروشات {{ $shop->nameofstore }}</h4>
                            <input type="hidden" value="{{ $shop->storecredit == null ? 0 : $shop->storecredit }}"
                                name="store_creadit" id="store_creadit">

                        </div>

                        <div id="main-card" class="card-content">
                            <div class="card-body">
                                <div class="col-12 col-md-10 offset-md-1">
                                    <form class="form" id="user-create-form"
                                        action="{{ route('admin.cooperationsales.store') }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" value="{{ $shop->id }}" name="store_id" id="">
                                        <div class="form-body">
                                            <div class="row">

                                                <div class="col-md-6 col-12">
                                                    <div class="form-group d-flex align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <input readonly id="purchase_creadite" type="text"
                                                                placeholder="100,000" class="form-control moneyInput"
                                                                id="first_name" name="Creditamount" value="0"
                                                                style="margin-left: 4px">

                                                            ریال
                                                        </div>
                                                        <label for="first_name" class="ml-2">اعتبار کاربر
                                                            خریدار
                                                            <span class="user_title" id="user_title"></span>
                                                            <span class="text-success user_name" id="user_name"></span>

                                                        </label>

                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">

                                                </div>
                                            </div>
                                            <div class="card-header">
                                                <h4 class="card-title">ساخت فروش برای کاربر</h4>
                                            </div>
                                            <div class="row mt-1">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        انتخاب کاربر
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>سرچ بر اساس شماره تلفن</label>

                                                        <select class="form-control user_select2" id="user_select"
                                                            name="user_id">
                                                        </select>

                                                        @error('user_id')
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-1">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        قیمت اصلی کالا
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>قیمت اصلی کالا را وارد کنید.</label>
                                                        <div class="d-flex align-items-center">

                                                            <input id="main_price" type="text" placeholder="100,000"
                                                                class="form-control moneyInput" id="first_name"
                                                                name="Creditamount" style="margin-left: 4px"
                                                                value="{{ old('Creditamount') }}"> ریال
                                                        </div>
                                                        <span class="text-danger price_limit_error"></span>
                                                        @error('Creditamount')
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror



                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-1">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        روش پرداخت
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12 ">
                                                    <div class="form-group">
                                                        <select id="cash_status" type="text" class="form-control "
                                                            name="typeofpayment">
                                                            <option value="cash">نقدی</option>
                                                            <option value="monthly_installment">اقساط ماهانه</option>
                                                            <option value="weekly_installment">اقساط هفته ای</option>
                                                            @if ($shop->no_profit_installment == 'yes')
                                                                <option value="m_none_profit">۴ قسط ماهانه بدون کارمزد
                                                                </option>
                                                                <option value="w_none_profit">۱۲ قسط هفته ای بدون کارمزد
                                                                </option>
                                                            @endif
                                                        </select>
                                                        @error('typeofpayment')
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-1 monthly_instalment">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        تعداد اقساط
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>تعداد اقساط را انتخاب نمایید</label>
                                                        <select disabled id="monthly_payment" type="text"
                                                            class="form-control" name="numberofinstallments">
                                                            <option id="one_month" selected value="1" selected>۱ ماه
                                                            </option>
                                                            <option {{ old('numberofinstallments') == 2 ? 'selected' : '' }}
                                                                value="2">۲ ماه</option>
                                                            <option {{ old('numberofinstallments') == 3 ? 'selected' : '' }}
                                                                value="3">۳ ماه</option>
                                                            <option {{ old('numberofinstallments') == 4 ? 'selected' : '' }}
                                                                value="4">۴ ماه</option>
                                                            <option {{ old('numberofinstallments') == 5 ? 'selected' : '' }}
                                                                value="5">۵ ماه</option>
                                                            <option {{ old('numberofinstallments') == 6 ? 'selected' : '' }}
                                                                value="6">۶ ماه</option>
                                                            <option {{ old('numberofinstallments') == 7 ? 'selected' : '' }}
                                                                value="7">۷ ماه</option>
                                                            <option {{ old('numberofinstallments') == 8 ? 'selected' : '' }}
                                                                value="8">۸ ماه</option>
                                                            <option {{ old('numberofinstallments') == 9 ? 'selected' : '' }}
                                                                value="9">۹ ماه</option>
                                                            <option {{ old('numberofinstallments') == 10 ? 'selected' : '' }}
                                                                value="10">۱۰ ماه</option>
                                                            <option {{ old('numberofinstallments') == 11 ? 'selected' : '' }}
                                                                value="11">۱۱ ماه</option>
                                                            <option {{ old('numberofinstallments') == 12 ? 'selected' : '' }}
                                                                value="12">۱۲ ماه</option>
                                                            <option {{ old('numberofinstallments') == 13 ? 'selected' : '' }}
                                                                value="13">۱۳ ماه</option>
                                                            <option {{ old('numberofinstallments') == 14 ? 'selected' : '' }}
                                                                value="14">۱۴ ماه</option>
                                                            <option {{ old('numberofinstallments') == 15 ? 'selected' : '' }}
                                                                value="15">۱۵ ماه</option>
                                                        </select>
                                                        @error('numberofinstallments')
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-1 weekly_instalment">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        تعداد اقساط
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>تعداد اقساط را انتخاب نمایید</label>
                                                        <select disabled id="weekly_payment" type="text"
                                                            class="form-control" name="numberofinstallments">
                                                            <option id="one_month" selected value="5" selected>۵ هفته
                                                            </option>
                                                            <option {{ old('numberofinstallments') == 10 ? 'selected' : '' }}
                                                                value="10">۱۰ هفته</option>
                                                            <option {{ old('numberofinstallments') == 15 ? 'selected' : '' }}
                                                                value="15">۱۵ هفته</option>
                                                            <option {{ old('numberofinstallments') == 20 ? 'selected' : '' }}
                                                                value="20">۲۰ هفته</option>
                                                            <option {{ old('numberofinstallments') == 25 ? 'selected' : '' }}
                                                                value="25">۲۵ هفته</option>
                                                            <option {{ old('numberofinstallments') == 30 ? 'selected' : '' }}
                                                                value="30">۳۰ هفته</option>
                                                            <option {{ old('numberofinstallments') == 35 ? 'selected' : '' }}
                                                                value="35">۳۵ هفته</option>
                                                            <option {{ old('numberofinstallments') == 40 ? 'selected' : '' }}
                                                                value="40">۴۰ هفته</option>
                                                            <option {{ old('numberofinstallments') == 45 ? 'selected' : '' }}
                                                                value="45">۴۵ هفته</option>
                                                            <option {{ old('numberofinstallments') == 50 ? 'selected' : '' }}
                                                                value="50">۵۰ هفته</option>
                                                        </select>
                                                        @error('numberofinstallments')
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-1">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        مبلغ پیش پرداخت
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <div class="d-flex align-items-center">

                                                            <input id="prepayment" readonly type="text"
                                                                class="form-control moneyInput" name="prepaidamount"
                                                                style="margin-left: 4px">
                                                            ریال
                                                        </div>
                                                        @error('prepaidamount')
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-1 installment_fileds_container">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        مبلغ هر قسط
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <div class="d-flex align-items-center">

                                                            <input readonly id="each_pay" type="text"
                                                                class="form-control moneyInput" name="amounteachinstallment"
                                                                style="margin-left: 4px">
                                                            ریال
                                                        </div>
                                                        @error('amounteachinstallment')
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-12">
                                                <button type="button" id="submit_button"
                                                    data-end-date="{{ $shop->enddate }}"
                                                    class="btn btn-primary mr-1 mb-1 waves-effect waves-light">
                                                    تأیید نهایی
                                                </button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                    </section>
                @else
                    <section id="main-card" class="card">
                        <div class="card-header m-3 ">
                            <h3 class="text-danger">فروشگاهی برای شما ایجاد نشده است</h3>
                        </div>
                    </section>
                @endisset

                <!-- Description -->


            </div>
        </div>
    </div>


    <div class="container" dir="rtl">
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-danger">هشدار!</h4>
                    </div>
                    <div class="modal-body" id="modal_body">

                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn btn-default text-danger" data-dismiss="modal">بستن</button>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <div class="container" dir="rtl">
        <div class="modal fade" id="myModal2" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-danger">هشدار!</h4>
                    </div>
                    <div class="modal-body">
                        <p>مقدار قیمت اصلی نباید از اعتبار فروشگاه بیشتر باشد.</p>
                        <p>
                            اعتبار فروشگاه شما فعلا : <span id="store_creadit2" class="text-info"></span>ریال میباشد.
                        </p>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn btn-default text-danger" data-dismiss="modal">بستن</button>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <div class="container" dir="rtl">
        <div class="modal fade" id="myModal3" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-danger">هشدار!</h4>
                    </div>
                    <div class="modal-body">
                        <p>مقدار قیمت اصلی کالا باید بیشتر از <span class="text-danger">( 0 )</span> باشد.</p>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn btn-default text-danger" data-dismiss="modal">بستن</button>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- styling for modals --}}
    <style>
        .modal a.close-modal[class*="icon-"] {
            direction: rtl;
            top: -10px;
            right: -10px;
            width: 20px;
            height: 20px;
            color: #fff;
            line-height: 1.25;
            text-align: center;
            text-decoration: none;
            text-indent: 0;
            background: #900;
            border: 2px solid #fff;
            -webkit-border-radius: 26px;
            -moz-border-radius: 26px;
            -o-border-radius: 26px;
            -ms-border-radius: 26px;
            -moz-box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
            -webkit-box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
            box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
        }
    </style>
@endsection

@include('back.partials.plugins', ['plugins' => ['jquery.validate']])

@php
    $help_videos = [config('general.video-helpes.users')];
@endphp

@push('scripts')
    <script>
        var user = @json($users);
        var url = '{{ route('admin.user.searchUser') }}';
    </script>
    <script src="{{ asset('back/assets/js/pages/cooperationSales/create.js') }}?v=51"></script>
@endpush
