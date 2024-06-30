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
                                    <li class="breadcrumb-item active">لیست حساب های داخلی
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <section class="card">

                    <div class="card-content">
                        <div>
                            <div class="m-1">
                                <h2>
                                    @can('installmentreports.createinternalaccount')
                                        <a href="{{ route('admin.installmentreports.createinternalaccount') }}"
                                            style="font-size:16px" class=""><i style="font-size:30px"
                                                class="feather icon-plus-circle text-success"></i> ایجاد
                                            حساب
                                        </a>
                                    @endcan
                                </h2>
                            </div>
                            <div class="mx-2 my-2">
                                <h3>
                                    لیست حساب ها
                                </h3>
                            </div>
                            @foreach ($listbank as $item)
                                <div class="border rounded p-2 m-1">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            <a
                                                href="{{ route('admin.installmentreports.transactionFilter', [$item->id]) }}">
                                                نام حساب :
                                                {{ $item->bankname }}
                                            </a>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            شماره حساب : {{ $item->accountnumber }}
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            ماهیت حساب :
                                            {{ $item->account_type->name }}
                                        </div>


                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                    <div class="m-3">
                        {{ $listbank->links() }}
                    </div>
                </section>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('back/assets/js/pages/installmentsReport/create.js') }}"></script>
@endpush
