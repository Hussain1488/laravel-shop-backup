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
                                    <li class="breadcrumb-item active">{{ $flag ?? 'تراکنش ها' }}
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

                        <h4 class="card-title">لیست تراکنش های فروشگاه: {{ $store->nameofstore }}</h4>

                    </div>

                    <div class="card-content">
                        <div class="container mt-3">


                            <!-- Tab panes -->
                            <div class="">
                                <div id="home" class="container tab-pane "><br>
                                    @php
                                        $counter = ($trans->currentPage() - 1) * $trans->perPage() + 1;
                                    @endphp

                                    <div class="row mt-1 ml-2 mb-2">

                                    </div>
                                    <div class="row mb-2">
                                        <div class="g-col-6 g-col-sm-12">
                                            <h4>
                                                مجموعه تراکنش ها
                                            </h4>
                                        </div>
                                        <div class="g-col-6 d-col-sm-12 d-flex align-items-center">
                                            <input id="total_transaction" readonly class="form-control mr-1 moneyInput"
                                                type="text" value="{{ $total }}">
                                            ریال
                                        </div>
                                    </div>
                                    @if (!$trans->count() > 0)
                                        <div class="alert alert-warning m-2 p-2">
                                            <h5 class="text-danger">
                                                تراکنشی برای نمایش وجود ندارد!
                                            </h5>
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
                                                            تاریخ تراکنش
                                                        </th>
                                                        <th>
                                                            نوع تراکنش
                                                        </th>
                                                        <th>
                                                            مبلغ تراکنش
                                                        </th>
                                                        <th class="text-danger">
                                                            مجموع
                                                        </th>

                                                        <th>
                                                            شماره سند
                                                        </th>
                                                        <th>
                                                            جزئیات
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($trans as $key)
                                                        <tr>
                                                            <td>
                                                                {{ $counter++ }}
                                                            </td>
                                                            <td>

                                                                <span class="transaction_datetime">
                                                                    {{ jdate($key->created_at)->format('Y/m/d') }}
                                                                    <br>
                                                                    {{ jdate($key->created_at)->format('H:i:s') }}

                                                                </span>
                                                            </td>
                                                            <td>
                                                                {{ $key->description }}
                                                            </td>
                                                            <td>
                                                                <span class="monyInputSpan">{{ $key->price }}</span>
                                                            </td>
                                                            <td class="text-danger">
                                                                <span class="monyInputSpan">{{ $key->finalprice }}</span>

                                                            </td>

                                                            <td>
                                                                {{ $key->documentnumber }}
                                                            </td>
                                                            <td>
                                                                <button
                                                                    data-action="{{ route('admin.cooperationsales.transaction.details', [$key->id]) }}"
                                                                    class="btn transaction_details btn btn-info btn-sm"
                                                                    data-id="{{ $key->id }}" value=""><i
                                                                        class=" feather icon-info"> بیشتر</i>
                                                                </button>
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mobile-size " data-screen="mobile">
                                            @foreach ($trans as $key)
                                                <div class=" border rounded mb-1">
                                                    <div class="row pt-1">
                                                        <div class="col ml-1">
                                                            <h5 class="text-light">
                                                                تاریخ تراکنش:

                                                            </h5>
                                                        </div>
                                                        <div class="col"><span class="text-dark">
                                                                {{ jdate($key->created_at)->format('Y/m/d') }}
                                                                <br>
                                                                {{ jdate($key->created_at)->format('H:i:s') }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="row pt-1">
                                                        <div class="col ml-1">
                                                            <h5 class="text-light">توضیحات:
                                                            </h5>
                                                        </div>
                                                        <div class="col">
                                                            <span class="text-dark">
                                                                {{ $key->description }}

                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row pt-1">
                                                        <div class="col ml-1">
                                                            <h5 class="text-light">مبلغ تراکنش:

                                                            </h5>
                                                        </div>
                                                        <div class="col">

                                                            <span
                                                                class="text-dark monyInputSpan">{{ $key->price }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row pt-1">
                                                        <div class="col ml-1">

                                                            <h5 class="text-light">مجموع:
                                                            </h5>
                                                        </div>
                                                        <div class="col">

                                                            <span
                                                                class="text-dark monyInputSpan">{{ $key->finalprice }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row pt-1">
                                                        <div class="col ml-1">

                                                            <h5 class="text-light"> شماره سند:
                                                            </h5>
                                                        </div>

                                                        <div class="col">
                                                            <span class="text-dark">
                                                                {{ $key->documentnumber }}
                                                            </span>
                                                        </div>

                                                    </div>
                                                    <div class="row p-1">
                                                        <div class="col ml-1">

                                                            <h5 class="text-light">جزئیات:
                                                            </h5>
                                                        </div>

                                                        <div class="col">
                                                            <button
                                                                data-action="{{ route('admin.cooperationsales.transaction.details', [$key->id]) }}"
                                                                class="btn transaction_details btn btn-info btn-sm"
                                                                data-id="{{ $key->id }}" value=""><i
                                                                    class=" feather icon-info"> بیشتر</i>
                                                            </button>
                                                        </div>

                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="m-3">
                        {{ $trans->links() }}
                    </div>
            </div>

        </div>

        </section>
    </div>
    <div class="modal fade transaction_details_modal" id="transaction_details">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">جزئیات تراکنش:<span class="text-success" id="deposit_amount_show"></span>
                    </h4>
                </div>
                <hr />

                <!-- Modal body -->
                <div class="modal-body p-2">


                </div>

                <!-- Modal footer -->

                <div class="modal-footer">

                </div>
                <button type="button" class="btn btn-danger m-1 d-flex justify-content-center" data-dismiss="modal"><i
                        class="feather icon-x-circle" style=""></i> بستن</button>
            </div>
        </div>
    </div>
@endsection

@include('back.partials.plugins', [
    'plugins' => ['persian-datepicker', 'jquery.validate'],
])


@push('scripts')
    <script src="{{ asset('back/assets/js/pages/banktransaction/script.js') }}?v=51"></script>
    <script src="{{ asset('back/assets/js/pages/installmentsReport/create.js') }}?v=51"></script>
@endpush
