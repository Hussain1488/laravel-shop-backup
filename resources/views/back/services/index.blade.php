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
                                    <li class="breadcrumb-item">امکانات فروشگاه
                                    </li>
                                    <li class="breadcrumb-item active">لیست امکانات </li>
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
                                <a href="{{ route('admin.services.create') }}" style="font-size:16px" class=""><i
                                        style="font-size:30px" class="feather icon-plus-circle text-success"></i>امکانات
                                    جدید
                                </a>
                            </h2>
                        </div>
                    </div>
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
                                                        جستجو امکانات
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
                                            لیست امکانات
                                        </h3>
                                    </div>
                                    <div class="row mb-2">
                                    </div>
                                    <div class="pc-size" data-screen="pc">
                                        <table class="table table-hover" id="">
                                            <thead>
                                                <tr>
                                                    <td>
                                                        شماره
                                                    </td>
                                                    <td>
                                                        نام فروشگاه
                                                    </td>
                                                    <td>
                                                        اسم امکانات
                                                    </td>

                                                    <td>
                                                        امتیاز
                                                    </td>
                                                    <td>
                                                        عملیات
                                                    </td>
                                                </tr>
                                            </thead>
                                            @php
                                                $counter = 1;
                                            @endphp
                                            <tbody>
                                                @foreach ($service as $key)
                                                    <tr>
                                                        <td>
                                                            {{ $counter++ }}
                                                        </td>
                                                        <td>
                                                            {{ $key->shop->nameofstore }}
                                                        </td>
                                                        <td>
                                                            {{ $key->name }}
                                                        </td>
                                                        <td>{{ $key->rating }}</td>
                                                        <td>
                                                            <button
                                                                data-action="{{ route('admin.services.show', [$key->id]) }}"
                                                                class="btn btn-info btn-sm details-button waves-effect waves-light"
                                                                style="margin-left:2px">
                                                                بیشتر<i class="feather icon-info"></i></button>
                                                            <button
                                                                action="{{ route('admin.services.delete', [$key->id]) }}"
                                                                class="btn btn-danger btn-sm waves-effect waves-light delete-button">حذف<i
                                                                    class="feather icon-trash"></i></button>
                                                            <a href="{{ route('admin.services.edit', [$key->id]) }}"
                                                                class="btn btn-warning btn-sm waves-effect waves-light">اصلاح<i
                                                                    class="feather icon-edit"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mobile-size " data-screen="mobile">
                                        @foreach ($service as $key)
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

                                                                    {{ $key->shop->nameofstore ?? '' }}


                                                                </span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @else
                                                    <div class="row pt-1">
                                                        <div class="col-4 ml-1">
                                                            <h5 class="text-light">
                                                                نام فروشگاه:

                                                            </h5>
                                                        </div>
                                                        <div class="col-7"><span class="text-dark">

                                                                {{ $key->shop->nameofstore ?? '' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endcan

                                                <div class="row pt-1">
                                                    <div class="col-4 ml-1">
                                                        <h5 class="text-light">اسم امکانات:
                                                        </h5>
                                                    </div>
                                                    <div class="col-7">
                                                        <span class="text-dark">
                                                            {{ $key->name }}

                                                        </span>
                                                    </div>
                                                </div>


                                                <div class="row pt-1">
                                                    <div class="col-4 ml-1">

                                                        <h5 class="text-light">امتیاز:
                                                        </h5>
                                                    </div>
                                                    <div class="col-7">
                                                        <span class="text-dark">
                                                            {{ $key->rating }}
                                                    </div>
                                                </div>


                                                <div class="d-flex justify-content-center m-1">
                                                    <button data-action="{{ route('admin.services.show', [$key->id]) }}"
                                                        class="btn btn-info btn-sm details-button waves-effect waves-light"
                                                        style="">
                                                        بیشتر<i class="feather icon-info"></i></button>
                                                    <button action="{{ route('admin.services.delete', [$key->id]) }}"
                                                        class="btn btn-danger btn-sm waves-effect waves-light delete-button"
                                                        style="margin:1px ">حذف<i class="feather icon-trash"></i></button>
                                                    <a href="{{ route('admin.services.edit', [$key->id]) }}"
                                                        class="btn btn-warning btn-sm waves-effect waves-light">اصلاح<i
                                                            class="feather icon-edit"></i></a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="m-3">
                    </div>
                </section>
            </div>
        </div>
    </div>
    <div class="modal fade text-left" id="service-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel19">آیا مطمئن هستید؟</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    با حذف نظر دیگر قادر به بازیابی آن نخواهید بود و تمامی پاسخ های آن نیز حذف خواهند شد.
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-success waves-effect waves-light"
                        data-dismiss="modal">خیر</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light service-delete-button">بله
                        حذف شود</button>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="service-show-modal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">مشاهده نظر</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="service-detail" class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('back.partials.plugins', [
    'plugins' => ['persian-datepicker', 'jquery.validate'],
])
@push('scripts')
    <script src="{{ asset('back/assets/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('back/assets/js/pages/cooperationSales/servicesIndex.js') }}"></script>
@endpush
