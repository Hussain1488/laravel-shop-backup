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
                                    <li class="breadcrumb-item active">ویرایش پروفایل
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
                        <h4 class="card-title">اسم کاربر</h4>
                    </div>
                    <div class="card-content">
                        <div class="container mt-3">

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" style="font-size: 10px" data-toggle="tab" href="#home">در
                                        انتظار تأیید</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" style="font-size: 10px" data-toggle="tab" href="#menu1">اقساط
                                        پرداخت
                                        نشده</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" style="font-size: 10px" data-toggle="tab" href="#menu2">اقساط
                                        پرداخت
                                        شده</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" style="font-size: 10px" data-toggle="tab" href="#menu3">خدمات
                                        اینترنتی</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div id="home" class="container tab-pane active"><br>
                                    <div class="row">

                                        <div class="col-md-6 col-12">
                                            <div class="form-group d-flex align-items-center">
                                                <label for="first_name" class="mr-2">
                                                    مقدار اعتبار خرید اقساطی
                                                </label>
                                                <div class="d-flex align-items-baseline">
                                                    <input readonly type="text" placeholder="100,000"
                                                        class="form-control moneyInput" id="first_name" name="first_name"
                                                        value="{{ $user->purchasecredit }}" style="margin-left: 4px">ریال
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group d-flex align-items-center">
                                                <label for="first_name" class="mr-2">
                                                    موجودی نقدی کیف پول </label>
                                                <div class="d-flex align-items-baseline ">
                                                    <input readonly type="text" placeholder="100,000"
                                                        class="form-control moneyInput" id="first_name" name="first_name"
                                                        value="{{ $user->wallet->balance }}" style="margin-left: 4px">ریال
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">

                                        </div>
                                    </div>



                                    <div class="border rounded p-2">
                                        <div class="row text-center" style="flex-direction: column;">
                                            <h5>
                                                قسط فروشگاه احمدی
                                            </h5>
                                        </div>

                                        <div class="row my-1">
                                            <div class="col">
                                                ۱۵ عدد قسط به سر رسید ۲۵ هر ماه به مبلغ قسط ۱۰۰،۰۰۰ ریال
                                            </div>

                                        </div>

                                        <div class="row">
                                            مقدار پیش پرداخت: ۳۰۰،۰۰۰ ریال
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col d-flex align-items-baseline justify-content-center">

                                            <a href="" class="btn btn-primary my-1">تأیید و پرداخت</a>
                                        </div>
                                        <div class="col d-flex align-items-baseline justify-content-center">

                                            <a href="" class="btn btn-danger my-1">انصراف از فروش</a>
                                        </div>
                                    </div>
                                </div>
                                <div id="menu1" class="container tab-pane fade"><br>
                                    <h3>Menu 1</h3>
                                    <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                                        aliquip ex ea commodo consequat.</p>
                                </div>
                                <div id="menu2" class="container tab-pane fade"><br>
                                    <div class="row">

                                        <div class="col-md-6 col-12">
                                            <div class="form-group d-flex align-items-center">
                                                <label for="first_name" class="mr-2">
                                                    مقدار اعتبار خرید اقساطی
                                                </label>
                                                <div class="d-flex">
                                                    <input type="text" placeholder="100,000"
                                                        class="form-control moneyInput" id="first_name" name="first_name">
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group d-flex align-items-center">
                                                <label for="first_name" class="mr-2">
                                                    موجودی نقدی کیف پول </label>
                                                <div class="d-flex ">
                                                    <input type="text" placeholder="100,000"
                                                        class="form-control moneyInput" id="first_name"
                                                        name="first_name">
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">

                                        </div>
                                    </div>
                                    @for ($i = 0; $i <= 3; $i++)
                                        <div class="border rounded p-2 my-1">
                                            <div class="row text-center " style="flex-direction: column;">
                                                <h5>
                                                    قسط فروشگاه احمدی
                                                </h5>
                                            </div>

                                            <div class="row my-1">
                                                <div class="col-5">
                                                    1402/2/2
                                                </div>
                                                <div class="col-7">
                                                    مبلغ قسط ۱۰۰۰،۰۰۰ ریال
                                                </div>

                                            </div>

                                            <div class="row">
                                                مقدار جریمه دیر کرد ۰ ریال
                                            </div>

                                            <div class="row mt-2">
                                                وضعیت: پرداخت شده در تاریخ ۱۴۰۲/۸/۲۵
                                            </div>
                                        </div>
                                    @endfor

                                </div>
                                <div id="menu3" class="container tab-pane fade"><br>
                                    <h3>Menu 3</h3>
                                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                                        doloremque laudantium, totam rem aperiam.</p>
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
    <script src="{{ asset('back/assets/js/pages/users/all.js') }}"></script>
    <script src="{{ asset('back/assets/js/pages/installmentpurchse/create.js') }}"></script>
@endpush
