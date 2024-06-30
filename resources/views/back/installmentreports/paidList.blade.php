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
                                    <li class="breadcrumb-item active">لیست پرداخت ها
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

                        <h4 class="card-title">لیست درخواست های پرداخت شده:</h4>

                    </div>

                    <div class="card-content">
                        <div class="container mt-3">


                            <!-- Tab panes -->
                            <div class="">
                                <div id="home" class="container tab-pane "><br>



                                    <div class="row mt-1 ml-2 mb-2">
                                        <h3>
                                            لیست تراکنش های بانکی </h3>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="g-col-6 g-col-sm-12">
                                            <h4>
                                                مجموعه تراکنش ها
                                            </h4>
                                        </div>
                                        <div class="g-col-6 d-col-sm-12 d-flex align-items-center">
                                            <input id="total_transaction" readonly class="form-control mr-1" type="text"
                                                value="">
                                            ریال
                                        </div>
                                    </div>
                                    @if (!$paidList->count() > 0)
                                        <div class="m-2">
                                            <div class="alert alert-warning">
                                                چیزی برای نمایش نیست!
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
                                                            تاریخ پرداخت
                                                        </th>

                                                        <th>
                                                            شماره ره گیری
                                                        </th>
                                                        <th class="">
                                                            اسناد:
                                                        </th>
                                                        <th class="">
                                                            جزئیات:
                                                        </th>

                                                    </tr>
                                                </thead>
                                                @php
                                                    $counter =
                                                        ($paidList->currentPage() - 1) * $paidList->perPage() + 1;
                                                @endphp
                                                <tbody>
                                                    @foreach ($paidList as $key)
                                                        <tr>
                                                            <td>
                                                                {{ $counter++ }}
                                                            </td>
                                                            <td>
                                                                {{ $key->payments->store->nameofstore }}
                                                            </td>
                                                            <td>
                                                                <span class="transaction_datetime">
                                                                    {{ jdate($key->created_at)->format('Y/m/d') }}
                                                                    <br>
                                                                    {{ jdate($key->created_at)->format('H:i:s') }}
                                                                </span>
                                                            </td>

                                                            <td>
                                                                <span class="">{{ $key->Issuetracking }}</span>
                                                            </td>
                                                            <td class="">
                                                                <a href="{{ asset($key->documentpayment) }}"
                                                                    download="سند پرداخت فروشگاه {{ $key->payments->store->nameofstore }}">
                                                                    <span class="text-success">دانلود سند</span>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <button class="payDetailsButton btn btn-info btn-sm"
                                                                    data-href="{{ route('admin.installmentreports.payReqDetails', [$key->payments->id]) }}"><i
                                                                        class="feather icon-info"></i> بیشتر</button>
                                                            </td>


                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mobile-size " data-screen="mobile">
                                            @foreach ($paidList as $key)
                                                <div class=" border rounded mb-1">
                                                    <div class="row pt-1">
                                                        <div class="col ml-1">
                                                            <h5 class="text-light">
                                                                نام فروشگاه:

                                                            </h5>
                                                        </div>
                                                        <div class="col"><span class="text-dark">
                                                                {{ $key->payments->store->nameofstore }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="row pt-1">
                                                        <div class="col ml-1">
                                                            <h5 class="text-light">
                                                                تاریخ پرداخت </h5>
                                                        </div>
                                                        <div class="col">
                                                            <span class="transaction_datetime">
                                                                {{ jdate($key->created_at)->format('Y/m/d') }}
                                                                <br>
                                                                {{ jdate($key->created_at)->format('H:i:s') }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="row pt-1">
                                                        <div class="col ml-1">

                                                            <h5 class="text-light">
                                                                شماره رهگیری:
                                                            </h5>
                                                        </div>
                                                        <div class="col">
                                                            <span class="">{{ $key->Issuetracking }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row pt-1">
                                                        <div class="col ml-1">

                                                            <h5 class="text-light">
                                                                اسناد:
                                                            </h5>
                                                        </div>

                                                        <div class="col">
                                                            <a href="{{ asset($key->documentpayment) }}"
                                                                download="سند پرداخت فروشگاه {{ $key->payments->store->nameofstore }}">
                                                                <span class="text-success">دانلود سند</span>
                                                            </a>
                                                        </div>

                                                    </div>
                                                    <div class="row p-1">
                                                        <div class="col ml-1">

                                                            <h5 class="text-light">
                                                                جزئیات:
                                                            </h5>
                                                        </div>

                                                        <div class="col">
                                                            <button class="payDetailsButton btn btn-info btn-sm"
                                                                data-href="{{ route('admin.installmentreports.payReqDetails', [$key->payments->id]) }}"><i
                                                                    class="feather icon-info"></i> بیشتر</button>
                                                            </a>
                                                        </div>

                                                    </div>

                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="m-3">
                                            {{ $paidList->links() }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

            </div>

        </div>

        </section>
    </div>
    @include('back.installmentreports.payDetailsModal')
    </div>
    </div>
@endsection

@include('back.partials.plugins', [
    'plugins' => ['persian-datepicker', 'jquery.validate'],
])


@push('scripts')
    {{-- <script src="{{ asset('back/assets/js/pages/banktransaction/script.js') }}"></script> --}}
    <script src="{{ asset('back/assets/js/pages/installmentsReport/create.js') }}"></script>
@endpush
