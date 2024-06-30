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
                        <h4 class="card-title">لیست درخواست های تسویه حساب</h4>
                    </div>
                    <div class="card-content">
                        <div class="container mt-3">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link  {{ request('tab') != 'transaction1' ? 'active' : '' }}"
                                        style="font-size: 10px" data-toggle="tab" href="#home">
                                        درخواست های پرداخت نشده </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('tab') == 'transaction1' ? 'active' : '' }}"
                                        style="font-size: 10px" data-toggle="tab" href="#menu1">درخواست
                                        های
                                        پرداخت
                                        شده</a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">

                                {{-- not validated and not paid prepayment of installments list --}}
                                <div id="home"
                                    class="container tab-pane {{ request('tab') != 'transaction1' ? 'active' : 'fade' }}">
                                    <br>
                                    <div class="row mb-2">
                                        <div class="g-col-6 g-col-sm-12 d-flex align-items-center">
                                            <h4>
                                                مجموعه درخواست ها:
                                            </h4>
                                        </div>
                                        <div class="g-col-6 d-col-sm-12 d-flex align-items-center">
                                            <input id="total_transaction" readonly class="form-control mr-1 moneyInput"
                                                type="text" value="{{ $total[0] }}">
                                            ریال
                                        </div>
                                    </div>
                                    <div class="row mt-1 ml-2 mb-2">
                                        <h3>
                                            درخواست های تسویه نشده:
                                        </h3>
                                    </div>
                                    @if (!$transaction->count() > 0)
                                        <div class="m-2">
                                            <div class="alert alert-warning">
                                                درخواستی موجود نیست!
                                            </div>
                                        </div>
                                    @else
                                        <div class="pc-size" data-screen="pc">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            #
                                                        </th>
                                                        <th>
                                                            نام فروشگاه
                                                        </th>
                                                        <th>
                                                            مبلغ درخواست(ریال)
                                                        </th>
                                                        <th class="">
                                                            مجموع درخواست ها (ریال)
                                                        </th>
                                                        <th>
                                                            تاریخ درخواست
                                                        </th>
                                                        <th>
                                                            شماره درخواست
                                                        </th>
                                                        @can('installmentreports.RequestPaymentStore')
                                                            <th>
                                                                عملیات
                                                            </th>
                                                        @endcan
                                                    </tr>
                                                </thead>
                                                @php
                                                    $counter =
                                                        ($transaction->currentPage() - 1) * $transaction->perPage() + 1;
                                                @endphp
                                                <tbody>
                                                    @foreach ($transaction as $key)
                                                        @if ($key->status == 0)
                                                            <tr>
                                                                <td>
                                                                    {{ $counter++ }}
                                                                </td>
                                                                <td>

                                                                    <a
                                                                        href="{{ route('admin.createcolleague.show', [$key->store->id]) }}">
                                                                        {{ $key->store->nameofstore }}
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="monyInputSpan">{{ $key->depositamount }}</span>
                                                                    ریال

                                                                </td>
                                                                <td class="text-success">
                                                                    +<span
                                                                        class="monyInputSpan">{{ $key->final_price }}</span>
                                                                    ریال

                                                                </td>
                                                                <td>
                                                                    <span class="transaction_datetime">
                                                                        {{ jdate($key->depositdate)->format('Y/m/d') }}
                                                                        <br>
                                                                        {{ jdate($key->created_at)->format('H:i:s') }}
                                                                    </span>

                                                                </td>
                                                                <td>
                                                                    {{ $key->list_id }}
                                                                </td>
                                                                @can('installmentreports.RequestPaymentStore')
                                                                    <td>
                                                                        <button data-id="{{ $key->id }}"
                                                                            data-amount="{{ $key->depositamount }}"
                                                                            class="btn btn-success btn-sm pay_button">پرداخت</button>
                                                                    </td>
                                                                @endcan

                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mobile-size " data-screen="mobile">
                                            @foreach ($transaction as $key)
                                                <div class=" border rounded mb-1">
                                                    <div class="row pt-1">
                                                        <div class="col ml-1">
                                                            <h5 class="text-light">
                                                                نام فروشگاه:

                                                            </h5>
                                                        </div>
                                                        <div class="col"><span class="text-dark">
                                                                <a
                                                                    href="{{ route('admin.createcolleague.show', [$key->store->id]) }}">
                                                                    {{ $key->store->nameofstore }}
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row pt-1">
                                                        <div class="col ml-1">
                                                            <h5 class="text-light">
                                                                مبلغ درخواست:
                                                            </h5>
                                                        </div>
                                                        <div class="col">
                                                            <span class="text-dark">
                                                                <span
                                                                    class="monyInputSpan">{{ $key->depositamount }}</span>
                                                                ریال
                                                            </span>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="row pt-1">
                                                        <div class="col ml-1">
                                                            <h5 class="text-light">
                                                                مجموع درخواست ها (ریال):

                                                            </h5>
                                                        </div>
                                                        <div class="col">

                                                            +<span class="monyInputSpan">{{ $key->final_price }}</span>
                                                        </div>
                                                    </div> --}}
                                                    <div class="row pt-1">
                                                        <div class="col ml-1">
                                                            <h5 class="text-light">
                                                                تاریخ درخواست:
                                                            </h5>
                                                        </div>
                                                        <div class="col">
                                                            <span class="transaction_datetime">
                                                                {{ jdate($key->created_at)->format('Y/m/d') }}

                                                                {{ jdate($key->created_at)->format('H:i:s') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row pt-1">
                                                        <div class="col ml-1">
                                                            <h5 class="text-light">
                                                                شماره درخواست
                                                            </h5>
                                                        </div>
                                                        <div class="col">
                                                            <span class="text-dark">
                                                                {{ $key->list_id }}
                                                        </div>
                                                    </div>
                                                    @can('installmentreports.RequestPaymentStore')
                                                        <div class="row p-1">
                                                            <div class="col ml-1">

                                                                <h5 class="text-light">
                                                                    عملیات
                                                                </h5>
                                                            </div>
                                                            <div class="col">
                                                                <span class="text-dark">
                                                                    <button data-id="{{ $key->id }}"
                                                                        data-amount="{{ $key->depositamount }}"
                                                                        class="btn btn-success btn-sm pay_button">پرداخت</button>
                                                            </div>
                                                        </div>
                                                    @endcan
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="m-3">
                                            {{ $transaction->appends(['tab' => 'transaction', 'page' => $transaction->currentPage()])->links() }}
                                        </div>
                                    @endif
                                </div>
                                {{--  not payd installments which are paid prepayment of isntallments list --}}
                                <div id="menu1"
                                    class="container tab-pane {{ request('tab') == 'transaction1' ? 'active' : 'fade' }}">
                                    <br>
                                    <div class="row mb-2">
                                        <div class="g-col-6 g-col-sm-12 d-flex align-items-center">
                                            <h4>
                                                مجموعه درخواست ها:
                                            </h4>
                                        </div>
                                        <div class="g-col-6 d-col-sm-12 d-flex align-items-center">
                                            <input id="total_transaction" readonly class="form-control mr-1 moneyInput"
                                                type="text" value="{{ $total[1] }}">
                                            ریال
                                        </div>
                                    </div>
                                    <div class="row mt-1 ml-2 mb-2">
                                        <h3>
                                            درخواست های تسویه شده:
                                        </h3>
                                    </div>
                                    @if (!$transaction1->count() > 0)
                                        <div class="m-2">
                                            <div class="alert alert-warning">
                                                درخواستی موجود نیست!
                                            </div>
                                        </div>
                                    @else
                                        <div class="pc-size" data-screen="pc">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            #
                                                        </th>
                                                        <th>
                                                            نام فروشگاه
                                                        </th>
                                                        <th>
                                                            مبلغ درخواست(ریال)
                                                        </th>
                                                        <th class="">
                                                            مجموع درخواست ها (ریال)
                                                        </th>
                                                        <th>
                                                            تاریخ درخواست
                                                        </th>
                                                        <th>
                                                            شماره درخواست
                                                        </th>
                                                        <th>
                                                            شماره پیگیری
                                                        </th>
                                                        <th>
                                                            جزئیات
                                                        </th>

                                                    </tr>
                                                </thead>
                                                @php
                                                    $counter1 =
                                                        ($transaction1->currentPage() - 1) * $transaction1->perPage() +
                                                        1;
                                                @endphp
                                                <tbody>
                                                    @foreach ($transaction1 as $key)
                                                        <tr>
                                                            <td>
                                                                {{ $counter1++ }}
                                                            </td>
                                                            <td>
                                                                <a
                                                                    href="{{ route('admin.createcolleague.show', [$key->store->id]) }}">
                                                                    {{ $key->store->nameofstore }}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="monyInputSpan">{{ $key->depositamount }}</span>
                                                                ریال

                                                            </td>
                                                            <td class="text-success">
                                                                +<span
                                                                    class="monyInputSpan">{{ $key->final_price }}</span>
                                                                ریال

                                                            </td>
                                                            <td>
                                                                {{ jdate($key->depositdate)->format('Y/m/d') }}
                                                                <br>
                                                                {{ jdate($key->created_at)->format('H:i:s') }}
                                                            </td>
                                                            <td>
                                                                {{ $key->list_id }}
                                                            </td>
                                                            <td>
                                                                {{ $key->details->Issuetracking }}
                                                            </td>
                                                            <td>
                                                                <button class="payDetailsButton btn btn-info btn-sm"
                                                                    data-href="{{ route('admin.installmentreports.payReqDetails', [$key->id]) }}"><i
                                                                        class="feather icon-info"></i> بیشتر</button>
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mobile-size " data-screen="mobile">
                                            @foreach ($transaction1 as $key)
                                                @if ($key->status == 1)
                                                    <div class=" border rounded mb-1">
                                                        <div class="row pt-1">
                                                            <div class="col ml-1">
                                                                <h5 class="text-light">
                                                                    نام فروشگاه:

                                                                </h5>
                                                            </div>
                                                            <div class="col"><span class="text-dark">
                                                                    <a
                                                                        href="{{ route('admin.createcolleague.show', [$key->store->id]) }}">
                                                                        {{ $key->store->nameofstore }}
                                                                    </a>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row pt-1">
                                                            <div class="col ml-1">
                                                                <h5 class="text-light">
                                                                    مبلغ درخواست:
                                                                </h5>
                                                            </div>
                                                            <div class="col">
                                                                <span class="text-dark">
                                                                    <span
                                                                        class="monyInputSpan">{{ $key->depositamount }}</span>
                                                                    ریال
                                                                </span>
                                                            </div>
                                                        </div>
                                                        {{-- <div class="row pt-1">
                                                            <div class="col ml-1">
                                                                <h5 class="text-light">
                                                                    مجموع درخواست ها (ریال):

                                                                </h5>
                                                            </div>
                                                            <div class="col">
                                                                +<span
                                                                    class="monyInputSpan">{{ $key->final_price }}</span>
                                                            </div>
                                                        </div> --}}
                                                        <div class="row pt-1">
                                                            <div class="col ml-1">
                                                                <h5 class="text-light">
                                                                    تاریخ درخواست:
                                                                </h5>
                                                            </div>
                                                            <div class="col">
                                                                <span class="transaction_datetime">
                                                                    {{ jdate($key->depositdate)->format('Y/m/d') }}

                                                                    {{ jdate($key->created_at)->format('H:i:s') }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row pt-1">
                                                            <div class="col ml-1">
                                                                <h5 class="text-light">
                                                                    شماره درخواست
                                                                </h5>
                                                            </div>
                                                            <div class="col">
                                                                <span class="text-dark">
                                                                    {{ $key->list_id }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row pt-1">
                                                            <div class="col ml-1">
                                                                <h5 class="text-light">
                                                                    شماره پیگیری
                                                                </h5>
                                                            </div>
                                                            <div class="col">
                                                                <span class="text-dark">
                                                                    {{ $key->details->Issuetracking }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row p-1">
                                                            <div class="col ml-1">
                                                                <h5 class="text-light">
                                                                    جزئیات
                                                                </h5>
                                                            </div>
                                                            <div class="col ">
                                                                <button class="payDetailsButton btn btn-info btn-sm"
                                                                    data-href="{{ route('admin.installmentreports.payReqDetails', [$key->id]) }}"><i
                                                                        class="feather icon-info"></i> بیشتر</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="m-3">
                                            {{ $transaction1->appends(['tab' => 'transaction1', 'page' => $transaction1->currentPage()])->links() }}
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>

                    </div>

                </section>
                <!-- Vertically centered modal -->
                <div class="modal fade" id="myModal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">درخواست تسویه حساب به مبلغ:<span class="text-success"
                                        id="deposit_amount_show"></span>ریال
                                </h4>
                            </div>
                            <!-- Modal body -->
                            <div class="modal-body">
                                <form id="payment_form" action="{{ '#' }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input id="pay_list_id" type="hidden" value="" name="pay_list_id">
                                    <div class="d-flex justify-content-around my-1">
                                        <div class="col">
                                            <label for="">شماره پیگیری:</label>
                                        </div>
                                        <div class="col">
                                            <input required type="number" class="form-control" name="Issuetracking">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-around my-1">
                                        <div class="col d-flex align-items-center">
                                            <label for="">:نام بانک</label>
                                        </div>
                                        <div class="form-group col">
                                            <label></label>
                                            <select id="bank_list" type="text" class="form-control account_selection"
                                                name="nameofbank">
                                                @isset($bank)
                                                    <option value="">انتخاب بانک</option>
                                                    @foreach ($bank as $key)
                                                        <option attr-name="{{ $key->bankname }}"
                                                            value="{{ $key->id }}">{{ $key->accountnumber }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    <option value="">گزینه ای برای انتخاب وجود ندارد</option>
                                                @endisset
                                            </select>
                                            <div class="m-1">
                                                <span class="account-title" id=""></span>
                                                <span class="text-success account-name" id=""></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-around my-1">
                                        <div class="col">
                                            <label for="">ارسال سند:</label>
                                        </div>
                                        <div class="col">
                                            <input multiple required id="issue_doc" type="file"
                                                class="form-control imageInput" name="documentpayment[]">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col"></div>
                                        <div class="col imgContainer"></div>
                                    </div>
                                    <div class="row d-flex justify-center my-1">
                                        <div id="validation-messages"></div>

                                        <span class="text-danger" style="display: none">لطفا فرم را دقیق پر کرده بعد کلید
                                            تأیید را بزنید.</span>
                                    </div>
                                    <div class="modal-footer d-flex justify-content-between">
                                        <input type="button" id="submit_form_pay" class="btn btn-success"
                                            value="تأیید">
                                        <input type="button" class="btn btn-danger" data-dismiss="modal"
                                            value="انصراف">
                                    </div>
                                </form>
                            </div>
                            <!-- Modal footer -->
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    @include('back.installmentreports.payDetailsModal')
@endsection
@include('back.partials.plugins', ['plugins' => ['jquery.validate']])

@push('scripts')
    <script>
        var pay_url = "{{ route('admin.installmentreports.RequestPaymentStore') }}";
    </script>
    <script src="{{ asset('back/assets/js/pages/installmentsReport/create.js') }}"></script>
@endpush
