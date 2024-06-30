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
                                    <li class="breadcrumb-item active">فعالیت اپراتور ها
                                    </li>
                                    <li class="breadcrumb-item active">لیست اپراتور ها
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

                    </div>

                    <div class="card-content">
                        <div class="container mt-3">
                            <input type="hidden" id="user_data" value="{!! json_encode($users) !!}">

                            <!-- Tab panes -->
                            <div class="">
                                <div id="home" class="container tab-pane "><br>


                                    {{-- <div class="">

                                        <form action="{{ route('admin.operatoractivity.search') }}" method="post">
                                            @csrf
                                            <div class="row ">
                                                <div class="col-md-6 col-12 d-flex justify-content-around">
                                                    <h4>
                                                        جستوجو بر اساس شماره تماس
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
                                    </div> --}}

                                    <div class="row mt-1 ml-2 mb-2">
                                        <h3>
                                            لیست اپراتور ها
                                        </h3>
                                    </div>


                                    <table class="table table-hover" id="operator_list">
                                        <thead>
                                            <tr>
                                                <th>#</th>

                                                <th>
                                                    نام اپراتور
                                                </th>
                                                <th>
                                                    شماره تماس </th>
                                                <th>
                                                    مقام ها:
                                                </th>


                                            </tr>
                                        </thead>
                                        @php
                                            $counter = 1;
                                        @endphp

                                        <tbody>

                                            @foreach ($users as $key)
                                                <tr>
                                                    <td>
                                                        {{ $counter++ }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.operatoractivity.show', [$key->id]) }}">
                                                            {{ $key->first_name . ' ' . $key->last_name }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ $key->username }}
                                                    </td>
                                                    <td>
                                                        @foreach ($key->roles as $value)
                                                            {{ $value->title . ', ' }}
                                                        @endforeach
                                                    </td>


                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>

                                </div>
                            </div>

                        </div>
                    </div>
                    {{-- <div class="m-3">
                        {{ $users->links() }}
                    </div> --}}
                </section>


            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('back/assets/js/pages/users/all.js') }}"></script>
    <script src="{{ asset('back/assets/js/pages/installmentpurchse/create.js') }}"></script>
    <script src="{{ asset('back/assets/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('back/assets/js/pages/operatorActivity/index.js') }}"></script>
@endpush
