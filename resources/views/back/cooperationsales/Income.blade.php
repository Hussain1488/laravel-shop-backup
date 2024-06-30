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
                                    <li class="breadcrumb-item">مدیریت کاربران
                                    </li>
                                    <li class="breadcrumb-item active">ایجاد کاربر
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <section class="card">
                    <div class="card-header">
                        <h4 class="card-title">اقساط پرداخت شده کاربر</h4>
                    </div>
                    <div class="card-content">
                        <div class="container mt-3">


                            <!-- Tab panes -->
                            <div id="" class="container tab-pane mb-2"><br>
                                <div class="row">

                                    <div class="col-md-6 col-12">
                                        <div class="form-group d-flex align-items-center">
                                            <label for="first_name" class="mr-2">
                                                مقدار اعتبار خرید اقساطی
                                            </label>
                                            <div class="d-flex align-items-center">
                                                <input type="text" placeholder="100,000" class="form-control moneyInput"
                                                    id="first_name" name="first_name" style="margin-left:4px;">
                                                <span>ریال</span>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">

                                    </div>
                                </div>
                                <div class="border rounded p-2">
                                    <div class="row text-center" style="flex-direction: column;">
                                        <h5>
                                            آقای محسن حسین زاده
                                        </h5>
                                    </div>


                                    <div class="row">
                                        مبلغ کل فروش: ۱۰۰،۰۰۰ ریال
                                    </div>
                                    <div class="row">
                                        ۱۵ عدد قسط به سر رسیده ۲۵ هر ماه به مبلغ قسط ۱۰۰،۰۰۰ ریال
                                    </div>

                                    <div class="row mt-2">
                                        تاریخ فروش:‌۱۴۰۲/۲/۱۳
                                    </div>
                                    <div class="row my-1 px-1 text-center">
                                        ۱۴ روز بعد از فروش گزینه درخواست تسویه فعال میشود و مبلغ بعد از کسر کارمزد به کیف
                                        پول شما انتقال پیدا میکند.
                                    </div>
                                </div>
                                <div class="row mx-4" style="align-items:center ;display: flex;flex-direction: column;">
                                    <a href="" class="btn btn-danger my-1 " style="width: 200px">درخواست تسویه </a>
                                </div>
                                <div class="row mt-4 mb-2">
                                    <p class="text-center">
                                        در صورتی که تأمین کننده کالا فردی غیر از شما میباشد فاکتور فروش از نام فروشنده به
                                        خریدار تهیه و در قسمت عکس فاکتور آپلود نمایید.
                                    </p>
                                </div>
                                <div class="row">
                                    <input type="file" accept=".pdf, .doc" accept-language="fa">
                                    <label for="">افزودن عکس فاکتور فروش</label>
                                    {{-- <br>
                                        <input type="file" class="custom-file-input" id="customFile">
                                        <label class="custom-file-label" for="customFile">انتخاب فایل</label> --}}
                                </div>
                                <div class="row mt-1">
                                    <input type="text">
                                    <label for="">ثبت شماره شبا</label>
                                </div>

                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('back/assets/js/pages/users/all.js') }}?v=51"></script>
    <script src="{{ asset('back/assets/js/pages/cooperationSales/create.js') }}?v=51"></script>
@endpush
