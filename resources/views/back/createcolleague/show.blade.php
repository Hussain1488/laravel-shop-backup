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
                        <div class="m-1">
                            <h2 class="text-xl">
                                {{-- <a href="{{ route('admin.createcolleague.create') }}" style="font-size:16px"
                                    class=""><i style="font-size:30px"
                                        class="feather icon-plus-circle text-success"></i>ایجاد فروشگاه

                                </a> --}}
                                فروشگاه: {{ $store->nameofstore }}
                            </h2>
                        </div>
                    </div>

                    {{-- bank transacion report page --}}



                    <div class="card-content">
                        <div class="container mt-3">
                            <div class="row d-flex justify-start">

                                @can('createcolleague.more')
                                    <div class="mr-1 mt-1">
                                        <a href="{{ route('admin.cooperationsales.mainWallet', [$store->id]) }}"
                                            class="btn btn-info btn-sm font-bold">کیف پول
                                            اصلی</a>
                                    </div>
                                @endcan
                                @can('createcolleague.more')
                                    <div class="mr-1 mt-1">

                                        <a href="{{ route('admin.cooperationsales.payRequestWallet', [$store->id]) }}"
                                            class="btn btn-info btn-sm font-bold">کیف پول درخواست واریز</a>
                                    </div>
                                @endcan
                                @can('createcolleague.more')
                                    <div class="mr-1 mt-1">

                                        <a href="{{ route('admin.cooperationsales.paidSales', [$store->id]) }}"
                                            class="btn btn-info btn-sm font-bold">فروش های تسویه شده</a>
                                    </div>
                                @endcan
                                @can('createcolleague.more')
                                    <div class="mr-1 mt-1">
                                        <a href="{{ route('admin.cooperationsales.creditTrans', [$store->id]) }}"
                                            class="btn btn-info btn-sm font-bold">تراکنش های اعتبار فروشگاه</a>
                                    </div>
                                @endcan
                                @can('createcolleague.more')
                                    <div class="mr-1 mt-1">

                                        <a href="{{ route('admin.shop_more.more', [$store->id]) }}"
                                            class="btn btn-info btn-sm font-bold">اطلاعات و گالری فروشگاه</a>
                                    </div>
                                @endcan



                                {{-- <a href="mr-1 mt-1" class="btn btn-success">مقدار فروش اقساط ماهانه</a> --}}
                            </div>
                        </div>
                        <div class="mb-3 mx-1 p-1 mt-2 border-2 rounded-2xl">
                            <div class="row  ">

                                <div class="col-12 col-lg-3 col-md-6 col-lx-3 col-sm-12 mt-1">

                                    <h5 style="font-size: 16px" for="inventory" class="mr-2">
                                        آدرس فروشگاه :
                                    </h5>
                                </div>
                                <div class="col-12 col-lg-3 col-md-6 col-lx-3 col-sm-12 pl-3">
                                    <span style="font-size: 14px">{{ $store->addressofstore }}</span>

                                </div>


                                <div class="col-12 col-lg-3 col-md-6 col-lx-3 col-sm-12 mt-1">

                                    <h5 style="font-size: 16px" for="inventory" class="mr-2">
                                        مسئول فروشگاه :
                                    </h5>
                                </div>
                                <div class="col-12 col-lg-3 col-md-6 col-lx-3 col-sm-12 pl-3">
                                    <span style="font-size: 14px">{{ $store->user->username }}</span>
                                </div>

                            </div>


                            <div class="row ">

                                <div class="col-12 col-lg-3 col-md-6 col-lx-3 col-sm-12 mt-1">

                                    <h5 style="font-size: 16px" for="inventory" class="mr-2">
                                        مقدار کارمز فروشگاه:
                                    </h5>
                                </div>


                                <div class="col-12 col-lg-3 col-md-6 col-lx-3 col-sm-12 pl-3">
                                    <span style="font-size: 14px">{{ $store->feepercentage }}٪</span>

                                </div>



                                <div class="col-12 col-lg-3 col-md-6 col-lx-3 col-sm-12 mt-1">

                                    <h5 style="font-size: 16px" for="inventory" class="mr-2">
                                        اعتبار فروشگاه:
                                    </h5>
                                </div>
                                <div class="col-12 col-lg-3 col-md-6 col-lx-3 col-sm-12 pl-3">
                                    <span style="font-size: 14px" class="monyInputSpan">{{ $store->storecredit }}</span>
                                    ریال

                                </div>
                            </div>



                            <div class="row ">


                                <div class="col-12 col-lg-3 col-md-6 col-lx-3 col-sm-12 mt-1">


                                    <h5 style="font-size: 16px" for="inventory" class="mr-2">
                                        مقدار فروش فروشگاه:
                                    </h5>
                                </div>
                                <div class="col-12 col-lg-3 col-md-6 col-lx-3 col-sm-12 pl-3">
                                    <span style="font-size: 14px" class="monyInputSpan">{{ $store->salesamount }}</span>
                                    ریال

                                </div>


                                <div class="col-12 col-lg-3 col-md-6 col-lx-3 col-sm-12 mt-1">

                                    <h5 style="font-size: 16px" for="inventory" class="mr-2">
                                        اسناد :
                                    </h5>
                                </div>
                                <div class="col-12 col-lg-3 col-md-6 col-lx-3 col-sm-12 pl-1">

                                    <div class="row d-flex flex-wrap">
                                        @php
                                            use Illuminate\Support\Str;
                                            $counter = 1;
                                        @endphp
                                        @isset($doc)
                                            @if (count($doc) > 1)
                                                <div class="col-12">
                                                    <a href="{{ route('admin.createcolleague.fileDownload', [$store->id]) }}"
                                                        class="btn btn-primary">
                                                        دانلود همه فایل ها<i class="feather icon-download"></i>
                                                    </a>
                                                </div>
                                            @endif


                                            @foreach ($doc as $document)
                                                @if (Str::endsWith($document, ['.jpg', '.jpeg', '.png', '.gif', '.bmp']))
                                                    <div class="mt-1 mr-1">
                                                        <div style="position: relative; display: inline-block; width:100px">
                                                            <a style="position: absolute; top: 5%; right: 5%;"
                                                                class="badge badge-secondary badge-pill"
                                                                href="{{ asset($document) }}" download="{{ $document }}">
                                                                <span class="badge badge-success badge-pill"><i
                                                                        class="feather icon-download"></i></span>
                                                                دانلود
                                                            </a>
                                                            <img src="{{ asset($document) }}" alt="Image">
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="mt-1 mr-1">
                                                        <a class="btn btn-secondary" href="{{ asset($document) }}"
                                                            download="{{ $document }}"><span
                                                                class="badge badge-success badge-pill"><i
                                                                    class="feather icon-download"></i></span>
                                                            دانلود
                                                            فایل
                                                            {{ $counter++ }}</a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endisset
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

@include('back.partials.plugins', [
    'plugins' => ['persian-datepicker', 'jquery.validate'],
])


@push('scripts')
    <script src="{{ asset('back/assets/js/pages/createColleague/create.js') }}"></script>
@endpush
