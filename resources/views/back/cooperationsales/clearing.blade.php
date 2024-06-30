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
                                    <li class="breadcrumb-item active">درخواست تسویه حساب
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
                        <h4 class="card-title">فروش های پرداخت شده کاربر</h4>
                    </div>
                    <div class="card-content">
                        <div class="container mt-3">
                            <div id="home" class="container tab-pane active"><br>
                                <form action="{{ route('admin.cooperationsales.clearing.store') }}"
                                    enctype="multipart/form-data" id="clearing_form" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ isset($store->id) ? $store->id : 0 }}" name="store">
                                    <div class="row">

                                        <div class="col-md-6 col-12">
                                            <div class="form-group d-flex align-items-center">
                                                <label for="first_name" class="mr-2">
                                                    موجودی قابل برداشت
                                                </label>
                                                <div class="d-flex align-items-center">
                                                    <input readonly type="text" placeholder="100,000"
                                                        class="form-control moneyInput" id="total_amount" name="first_name"
                                                        value="{{ $store->salesamount != null ? ($store->salesamount != 0 ? $store->salesamount : 0) : 0 }}"
                                                        style="margin-left: 4px;">
                                                    <span>ریال</span>
                                                </div>


                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">

                                        </div>
                                    </div>
                                    <div class="row my-3">
                                        <div class="col">
                                            <p class="">
                                                لطفا بررسی نمایید که فاکتور هایی که تاریخ روز کاری آن سررسیده است را تسویه
                                                بزنید، تا به موجودی کیف پول شما واریز شده و قابل برداشت باشد.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <input type="text" class="form-control moneyInput" id="reqest_amount"
                                                name="depositamount" value="{{ old('depositamount', 0) }}"
                                                style="margin-left: 4px;"> ریال
                                        </div>
                                        <label for="first_name" class="ml-2">
                                            مبلغ درخواست واریز
                                        </label>
                                        @error('depositamount')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    {{-- <div class="input-group">
                                    <input type="text" aria-label="First name" class="form-control">
                                    <input type="text" style="width: 20px; border-right: none;" class="form-control" value="ریال" disabled>
                                </div> --}}
                                    <div class="row mt-4 mb-2">
                                        <p class="text-center">
                                            در صورتی که تأمین کننده کالا فردی غیر از شما میباشد فاکتور فروش از نام فروشنده
                                            به
                                            خریدار تهیه و در قسمت عکس فاکتور آپلود نمایید.
                                        </p>


                                    </div>
                                    <div class="row">
                                        <input multiple class="imageInput" type="file" accept=".pdf, .doc"
                                            accept-language="fa" name="factor[]">
                                        <label for="">افزودن عکس فاکتور فروش</label>

                                    </div>
                                    <div class="imgContainer"></div>
                                    @error('factor')
                                        <span class="text-danger">
                                            {{ $message }}
                                        </span>
                                    @enderror


                                    <div class="form-group d-flex align-items-center my-1">
                                        <div class="d-flex align-items-center">
                                            <input type="text" id="shaba_number" class="form-control" name="shabanumber"
                                                style="margin-left: 4px;">
                                        </div>
                                        <label for="first_name" class="ml-2">
                                            شماره ثبت شبا </label>
                                    </div>
                                    @error('shabanumber')
                                        <span class="text-danger">
                                            {{ $message }}
                                        </span>
                                    @enderror

                                    @if (session('register_number'))
                                        <div class="my-4 border rounded bg-danger text-dark p-1 text-center">
                                            ثبت شد <span class="badge badge-success"> {{ session('register_number') }}
                                            </span> شماره
                                            پیگیری را روی فاکتور بنویسید
                                            <br>
                                            تا همکاران ما فاکتور را به شما مراجعه کنند و به صورت فیزیکی فاکتور ها را تحویل
                                            بگیرند.
                                        </div>
                                    @endif

                                    <div class="row mb-2" style="align-items:center ;display: flex;flex-direction: column;">
                                        <input type="button" id="clearing_button" value="تأیید"
                                            class="btn btn-lg btn-success">
                                    </div>
                                </form>
                            </div>

                        </div>
                </section>
                <div class="container" dir="rtl">
                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title text-danger">هشدار!</h4>
                                </div>
                                <div class="modal-body">

                                </div>
                                <div class="modal-footer d-flex justify-content-center">
                                    <button type="button" class="btn btn-default text-danger"
                                        data-dismiss="modal">بستن</button>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
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

@push('scripts')
    <script>
        var url = '';
    </script>
    <script src="{{ asset('back/assets/js/pages/users/all.js') }}"></script>
    <script src="{{ asset('back/assets/js/pages/cooperationSales/create.js') }}?v=52"></script>
@endpush
