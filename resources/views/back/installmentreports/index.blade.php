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
                                    <li class="breadcrumb-item active">لیست تمامی اقساط
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
                        {{-- @isset($store) --}}
                        <h4 class="card-title">لیست تمامی اقساط</h4>

                    </div>
                    <div class="card-content">
                        <div class="container mt-3">

                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link {{ request('tab') == 'insta' ? 'active' : ($payment_stat == 'wait' ? 'active' : '') }} "
                                        style="font-size: 10px" data-toggle="tab" href="#home">در
                                        انتظار تأیید</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('tab') == 'insta1' ? 'active' : ($payment_stat == 'not_paid' ? 'active' : '') }}"
                                        style="font-size: 10px" data-toggle="tab" href="#menu1">اقساط
                                        پرداخت
                                        نشده</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('tab') == 'insta2' ? 'active' : ($payment_stat == 'paid' ? 'active' : '') }}"
                                        style="font-size: 10px" data-toggle="tab" href="#menu2">اقساط
                                        پرداخت
                                        شده</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">

                                {{-- not validated and not paid prepayment of installments list --}}
                                <div id="home"
                                    class="container tab-pane {{ request('tab') == 'insta' ? 'active' : ($payment_stat == 'wait' ? 'active' : 'fade') }}">
                                    <br>

                                    <form action="{{ route('admin.installments.filter') }}" method="get">
                                        @csrf
                                        <div class="row ">

                                            <div class="col-md-6 col-12 d-flex justify-content-around">

                                                <h4>
                                                    فیلتر بر اساس شماره تلفن
                                                </h4>

                                                <div class="d-flex">
                                                    <input type="text" name="filter" class="form-control w-auto mr-1"
                                                        placeholder="شماره تلفن را برای فیلتر وارد کنید">
                                                    <input type="submit" class="btn btn-info" value="فیلتر">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                            </div>
                                        </div>
                                    </form>

                                    <div class="row mt-1 ml-2">
                                        <h3>
                                            لیست فروشات در انتظار تأیید:
                                        </h3>
                                    </div>

                                    @if (!$installments->count() > 0)
                                        <div class="alert alert-warning p-2 my-1">
                                            <span class="text-danger">

                                                هیچ قسطی برای نمایش وجود ندارد!
                                            </span>
                                        </div>
                                    @else
                                        @foreach ($installments as $key)
                                            <div class="border rounded p-2 my-1">
                                                <div class="row d-flex justify-content-around">
                                                    <a class="btn"
                                                        href="{{ route('admin.installments.shop.installments', [$key->store->id, 'wait']) }}">
                                                        <h5 class="alert alert-success">
                                                            فروشنده: {{ $key->store->nameofstore }}
                                                        </h5>
                                                    </a>
                                                    <form action="{{ route('admin.installments.filter') }}" method="get">
                                                        @csrf
                                                        <input type="hidden" value="{{ $key->user->username }}"
                                                            name="filter" id="">
                                                        <button class="btn" style="border:none; background-color:none"
                                                            type="submit">
                                                            <h5 class="alert alert-success">خریدار:
                                                                {{ $key->user->username }}
                                                            </h5>
                                                        </button>
                                                    </form>
                                                </div>


                                                <div class="row">
                                                    مبلغ کل فروش: ( <span class="monyInputSpan">
                                                        {{ $key->Creditamount }}
                                                    </span> ) ریال
                                                </div>
                                                <div class="row">
                                                    ({{ $key->numberofinstallments }})
                                                    عدد قسط
                                                    به مبلغ قسط
                                                    ( <span class="monyInputSpan">
                                                        {{ $key->amounteachinstallment }} </span>) ریال
                                                </div>

                                                <div class="row">

                                                    مقدار پیش پرداخت (<span class="monyInputSpan">
                                                        {{ $key->prepaidamount }} </span>) ریال

                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="m-3">
                                        {{ $installments->appends(['tab' => 'insta', 'filter' => request('filter'), 'page' => $installments->currentPage()])->links() }}
                                    </div>

                                </div>
                                {{--  not payd installments which are paid prepayment of isntallments list --}}
                                <div id="menu1"
                                    class="container tab-pane {{ request('tab') == 'insta1' ? 'active' : ($payment_stat == 'not_paid' ? 'active' : 'fade') }}">
                                    <br>

                                    <form action="{{ route('admin.installments.filter1') }}" method="get">
                                        @csrf
                                        <div class="row ">

                                            <div class="col-md-6 col-12 d-flex justify-content-around">

                                                <h4>
                                                    فیلتر بر اساس شماره تلفن
                                                </h4>

                                                <div class="d-flex">
                                                    <input type="text" name="filter1" class="form-control w-auto mr-1"
                                                        placeholder="شماره تلفن را برای فیلتر وارد کنید">
                                                    <input type="submit" class="btn btn-info" value="فیلتر">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row mt-1 ml-2">
                                        <h3>
                                            لیست اقساط پرداخت نشده:
                                        </h3>
                                    </div>

                                    @if (!$installments1->count() > 0)
                                        <div class="alert alert-warning p-2 my-1">
                                            <span class="text-danger">

                                                هیچ قسطی برای نمایش وجود ندارد!
                                            </span>
                                        </div>
                                    @else
                                        @foreach ($installments1 as $value)
                                            {{-- @if ($key->paymentstatus == 0) --}}
                                            <div class="border rounded p-2 my-1">
                                                <div class="row d-flex justify-content-around">
                                                    <a class="btn"
                                                        href="{{ route('admin.installments.shop.installments', [$value->installments->store_id, 'not_paid']) }}">
                                                        <h5 class="alert alert-success">
                                                            فروشنده: {{ $value->installments->store->nameofstore }}
                                                        </h5>
                                                    </a>
                                                    <form action="{{ route('admin.installments.filter1') }}"
                                                        method="get">
                                                        @csrf
                                                        <input type="hidden"
                                                            value="{{ $value->installments->user->username }}"
                                                            name="filter1" id="">
                                                        <button class="btn" style="border:none; background-color:none"
                                                            type="submit">
                                                            <h5 class="alert alert-success">خریدار:
                                                                {{ $value->installments->user->username }}
                                                            </h5>
                                                        </button>
                                                    </form>
                                                </div>


                                                <div class="row">
                                                    مبلغ کل فروش: (<span class="monyInputSpan">
                                                        {{ $value->installments->Creditamount }} </span>) ریال
                                                </div>
                                                <div class="row">
                                                    قسط شماره ({{ $value->installmentnumber }})به سر رسید
                                                    ({{ jdate($value->duedate)->format('Y/m/d') }})
                                                </div>
                                                <div class="row">
                                                    به مبلغ قسط
                                                    (<span class="monyInputSpan ">
                                                        {{ $value->installmentprice }} </span>) ریال
                                                </div>
                                                <div class="row">

                                                    مقدار پیش پرداخت (<span class="monyInputSpan ">
                                                        {{ $value->installments->prepaidamount }} </span>) ریال

                                                </div>
                                            </div>
                                            {{-- @endif --}}
                                        @endforeach
                                    @endif
                                    <div class="m-3">
                                        {{ $installments1->appends(['tab' => 'insta1', 'filter1' => request('filter1'), 'page' => $installments1->currentPage()])->links() }}
                                    </div>
                                </div>
                                <div id="menu2" {{-- paid installments list --}}
                                    class="container tab-pane {{ request('tab') == 'insta2' ? 'active' : ($payment_stat == 'paid' ? 'active' : 'fade') }}">
                                    <br>

                                    <form action="{{ route('admin.installments.filter2') }}" method="get">
                                        @csrf
                                        <div class="row ">

                                            <div class="col-md-6 col-12 d-flex justify-content-around">

                                                <h4>
                                                    فیلتر بر اساس شماره تلفن
                                                </h4>

                                                <div class="d-flex">
                                                    <input type="text" name="filter1" class="form-control w-auto mr-1"
                                                        placeholder="شماره تلفن را برای فیلتر وارد کنید">
                                                    <input type="submit" class="btn btn-info" value="فیلتر">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                            </div>
                                        </div>
                                    </form>

                                    <div class="row mt-1 ml-2">
                                        <h3>
                                            لیست اقساط پرداخت شده:
                                        </h3>
                                    </div>


                                    @if (!$installments2->count() > 0)
                                        <div class="alert alert-warning p-2 my-1">
                                            <span class="text-danger">

                                                هیچ قسطی برای نمایش وجود ندارد!
                                            </span>
                                        </div>
                                    @else
                                        @foreach ($installments2 as $value)
                                            {{-- @if ($key->paymentstatus == 1) --}}
                                            <div class="border rounded p-2 my-1">
                                                <div class="row d-flex justify-content-around">
                                                    <a class="btn"
                                                        href="{{ route('admin.installments.shop.installments', [$value->installments->store_id, 'paid']) }}">
                                                        <h5 class="alert alert-success">
                                                            فروشنده: {{ $value->installments->store->nameofstore }}
                                                        </h5>
                                                    </a>
                                                    <form action="{{ route('admin.installments.filter2') }}"
                                                        method="get">
                                                        @csrf
                                                        <input type="hidden"
                                                            value="{{ $value->installments->user->username }}"
                                                            name="filter1" id="">
                                                        <button class="btn" style="border:none; background-color:none"
                                                            type="submit">
                                                            <h5 class="alert alert-success">خریدار:
                                                                {{ $value->installments->user->username }}
                                                            </h5>
                                                        </button>
                                                    </form>
                                                </div>

                                                <div class="row">
                                                    مبلغ کل فروش: (<span class="monyInputSpan">
                                                        {{ $value->installments->Creditamount }} </span>) ریال
                                                </div>
                                                <div class="row">
                                                    قسط شماره ({{ $value->installmentnumber }})
                                                    پرداخت شده در
                                                    ({{ jdate($value->updated_at)->format('Y/m/d H:i:s') }})
                                                </div>
                                                <div class="row">
                                                    به مبلغ قسط
                                                    (<span class="monyInputSpan">
                                                        {{ $value->installmentprice }} </span>)
                                                    ریال
                                                </div>

                                                <div class="row">

                                                    مقدار پیش پرداخت (<span class="monyInputSpan">
                                                        {{ $value->installments->prepaidamount }} </span>) ریال

                                                </div>
                                            </div>
                                            {{-- @endif --}}
                                        @endforeach
                                    @endif
                                    <div class="m-3">
                                        {{ $installments2->appends(['tab' => 'insta2', 'filter2' => request('filter2'), 'page' => $installments2->currentPage()])->links() }}
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
@include('back.partials.plugins', ['plugins' => ['jquery.validate']])

@push('scripts')
    <script src="{{ asset('back/assets/js/pages/installmentsReport/create.js') }}"></script>
@endpush
