@extends('back.layouts.master')

@section('content')
    <input type="hidden" id="new_date" value="{{ \Carbon\Carbon::parse($today)->format('Y-m-d') }}">
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
                                    <li class="breadcrumb-item active">لیست فروش
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
                        @isset($store)
                            <h4 class="card-title">{{ $store->nameofstore }}</h4>
                        @else
                            <h4 class="text-warning">
                                شما فروشگاهی برای نمایش ندارید!
                            </h4>
                        @endisset
                    </div>
                    <div class="row mt-3">

                        <div class="col-md-6 col-12">
                            @isset($store)
                                <div class="form-group d-flex align-items-center">
                                    <label for="first_name" class="mr-2">
                                        مقدار اعتبار فروش اقساطی
                                    </label>
                                    <div class="d-flex align-items-center">
                                        <input readonly type="text" placeholder="100,000" class="form-control moneyInput"
                                            id="first_name" name="first_name" style="margin-left: 4px"
                                            value="{{ $store->storecredit != null ? $store->storecredit : 0 }}">
                                        ریال
                                    </div>

                                </div>
                            @endisset
                        </div>
                        <div class="col-md-6 col-12">

                        </div>
                    </div>
                    <div class="card-content">
                        <div class="container m1-3">

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link {{ request('tab') != 'insta1' ? 'active' : '' }}"
                                        style="font-size: 10px" data-toggle="tab" href="#home">در
                                        انتظار پرداخت</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('tab') == 'insta1' ? 'active' : '' }}"
                                        style="font-size: 10px" data-toggle="tab" href="#menu1">
                                        فروش های انجام شده
                                    </a>
                                </li>

                            </ul>

                            <!-- Tab not paid and not validated installments -->
                            <div class="tab-content">
                                <div id="home"
                                    class="container tab-pane {{ request('tab') != 'insta1' ? 'active' : 'fade' }}"><br>

                                    @if (!$installmentsm->count() > 0)
                                        <section id="main-card" class="card">
                                            <div class="card-header p-1 alert alert-warning ">
                                                <h3 class="text-danger">فروشی برای نمایش به شما وجود ندارد</h3>
                                            </div>
                                        </section>
                                    @else
                                        @foreach ($installmentsm as $key)
                                            <div class="border rounded p-2 my-1">
                                                <div class="row">
                                                    <h5>آقای:
                                                        {{ $key->user->first_name . ' ' . $key->user->last_name . '(' . $key->user->username . ')' }}
                                                    </h5>
                                                </div>
                                                <div class="row">
                                                    مبلغ کل فروش:(<span
                                                        class="monyInputSpan">{{ $key->Creditamount }}</span>)ریال
                                                </div>
                                                <div class="row">
                                                    ({{ $key->numberofinstallments }})
                                                    عدد قسط به مبلغ هر قسط
                                                    (<span class="monyInputSpan">{{ $key->amounteachinstallment }}</span>)
                                                    ریال
                                                </div>

                                                <div class="row">

                                                    مقدار پیش پرداخت (<span
                                                        class="monyInputSpan">{{ $key->prepaidamount }}</span>) ریال


                                                </div>
                                                <div class="d-flex justify-content-end">

                                                    <a href="{{ route('admin.installments.usrestatus.refuse', [$key->id]) }}"
                                                        class="btn btn-warning ">حذف</a>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                    <div class="m-2">
                                        {{ $installmentsm->appends(['tab' => 'insta', 'page' => $installmentsm->currentPage()])->links() }}
                                    </div>
                                </div>

                                {{-- tab prepayment paid and validated installments --}}
                                <div id="menu1"
                                    class="container tab-pane {{ request('tab') == 'insta1' ? 'active' : 'fade' }}"><br>


                                    <div class="row">


                                        <div class="col-md-6 col-12">

                                        </div>
                                    </div>
                                    @if (!$installmentsm1->count() > 0)
                                        <section id="main-card" class="card">
                                            <div class="card-header p-1 alert alert-warning ">
                                                <h3 class="text-danger">فروشی برای نمایش به شما وجود ندارد</h3>
                                            </div>
                                        </section>
                                    @else
                                        @foreach ($installmentsm1 as $index)
                                            <div class="border rounded p-2 my-1">
                                                <div class="row">
                                                    <h5>آقای:
                                                        {{ $index->user->first_name . ' ' . $index->user->last_name . '(' . $index->user->username . ')' }}
                                                    </h5>
                                                </div>


                                                <div class="row">
                                                    مبلغ کل فروش: (<span
                                                        class="monyInputSpan">{{ $index->Creditamount }}</span>)
                                                    ریال
                                                </div>
                                                <div class="row">
                                                    @if ($index->typeofpayment == 'cash')
                                                        خرید: نقدی
                                                    @elseif ($index->typeofpayment == 'weekly_installment' || $index->typeofpayment == 'w_none_profit')
                                                        تعداد ({{ $index->numberofinstallments }}) قسط به سر رسید
                                                        هفته ای
                                                        به مبلغ قسط
                                                        (<span
                                                            class="monyInputSpan">{{ $index->amounteachinstallment }}</span>)
                                                        ریال
                                                    @else
                                                        تعداد ({{ $index->numberofinstallments }}) قسط به سر رسید تاریخ
                                                        ({{ \Carbon\Carbon::parse($index->datepayment)->format('d') }})
                                                        هر ماه
                                                        به مبلغ قسط
                                                        (<span
                                                            class="monyInputSpan">{{ $index->amounteachinstallment }}</span>)
                                                        ریال
                                                    @endif

                                                </div>

                                                <div class="row ">
                                                    @if ($index->typeofpayment == 'cash')
                                                        مبلغ پرداخت شده: (<span
                                                            class="monyInputSpan">{{ $index->prepaidamount }}</span>) ریال
                                                    @else
                                                        مقدار پیش پرداخت: (<span
                                                            class="monyInputSpan">{{ $index->prepaidamount }}</span>) ریال
                                                    @endif


                                                </div>
                                                <div class="row d-flex justify-content-between p-1">
                                                    <div class="col d-flex justify-content-end">
                                                        <button
                                                            data_date='{{ \Carbon\Carbon::parse($index->datepayment)->format('Y-m-d') }}'
                                                            data_day='{{ $index->store->settlementtime }}'
                                                            class="btn btn-primary settlementtime_button"
                                                            id="settlementtime_button" data-store-id ="{{ $index->id }}"
                                                            data-route="{{ route('admin.installments.payrequest', ['store_id' => $index->store->id, 'installments_id' => $index->id]) }}">
                                                            درخواست تسویه حساب
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="m-2">
                                        {{ $installmentsm1->appends(['tab' => 'insta1', 'page' => $installmentsm1->currentPage()])->links() }}
                                    </div>
                                </div>

                            </div>
                        </div>


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
                                <p>
                                    برای فعلا شما اجازه درخواست تسویه را ندارید.
                                </p>
                                <p>برای این فروش <span id="user_day_time" class="text-success"></span> روز دیگر از وقت
                                    درخواست تسویه مانده است .</p>
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
        var url = '{{ route('admin.user.searchUser') }}';
    </script>
    <script src="{{ asset('back/assets/js/pages/cooperationSales/create.js') }}?v=51"></script>
@endpush
