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
                                    <li class="breadcrumb-item">ایجاد همکار
                                    </li>
                                    <li class="breadcrumb-item active">لیست فروشگاه ها
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
                            <h2>
                                @can('createcolleague.create')
                                    <a href="{{ route('admin.createcolleague.create') }}" style="font-size:16px"
                                        class=""><i style="font-size:30px"
                                            class="feather icon-plus-circle text-success"></i>ایجاد فروشگاه

                                    </a>
                                @endcan
                            </h2>
                        </div>
                    </div>

                    {{-- bank transacion report page --}}



                    <div class="card-content">
                        <div class="container mt-3">


                            <!-- Tab panes -->
                            <div class="mb-3">
                                <div id="home" class="container tab-pane  mb-5"><br>

                                    <div class="">

                                        <form action="{{ route('admin.createcolleague.shopListFilter') }}" method="post">
                                            @csrf
                                            <div class="row ">

                                                <div class="col-md-6 col-12 d-flex justify-content-around">

                                                    <h4>
                                                        جستوجو بر اساس نام فروشگاه
                                                    </h4>

                                                    <div class="d-flex">
                                                        <input type="text" name="filter"
                                                            class="form-control w-auto mr-1" placeholder="جستجو">
                                                        <input type="submit" class="btn btn-info" value="جستجو">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="row mt-1 ml-2 mb-2">
                                        <h3>
                                            لیست تمامی فروشگاه ها
                                        </h3>
                                    </div>
                                    <div class="row mb-2">

                                    </div>
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
                                                        مالک فروشگاه
                                                    </th>
                                                    <th>
                                                        اعتبار فروشگاه
                                                    </th>
                                                    <th>
                                                        مقدار فروشات
                                                    </th>
                                                    <th>
                                                        تاریخ ختم قرارداد
                                                    </th>
                                                    @can('createcolleague.shopedit')
                                                        <th>
                                                            عملیات
                                                        </th>
                                                    @endcan
                                                </tr>
                                            </thead>
                                            @php
                                                $counter = ($store->currentPage() - 1) * $store->perPage() + 1;
                                            @endphp
                                            <tbody>
                                                @foreach ($store as $key)
                                                    <tr>
                                                        <td>
                                                            {{ $counter++ }}
                                                        </td>


                                                        <td>
                                                            @can('createcolleague.show')
                                                                <a href="{{ route('admin.createcolleague.show', [$key->id]) }}">
                                                                    {{ $key->nameofstore ?? '' }}
                                                                </a>
                                                            @else
                                                                {{ $key->nameofstore ?? '' }}
                                                            @endcan
                                                        </td>
                                                        <td>
                                                            {{ $key->user->first_name . ' ' . $key->user->last_name ?? '' }}

                                                        </td>
                                                        <td>
                                                            <span class="monyInputSpan">{{ $key->storecredit ?? '' }}</span>

                                                        </td>
                                                        <td>
                                                            <span
                                                                class="monyInputSpan">{{ $key->salesamount != null ? $key->salesamount : 0 }}</span>

                                                        </td>
                                                        <td>
                                                            {{ jdate(\Carbon\Carbon::parse($key->enddate))->format('Y/m/d') }}

                                                        </td>
                                                        <td>
                                                            @can('createcolleague.shopedit')
                                                                <a href="{{ route('admin.createcolleague.shopedit', [$key->id]) }}"
                                                                    class="text-black btn btn-warning btn-sm">
                                                                    <i class="feather icon-edit" alt="اصلاح"
                                                                        title="اصلاح"></i>اصلاح
                                                                </a>
                                                            @endcan
                                                            @can('createcolleague.shopShow')
                                                                <a href="{{ route('admin.installments.shop.installments', [$key->id, 'wait']) }}"
                                                                    class="text-black btn btn-success btn-sm">
                                                                    <i class="feather icon-more" alt="فروش ها"
                                                                        title="فروش ها"></i>فروش ها</a>
                                                            @endcan

                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mobile-size " data-screen="mobile">
                                        @foreach ($store as $key)
                                            <div class=" border rounded mb-1">
                                                @can('createcolleague.show')
                                                    <a href="{{ route('admin.createcolleague.show', [$key->id]) }}">
                                                        <div class="row pt-1">
                                                            <div class="col ml-1">
                                                                <h5 class="text-light">
                                                                    نام فروشگاه:

                                                                </h5>
                                                            </div>
                                                            <div class="col"><span class="text-dark">

                                                                    {{ $key->nameofstore ?? '' }}


                                                                </span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @else
                                                    <div class="row pt-1">
                                                        <div class="col ml-1">
                                                            <h5 class="text-light">
                                                                نام فروشگاه:

                                                            </h5>
                                                        </div>
                                                        <div class="col"><span class="text-dark">

                                                                {{ $key->nameofstore ?? '' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endcan

                                                <div class="row pt-1">
                                                    <div class="col ml-1">
                                                        <h5 class="text-light">مالک فروشگاه:
                                                        </h5>
                                                    </div>
                                                    <div class="col">
                                                        <span class="text-dark">
                                                            {{ $key->user->first_name . ' ' . $key->user->last_name ?? '' }}

                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="row pt-1">
                                                    <div class="col ml-1">
                                                        <h5 class="text-light">اعتبار فروشگاه:

                                                        </h5>
                                                    </div>
                                                    <div class="col">

                                                        <span class="monyInputSpan">{{ $key->storecredit ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row pt-1">
                                                    <div class="col ml-1">

                                                        <h5 class="text-light">مقدار فروشات:
                                                        </h5>
                                                    </div>
                                                    <div class="col">

                                                        <span
                                                            class="monyInputSpan">{{ $key->salesamount != null ? $key->salesamount : 0 }}</span>
                                                    </div>
                                                </div>
                                                <div class="row pt-1">
                                                    <div class="col ml-1">

                                                        <h5 class="text-light">تاریخ ختم قرارداد:
                                                        </h5>
                                                    </div>

                                                    <div class="col">
                                                        <span class="text-dark">
                                                            {{ jdate(\Carbon\Carbon::parse($key->enddate))->format('Y/m/d') }}
                                                    </div>

                                                </div>
                                                <div class="row p-1">
                                                    <div class="col ml-1">

                                                        <h5 class="text-light">عملیات:
                                                        </h5>
                                                    </div>

                                                    <div class="col">
                                                        @can('createcolleague.shopedit')
                                                            <a href="{{ route('admin.createcolleague.shopedit', [$key->id]) }}"
                                                                class="text-black btn btn-warning btn-sm">
                                                                <i class="feather icon-edit" alt="اصلاح"
                                                                    title="اصلاح"></i>اصلاح
                                                            </a>
                                                        @endcan
                                                        <a href="{{ route('admin.installments.shop.installments', [$key->id, 'wait']) }}"
                                                            class="text-black btn btn-success btn-sm">
                                                            <i class="feather icon-more" alt="فروش ها"
                                                                title="فروش ها"></i>فروش ها</a>

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="m-3">
                        {{ $store->appends(['filter' => request('filter')])->links() }}
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
    <script src="{{ asset('back/assets/js/pages/banktransaction/script.js') }}"></script>

    <script src="{{ asset('back/assets/js/pages/installmentsReport/create.js') }}"></script>
@endpush
