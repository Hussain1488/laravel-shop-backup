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
                                    <li class="breadcrumb-item"> فرم ایجاد حساب های داخلی
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
                        <div class="m-1">
                            <h2>

                            </h2>
                        </div>
                        <form action="{{ route('admin.installmentreports.storebank') }}" id="create_bank_form"
                            method="POST">
                            @csrf
                            <input type="hidden" name="accounttype" value="none">
                            <div class="container mt-3">

                                <div class="row">
                                    <div class="col-md-3 col-6 pt-2">
                                        <h5>
                                            نام حساب
                                        </h5>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <input type="text" class="form-control" name="bankname"
                                            value="{{ old('bankname') }}">
                                        @error('bankname')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>




                                <div class="row mt-8">
                                    <div class="col-md-3 col-6 pt-2 ">
                                        <h5>
                                            ماهیت حساب
                                        </h5>
                                    </div>
                                    <div class="col-md-3 col-6">

                                        <div class="form-group">

                                            <select type="text" id="Account_type" class="form-control user_select2"
                                                name="account_type_id">
                                                @foreach ($types as $key)
                                                    <option data-code={{ $key->code }} value="{{ $key->id }}">
                                                        {{ $key->name }}</option>
                                                @endforeach
                                            </select>

                                            @error('accounttype')
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror

                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-8 gateway_input">
                                    <div class="col-md-3 col-6 pt-2 ">
                                        <h5>
                                            درگاه حساب
                                        </h5>
                                    </div>
                                    <div class="col-md-3 col-6">

                                        <div class="form-group">

                                            <select type="text" id="account_gatewy" class="form-control user_select2"
                                                name="gateway">
                                                <option value="">انتخاب درگاه حساب</option>
                                                @foreach ($gateways as $key)
                                                    <option value="{{ $key->id }}">
                                                        {{ $key->name }}</option>
                                                @endforeach
                                            </select>

                                            @error('accounttype')
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror

                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-8">
                                    <div class="col-md-3 col-6 pt-2">
                                        <h5>
                                            شماره حساب
                                        </h5>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="شماره حساب"
                                                id="main_account_number" name="accountnumber">
                                            <div class="input-group-append">
                                                <input class="input-group-text" name='accountnumber_prefix'
                                                    id="Acount_number_prefix" style="width: 40px" value="0">
                                                {{-- 1</span> --}}
                                            </div>
                                        </div>
                                        @error('accountnumber')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row p-1 d-flex justify-content-between">
                                    <div class="col p-1">
                                        <input type="button" id="creating_bank_button" value="ایجاد حساب "
                                            class="btn btn-info">
                                    </div>
                                    <div class="col d-flex justify-content-end p-1">
                                        <a href="{{ route('admin.installmentreports.banklist') }}" style="font-size:20px"
                                            class="btn btn-primary"><i class="feather icon-arrow-left-circle"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>

        </div>
    </div>
@endsection
@include('back.partials.plugins', ['plugins' => ['jquery.validate']])
@push('scripts')
    <script src="{{ asset('back/assets/js/pages/installmentsReport/create.js') }}"></script>
@endpush
