@extends('back.layouts.master')

@section('content')
    @use \Morilog\Jalali\Jalalian;

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
                                    <li class="breadcrumb-item active">گزارش کرن جاب
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
                        <h4 class="card-title"></h4>
                    </div>

                    <div class="card-content">
                        <div class="container mt-3">


                            <!-- Tab panes -->
                            <div class="">
                                <div id="home" class="container tab-pane "><br>



                                    <div class="row mt-1 ml-2 mb-2">
                                        <h3>
                                            لیست لاگ های کرن جاب
                                        </h3>
                                    </div>


                                    <table class="table table-hover" id="cornjob_report_table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    #
                                                </th>
                                                <th>
                                                    موضوع
                                                </th>
                                                <th>
                                                    توضیح
                                                </th>
                                                <th>
                                                    تاریخ
                                                </th>

                                            </tr>
                                        </thead>
                                        @php
                                            $counter = 1;
                                        @endphp
                                        <tbody>

                                            @foreach ($cornjobs as $key)
                                                <tr>
                                                    <td>
                                                        {{ $counter++ }}
                                                    </td>
                                                    <td>
                                                        {{ $key->name }}
                                                    </td>
                                                    <td>
                                                        {{ $key->description }}
                                                    </td>
                                                    <td>
                                                        {{ jdate($key->created_at)->format('Y/m/d') }}
                                                        <br>
                                                        {{ $key->created_at->format('H:i:s') }}

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

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
    <script src="{{ asset('back/assets/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('back/assets/js/pages/cornjob/index.js') }}"></script>
@endpush
