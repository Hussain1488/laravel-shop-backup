@extends('front::user.layouts.master')

@section('user-content')
    <!-- Start Content -->

    <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12">


        <div class="row">




            <div class="col-12">

                <div class="dt-sl">
                    <div class="table-responsive">
                        <div id="home" class="container tab-pane "><br>



                            <div class="row mt-1 ml-2 mb-2 mr-1">
                                <h5>
                                    لیست تراکنش های بانکی </h5>
                            </div>
                            <div class="row m-2 mb-3">
                                <div class="g-col-6 g-col-sm-12 d-flex align-items-center">
                                    <h6>
                                        اعتبار شما:
                                    </h6>
                                </div>
                                <div class="g-col-6 d-col-sm-12 d-flex align-items-center">
                                    <input id="total_transaction" readonly class="form-control mr-1 moneyInput"
                                        type="text" value="{{ $credit ?? 0 }}">
                                    ریال
                                </div>
                            </div>
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
                                                توضیحات
                                            </th>
                                            <th>
                                                مبلغ تراکنش
                                            </th>
                                            <th class="text-danger">
                                                مجموع تراکنش ها
                                            </th>
                                            <th>
                                                شماره تراکنش
                                            </th>
                                        </tr>
                                    </thead>
                                    @php
                                        $counter = ($trans->currentPage() - 1) * $trans->perPage() + 1;
                                    @endphp
                                    <tbody>
                                        @foreach ($trans as $key)
                                            <tr>
                                                <td>
                                                    {{ $counter++ }}
                                                </td>
                                                <td>
                                                    <span class="transaction_datetime">
                                                        {{ jdate($key->created_at)->format('Y-m-d') }}
                                                        <br>
                                                        {{ jdate($key->created_at)->format('H:i:s') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="{{ $key->typeoftransaction == 1 ? 'text-success' : 'text-danger' }}">
                                                        {{ $key->typeoftransaction == 1 ? 'افزایش اعتبار' : 'کاهش اعتبار' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span>
                                                        {{ $key->description }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="moneyInputSpan">{{ $key->price }}</span> ریال
                                                </td>
                                                <td class="">
                                                    <span
                                                        class="moneyInputSpan {{ $key->typeoftransaction == 1 ? 'text-success' : 'text-danger' }}">{{ $key->finalprice }}</span>
                                                    ریال

                                                </td>
                                                <td>

                                                    {{ $key->documentnumber }}
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
                                            <div class="col mr-2">
                                                <h6 class="">
                                                    تاریخ تراکنش:

                                                </h6>
                                            </div>
                                            <div class="col">
                                                <span class="">
                                                    {{ jdate($key->created_at)->format('Y-m-d') }}
                                                    <br>
                                                    {{ jdate($key->created_at)->format('H:i:s') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="row pt-1">
                                            <div class="col mr-2">
                                                <h6 class="">
                                                    نوع تراکنش: </h6>
                                            </div>
                                            <div class="col">
                                                <span
                                                    class="{{ $key->typeoftransaction == 1 ? 'text-success' : 'text-danger' }}">
                                                    {{ $key->typeoftransaction == 1 ? 'افزایش اعتبار' : 'کاهش اعتبار' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row pt-1">
                                            <div class="col mr-2">
                                                <h6 class="">
                                                    توضیحات
                                                </h6>
                                            </div>
                                            <div class="col">

                                                <span>
                                                    {{ $key->description }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row pt-1">
                                            <div class="col mr-2">
                                                <h6 class="">
                                                    مبلغ تراکنش
                                                </h6>
                                            </div>
                                            <div class="col">

                                                <span class="moneyInputSpan">{{ $key->price }}</span> ریال
                                            </div>
                                        </div>
                                        <div class="row pt-1">
                                            <div class="col mr-2">

                                                <h6 class="">
                                                    مجموع تراکنش </h6>
                                            </div>
                                            <div class="col">

                                                <span class="transaction_datetime">
                                                    <span
                                                        class="moneyInputSpan {{ $key->typeoftransaction == 1 ? 'text-success' : 'text-danger' }}">{{ $key->finalprice }}</span>
                                                    ریال

                                                </span>
                                            </div>
                                        </div>
                                        <div class="row pt-1">
                                            <div class="col mr-2">

                                                <h6 class="">
                                                    شماره تراکنش </h6>
                                            </div>

                                            <div class="col">
                                                <span class="text-dark">
                                                    {{ $key->documentnumber }}</span>
                                            </div>

                                        </div>

                                    </div>
                                @endforeach
                            </div>


                        </div>
                    </div>
                    <div class="mt-3">
                        {{ $trans->links('front::components.paginate') }}
                    </div>
                </div>
            </div>
        </div>




    </div>



    <!-- End Content -->
@endsection

@push('scripts')
    <script src="{{ theme_asset('js/pages/installments/index.js') }}"></script>
    <script src='{{ asset('front/script.js') }}'></script>
@endpush
