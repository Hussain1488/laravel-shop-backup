@extends('back.layouts.master')

@section('content')
    <div class="app-content content">
        <input type="hidden" class="en_start_date" name="start_date" value="">
        <input type="hidden" class="en_end_date" name="end_date" value="">
        <input type="hidden" name="operator" id="operato-id" data-action="{{ route('admin.get.operator.activity.data') }}"
            class="form-control w-auto mr-1" value="{{ $operator->id }}">
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
                                    <li class="breadcrumb-item active">اپراتور:
                                        {{ $operator->first_name . ' ' . $operator->last_name }}
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
                        <h4 class="card-title">
                            <div class="row mt-1 ml-2 mb-2">

                                لیست فعالیت های اپراتور:
                                {{ $operator->first_name . ' ' . $operator->last_name }}

                            </div>
                        </h4>
                    </div>

                    <div class="card-content">
                        <div class="container mt-3">


                            <!-- Tab panes -->
                            <div class="">
                                <div id="home" class="container tab-pane "><br>


                                    <div class="">

                                        {{-- <form action="{{ route('admin.operatoractivity.filter') }}" method="post">
                                            @csrf
                                            <div class="row ">
                                                <div class="col-md-6 col-12 d-flex justify-content-around">
                                                    <h4>
                                                        جستوجو گیرنده عملیات اساس شماره تماس
                                                    </h4>
                                                    <div class="d-flex"> --}}

                                        {{-- <input type="text" name="filter"
                                                            class="form-control w-auto mr-1" placeholder="جستجو">
                                                        <input type="submit" class="btn btn-info" value="جستجو">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                </div>
                                            </div>
                                        </form> --}}
                                    </div>

                                    <table class="" id="operatorActivity">
                                        <thead>

                                        </thead>
                                        {{-- @php
                                            $counter = ($operations->currentPage() - 1) * $operations->perPage() + 1;
                                        @endphp --}}

                                        <tbody>

                                            {{-- @foreach ($operations as $key)
                                                <tr>
                                                    <td>
                                                        {{ $counter++ }}
                                                    </td>
                                                    <td>
                                                        {{ $key->workdescription }}
                                                    </td>
                                                    <td>
                                                        @if ($key->user_id != null)
                                                            {{ $key->user->username }}
                                                        @else
                                                            <span class="text-danger">گیرنده ندارد!</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ jdate($key->created_at)->format('Y/m/d') }}
                                                        <br>
                                                        {{ $key->created_at->format('H:i:s') }}
                                                    </td>
                                                    <td>
                                                        <button data-date="{{ jdate($key->created_at)->format('Y/m/d') }}"
                                                            data-time="{{ $key->created_at->format('H:i:s') }}"
                                                            data-action="{{ route('admin.operatoractivity.details', [$key->id]) }}"
                                                            class="btn details-show" data-id="{{ $key->id }}"
                                                            value=""><i class="text-success feather icon-info"></i>
                                                        </button>

                                                    </td>
                                                </tr>
                                            @endforeach --}}

                                        </tbody>
                                    </table>

                                </div>
                            </div>

                        </div>
                    </div>
                    {{-- <div class="m-3">
                        {{ $operations->links() }}
                    </div> --}}
                </section>

            </div>
        </div>
    </div>
    <div class="modal fade" id="activity_details">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">جزئیات عملیات:<span class="text-success" id="deposit_amount_show"></span>
                    </h4>
                </div>
                <hr />

                <!-- Modal body -->
                <div class="modal-body p-2">


                </div>

                <!-- Modal footer -->

                <div class="modal-footer">

                </div>
                <button type="button" class="btn btn-danger m-1 btn-sm" data-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
@endsection
@include('back.partials.plugins', [
    'plugins' => ['persian-datepicker', 'jquery.validate'],
])
@push('scripts')
    <script src="{{ asset('back/assets/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('back/assets/js/pages/operatorActivity/index.js') }}"></script>
@endpush
