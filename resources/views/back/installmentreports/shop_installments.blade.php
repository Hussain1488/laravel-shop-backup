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
                                    <li class="breadcrumb-item">گزارش گیری اقساط
                                    </li>
                                    <li class="breadcrumb-item active">اقساط فروشگاه {{ $shop->nameofstore }}
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

                        <h4 class="card-title">لیست اقساط فروشگاه: {{ $shop->nameofstore }}</h4>

                    </div>
                    <div class="card-content">
                        <div class="container mt-3">

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link {{ $payment_stat == 'wait' ? 'active' : '' }}"
                                        style="font-size: 10px" data-toggle="tab" href="#home">در
                                        انتظار تأیید</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $payment_stat == 'not_paid' ? 'active' : '' }}"
                                        style="font-size: 10px" data-toggle="tab" href="#menu1">اقساط
                                        پرداخت
                                        نشده</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $payment_stat == 'paid' ? 'active' : '' }}"
                                        style="font-size: 10px" data-toggle="tab" href="#menu2">اقساط
                                        پرداخت
                                        شده</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div id="home"
                                    class="container tab-pane {{ $payment_stat == 'wait' ? 'active' : 'fade' }}">
                                    <br>

                                    <form action="{{ route('admin.installments.shop.installments.filter_name') }}"
                                        method="get">
                                        @csrf
                                        <div class="row ">

                                            <div class="col-md-6 col-12 d-flex justify-content-around">
                                                <h4>
                                                    فیلتر بر اساس شماره تلفن
                                                </h4>
                                                <div class="d-flex">
                                                    <input type="text" name="filter" class="form-control w-auto mr-1"
                                                        placeholder="شماره تلفن را برای فیلتر وارد کنید">
                                                    <input readonly type="hidden" name="payment_stat" value="wait">
                                                    <input type="hidden" value="{{ $shop->id }}" name="store"
                                                        id="">
                                                    <input type="submit" class="btn btn-info" value="فیلتر">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                            </div>
                                        </div>
                                    </form>

                                    <div class="row mt-1 ml-2">
                                        <h3>
                                            فروش هایی که در انتظار تأیید است
                                        </h3>
                                    </div>

                                    @if (!$installments->count() > 0)
                                        <div class="row mt-3 ml-2 alret alert-warning p-2 rounded rounded ">
                                            <h4 class="text-danger">
                                                هیچ فروشی برای نمایش وجود ندارد!
                                            </h4>
                                        </div>
                                    @else
                                        @foreach ($installments as $key)
                                            <div class="border rounded p-2 my-1">
                                                <div class="row d-flex justify-content-around">

                                                    <h5 class="">قسط آقای:
                                                        {{ $key->user->username }}
                                                    </h5>
                                                </div>


                                                <div class="row">
                                                    مبلغ کل فروش: (<span class="monyInputSpan">
                                                        {{ $key->Creditamount }}
                                                    </span>) ریال
                                                </div>
                                                <div class="row">
                                                    تعداد قسط:({{ $key->numberofinstallments }})

                                                </div>
                                                <div class="row">
                                                    مبلغ هر قسط:
                                                    (<span class="moneyInutSpan">{{ $key->amounteachinstallment }}</span>)
                                                    ریال

                                                </div>

                                                <div class="row">

                                                    مقدار پیش پرداخت {{ $key->prepaidamount }} ریال

                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="m-3">
                                        {{ $installments->appends(['store' => request('store'), 'filter' => request('filter'), 'user' => request('user'), 'tab' => 'insta', 'page' => $installments->currentPage()])->links() }}
                                    </div>
                                </div>

                                <div id="menu1"
                                    class="container tab-pane {{ $payment_stat == 'not_paid' ? 'active' : 'fade' }}">
                                    <br>

                                    <form action="{{ route('admin.installments.shop.installments.filter_name') }}"
                                        method="get">
                                        @csrf
                                        <div class="row ">

                                            <div class="col-md-6 col-12 d-flex justify-content-around">

                                                <h4>
                                                    فیلتر بر اساس شماره تلفن
                                                </h4>

                                                <div class="d-flex">
                                                    <input type="text" name="filter" class="form-control w-auto mr-1"
                                                        placeholder="شماره تلفن را برای فیلتر وارد کنید">
                                                    <input readonly type="hidden" name="payment_stat" value="not_paid"
                                                        placeholder="شماره تلفن را برای فیلتر وارد کنید">
                                                    <input type="hidden" value="{{ $shop->id }}" name="store"
                                                        id="">
                                                    <input type="submit" class="btn btn-info" value="فیلتر">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row mt-1 ml-2">
                                        <h3>
                                            اقساط پرداخت نشده
                                        </h3>
                                    </div>
                                    @if (!$installments1->count() > 0)
                                        <div class="row mt-3 ml-2 alret alert-warning p-2 rounded">
                                            <h4 class="text-danger">
                                                .هیچ قسطی برای نمایش وجود ندارد!
                                            </h4>
                                        </div>
                                    @else
                                        @foreach ($installments1 as $key)
                                            <div class="border rounded p-2 my-1">
                                                <div class="row d-flex justify-content-around">
                                                    <h5 class="">قسط آقای:
                                                        {{ $key->installments->user->username }}
                                                    </h5>
                                                </div>
                                                <div class="row">
                                                    مبلغ کل فروش: (<span class="monyInputSpan">
                                                        ({{ $key->installments->Creditamount }}
                                                    </span>)
                                                    ریال
                                                </div>
                                                <div class="row">
                                                    قسط شماره ({{ $key->installmentnumber }}) به سر رسید تاریخ
                                                    ({{ jdate($key->duedate)->format('Y/m/d') }})
                                                </div>
                                                <div class="row">
                                                    به مبلغ قسط
                                                    (<span class="monyInputSpan">{{ $key->installmentprice }}</span> )
                                                    ریال
                                                </div>
                                                <div class="row ">
                                                    مقدار پیش پرداخت (<span
                                                        class="monyInputSpan">{{ $key->installments->prepaidamount }}</span>)
                                                    ریال
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="m-3">
                                        {{ $installments1->appends(['store' => request('store'), 'filter' => request('filter'), 'user' => request('user'), 'tab' => 'insta1', 'page' => $installments1->currentPage()])->links() }}
                                    </div>
                                </div>
                                <div id="menu2" {{-- paid installments list --}}
                                    class="container tab-pane {{ request('tab') == 'insta2' ? 'active' : ($payment_stat == 'paid' ? 'active' : 'fade') }}">
                                    <br>
                                    <form action="{{ route('admin.installments.shop.installments.filter_name') }}"
                                        method="get">
                                        @csrf
                                        <div class="row ">
                                            <div class="col-md-6 col-12 d-flex justify-content-around">
                                                <h4>
                                                    فیلتر بر اساس شماره تلفن
                                                </h4>
                                                <div class="d-flex">
                                                    <input type="text" name="filter" class="form-control w-auto mr-1"
                                                        placeholder="شماره تلفن را برای فیلتر وارد کنید">
                                                    <input readonly type="hidden" name="payment_stat" value="paid">
                                                    <input type="hidden" value="{{ $shop->id }}" name="store"
                                                        id="">
                                                    <input type="submit" class="btn btn-info" value="فیلتر">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row mt-1 ml-2">
                                        <h3>
                                            اقساط پرداخت شده
                                        </h3>
                                    </div>
                                    @if (!$installments2->count() > 0)
                                        <div class="row mt-3 ml-2 alret alert-warning p-2 rounded">
                                            <h4 class="text-danger">
                                                هیچ قسطی برای نمایش وجود ندارد!
                                            </h4>
                                        </div>
                                    @else
                                        @foreach ($installments2 as $key)
                                            <div class="border rounded p-2 my-1">
                                                <div class="row d-flex justify-content-around">
                                                    <h5 class="">قسط آقای:
                                                        {{ $key->installments->user->username }}
                                                    </h5>
                                                </div>
                                                <div class="row">
                                                    مبلغ کل فروش: (<span
                                                        class="monyInputSpan">{{ $key->installments->Creditamount }}</span>)
                                                    ریال
                                                </div>
                                                <div class="row">
                                                    قسط شماره ({{ $key->installmentnumber }})

                                                </div>
                                                <div class="row">
                                                    مبلغ قسط
                                                    (<span class="monyInputSpan">{{ $key->installmentprice }}</span>)
                                                    ریال

                                                </div>
                                                <div class="row">
                                                    پرداخت شده در:
                                                    {{ jdate($key->updated_at)->format('Y/m/d H:i:s') }}
                                                </div>
                                                <div class="row">
                                                    مقدار پیش پرداخت (<span
                                                        class="monyInputSpan">{{ $key->installments->prepaidamount }}</span>)
                                                    ریال
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="m-3">
                                        {{ $installments2->appends(['store' => request('store'), 'filter' => request('filter'), 'user' => request('user'), 'tab' => 'insta2', 'page' => $installments2->currentPage()])->links() }}
                                    </div>
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
    <script src="{{ asset('back/assets/js/pages/cooperationSales/store_sales.js') }}"></script>
@endpush
