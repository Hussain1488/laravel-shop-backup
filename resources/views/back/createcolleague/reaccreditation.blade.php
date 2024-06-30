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
                                    <li class="breadcrumb-item active">اعتبار فروشگاه
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
                        <h6 class="card-title m-2">اعتبار فروشگاه</h6>
                        <div class="container mt-3">

                            {{-- rising the credit of store form --}}

                            <form action="{{ route('admin.createcolleague.reaccreditation.store') }}" method="POST"
                                enctype="multipart/form-data" id="reaccreditationStoreForm">
                                @csrf


                                <div class="tab-content">
                                    <div id="home" class="container tab-pane active"><br>


                                        <div class="row">
                                            <div class="col-lg-3 col-md-6 col-12">
                                                <h5>
                                                    فروشگاه مد نظر
                                                </h5>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>سرچ بر اساس شماره تلفن</label>
                                                    <select dir="rtl" type="text" class="form-control select2"
                                                        name="select_store">
                                                        <option value="">فروشگاه را انتخاب کنید</option>
                                                        @foreach ($store as $item)
                                                            <option
                                                                {{ old('select_store') == $item->id ? ' selected' : '' }}
                                                                value="{{ $item->id }}">
                                                                فروشگاه:
                                                                {{ $item->nameofstore . '. شماره تماس:' . $item->user->username }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                    @error('select_store')
                                                        <span class="text-danger">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-12">
                                            <h5>
                                                نوعیت تراکنش
                                            </h5>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-12">
                                            <div class="form-group">

                                                <select dir="rtl" type="text" class="form-control select2"
                                                    name="trans_type">
                                                    <option value="increase">شارژ اعتبار</option>
                                                    <option value="decrease">کاهش اعتبار</option>
                                                </select>
                                                @error('trans_type')
                                                    <span class="text-danger">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-lg-3 col-md-6 col-12">

                                        <h5 for="purchasecredit" class="mr-2">
                                            مبلغ به ریال وارد شود
                                        </h5>


                                    </div>
                                    <div class="col-lg-3 col-md-6 col-12">
                                        <div class="d-flex align-items-center">
                                            <input type="text" placeholder="100,000" class="form-control moneyInput"
                                                id="recredition_amount" name="storecredit" style="margin-left: 4px;"
                                                value="{{ old('storecredit') }}">
                                            <span>ریال</span>
                                        </div>
                                        @error('storecredit')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row ">
                                    <div
                                        class="col-lg-3 col-md-6 col-12 d-flex align-items-baseline justify-content-center">
                                        <input type="button" id="summit_button1"
                                            class="btn btn-primary my-1"value=" تأیید نهایی تغییرات" />
                                    </div>
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

@include('back.partials.plugins', [
    'plugins' => ['persian-datepicker', 'jquery.validate'],
])


@push('scripts')
    <script>
        var url = '';
    </script>
    <script src="{{ asset('back/assets/js/pages/createcollegue/myscript.js') }}"></script>
@endpush
