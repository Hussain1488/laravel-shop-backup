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
                                    <li class="breadcrumb-item active">ایجاد فروشگاه
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
                        <h4 class="card-title">همکاری در فروش</h4>
                    </div>
                    <div class="card-content">
                        <h6 class="card-title m-2">ساخت افرادی که همکاری در فروش دارند</h6>
                        <div class="container mt-3">


                            {{-- creating new store form --}}
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <form action="{{ route('admin.createcolleague.store') }}" method="POST"
                                    enctype="multipart/form-data" id="store_create_form">
                                    @csrf

                                    <div id="home" class="container tab-pane active"><br>
                                        <div class="row">

                                            <div class="col-md-6 col-12">
                                                <div class="form-group align-items-center">

                                                    <h6 for="first_name" class="mr-2">
                                                        انتخاب فرد مورد نظر از بین افرادی که لاگین کردند
                                                    </h6>

                                                    <div class="d-flex">
                                                        <select type="text" id="user_selection"
                                                            class="form-control user_select2" name="user_id">

                                                        </select>
                                                    </div>
                                                    @error('user_id')
                                                        <span class="text-danger">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror

                                                    <div class="m-1">
                                                        <span class="" id="user_title"></span>
                                                        <span class="text-success" id="user_name"></span>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">

                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="">
                                                <div class="form-group align-items-center">
                                                    <label for="first_name" class="mr-2">
                                                        آپلود تعدادی عکس(قرارداد ها و فرم ها)
                                                    </label>

                                                    <div class="row">

                                                        <div class="col-sm">
                                                            <input multiple type="file"
                                                                class="form-control mt-1 mr-1 imageInput"
                                                                name="uploaddocument[]" value="{{ old('uploaddocument') }}">
                                                            <span class="text-danger">
                                                                @error('uploaddocument')
                                                                    {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </div>


                                                    </div>
                                                    <div class="imgContainer"></div>


                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-12 pt-2">
                                                <h5>
                                                    نام فروشگاه
                                                </h5>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="nameofstore"
                                                        value="{{ old('nameofstore') }}">
                                                    <span class="text-danger">
                                                        @error('nameofstore')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-12 pt-2">
                                                <h5>
                                                    عکس پروفایل
                                                </h5>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <input type="file" class="form-control imageInput2" name="photo"
                                                        value="{{ old('photo') }}">
                                                    <span class="text-danger">
                                                        @error('photo')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                    <div class="imgContainer2"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-12 pt-2">
                                                <h5>
                                                    آدرس فروشگاه
                                                </h5>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="addressofstore"
                                                        value="{{ old('addressofstore') }}">
                                                    <span class="text-danger">
                                                        @error('addressofstore')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-12 pt-2">
                                                <h5>
                                                    موقعیت
                                                </h5>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="location"
                                                        value="{{ old('addressofstore') }}">
                                                    <span class="text-danger">
                                                        @error('location')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-12 pt-2">
                                                <h5>
                                                    میزان فروش اقساطی ماهانه
                                                </h5>
                                            </div>
                                            <div class="col-md-6 col-12">

                                                <div class="form-group">
                                                    <div class="d-flex align-items-center">
                                                        <input type="text" class="form-control moneyInput"
                                                            id="moneyInput" name="storecredit" style="margin-left: 4px"
                                                            value="{{ old('storecredit') }} ">
                                                        ریال

                                                    </div>
                                                </div>
                                                <span class="text-danger">
                                                    @error('storecredit')
                                                        {{ $message }}
                                                    @enderror
                                                </span>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-12 pt-2">
                                                <h5>
                                                    درصد کارمزد فروشگاه
                                                </h5>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <input type="number" class="form-control" name="feepercentage"
                                                        value="{{ old('feepercentage') }}">
                                                    <span class="text-danger">
                                                        @error('feepercentage')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-12 pt-2">
                                                <h5>
                                                    فروش اقساطی بدون کارمزد
                                                </h5>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <label for="">جواز فروش اقساطی بدون کارمز</label>
                                                <div class="form-group">
                                                    <select name="no_profit_installment" id="no_profit_installment"
                                                        class="form-control">
                                                        <option
                                                            {{ old('no_profit_installment') == 'no' ? 'selected' : '' }}
                                                            value="no">مجاز نیست</option>
                                                        <option
                                                            {{ old('no_profit_installment') == 'yes' ? 'selected' : '' }}
                                                            value="yes">
                                                            مجاز است</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row no_profit_fee">
                                            <div class="col-md-6 col-12 pt-2">
                                                <h5>
                                                    درصد کارمز فروش بدون کارمزد
                                                </h5>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <input type="number" class="form-control" name="no_profit_fee"
                                                        value="{{ old('no_profit_fee') ? old('no_profit_fee') : 15 }}">
                                                    <span class="text-danger">
                                                        @error('no_profit_fee')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-12 pt-2">
                                                <h5>
                                                    مدت زمان برای تصفیه
                                                </h5>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <input type="number" class="form-control" name="settlementtime"
                                                        value="{{ old('settlementtime') }}">
                                                    <span class="text-danger">
                                                        @error('settlementtime')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-12 pt-2">
                                                <h5>
                                                    حساب واریز درامد
                                                </h5>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <div class="d-flex">
                                                        <select type="text"
                                                            class="form-control select2 account_selection"
                                                            name="account_id">
                                                            @isset($accounts)
                                                                <option attr-name="" value="none">انتخاب حساب درآمد
                                                                </option>
                                                                @foreach ($accounts as $item)
                                                                    <option attr-name="{{ $item->bankname }}"
                                                                        {{ old('account_id') == $item->id ? 'selected' : '' }}
                                                                        value="{{ $item->id }}">
                                                                        {{ $item->accountnumber }}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">کاربری برای انتخاب وجود ندارد
                                                                </option>
                                                            @endisset
                                                        </select>
                                                    </div>
                                                    <div class="m-1">
                                                        <span class="account-title" id=""></span>
                                                        <span class="text-success account-name" id=""></span>
                                                    </div>
                                                    <span class="text-danger">
                                                        @error('account_id')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-12 pt-2">
                                                <h5>
                                                    تاریخ پایان قرارداد
                                                </h5>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <input type="text" placeholder="تاریخ پایان قرار داد را مشخص کنید."
                                                        class="form-control persian-date-picker" name="enddate"
                                                        value="{{ old('enddate') }}" data-timestamps="false">
                                                    <span class="text-danger">
                                                        @error('enddate')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row p-1 d-flex justify-content-between">
                                            <div class="col p-1">
                                                <input type="button" id="summit_button" value="تأیید"
                                                    class="btn btn-info btn-lg"
                                                    style="background-color: none; text-color:black">
                                            </div>
                                            <div class="col d-flex justify-content-end p-1">
                                                <a href="{{ route('admin.createcolleague.shopList') }}"
                                                    style="font-size:20px" class="btn btn-primary"><i
                                                        class="feather icon-arrow-left-circle"></i>
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </form>
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
    <script>
        var url = '{{ route('admin.user.searchUser') }}'
    </script>
    <script src="{{ asset('back/assets/js/pages/createcollegue/myscript.js') }}"></script>
@endpush
