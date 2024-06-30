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
                                    <li class="breadcrumb-item">تنظیمات
                                    </li>
                                    <li class="breadcrumb-item active">تنظیمات پیامک
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- users edit start -->
                <section class="users-edit">
                    <div class="card">
                        <div id="main-card" class="card-content">
                            <div class="card-body">
                                <div class="tab-content">
                                    <form id="corn_job_form" action="{{ route('admin.cornjob.store') }}" method="POST">
                                        @csrf
                                        <h4 class="my-1">تنظیمات کرن جاب</h4>
                                        <hr>
                                        <fieldset>
                                            <legend>
                                                کرن جاب اعتبار فروشگاه
                                            </legend>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    <label>روز فراخوانی کرن
                                                        جاب <span class="text-danger">(۱ الی ۲۹)</span></label>
                                                    <input type="number" class="form-control reccredit_day corn_job_day"
                                                        data-min="1" data-max='29' data-class="corn_job_day_message"
                                                        name="cornjob_call_day"
                                                        value="{{ option('cornjob_call_day') != null ? option('cornjob_call_day') : 1 }}">

                                                    <span class="text-danger text-sm corn_job_day_message"></span>
                                                    @error('cornjob_call_day')
                                                        <span class="text-danger">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>

                                            </div>

                                            <div class="row my-2">

                                                <div class="form-group col-md-4">
                                                    <fieldset class="checkbox">
                                                        <div class="vs-checkbox-con vs-checkbox-primary ">
                                                            <input data-class='reccredit_day' class="check_status checkbox"
                                                                id='store_recredit_check' type="checkbox"
                                                                name="store_reccredition_status"
                                                                {{ option('store_reccredition_status') == 'on' ? 'checked' : '' }}>
                                                            <span class="vs-checkbox">
                                                                <span class="vs-checkbox--check">
                                                                    <i class="vs-icon feather icon-check"></i>
                                                                </span>
                                                            </span>
                                                            <span class="">فعال</span>
                                                        </div>
                                                    </fieldset>
                                                </div>

                                            </div>
                                        </fieldset>
                                        <hr>

                                        <fieldset>
                                            <legend>
                                                پیامک اقساط رو به انقضاء </legend>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    <label>مشخص کنید چند روز قبل از ختم تاریخ پرداخت قسط پیامک ارسال شود.
                                                        <span class="text-danger">(۱ الی ۷)</span></label>
                                                    <input id="" type="number" data-min="1" data-max='7'
                                                        data-class="corn_job_befor_message"
                                                        class="form-control befor_pay_message corn_job_day"
                                                        name="message_send_befor_day"
                                                        value="{{ option('message_send_befor_day') != null ? option('message_send_befor_day') : 1 }}">

                                                    <span class="text-danger text-sm corn_job_befor_message"></span>
                                                    @error('message_send_befor_day')
                                                        <span class="text-danger">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label>متن پیامک ارسالی:</label>
                                                    <textarea id="reccredit_day" type="text" class="form-control befor_pay_message" name="installment_befor_message">{{ option('installment_befor_message') != null ? option('installment_befor_message') : 'مشتری عزیز موعد قسط رو به پایان است لطفا هرچه زود تر جهت پرداخت قسط اقدام کنید!' }}</textarea>
                                                    <span class="text-danger" id="validationMessage"></span>
                                                    @error('installment_befor_message')
                                                        <span class="text-danger">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>

                                            </div>

                                            <div class="row my-2">

                                                <div class="form-group col-md-4">
                                                    <fieldset class="checkbox">
                                                        <div class="vs-checkbox-con vs-checkbox-primary">
                                                            <input data-class="befor_pay_message" type="checkbox"
                                                                id="befor_message_stat" class="checkbox"
                                                                name="message_send_befor_status"
                                                                {{ option('message_send_befor_status') == 'on' ? 'checked' : '' }}>
                                                            <span class="vs-checkbox">
                                                                <span class="vs-checkbox--check">
                                                                    <i class="vs-icon feather icon-check"></i>
                                                                </span>
                                                            </span>
                                                            <span class="">فعال</span>
                                                        </div>
                                                    </fieldset>
                                                </div>

                                            </div>
                                        </fieldset>
                                        <hr>

                                        <fieldset>

                                            <legend>
                                                پیامک اقساط منقضی شده </legend>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    <label>مشخص کنید چند روز بعد از ختم تاریخ پرداخت قسط پیامک ارسال شود.
                                                        <span class="text-danger">(۱ الی ۷)</span></label>
                                                    <input id="reccredit_day" type="number" data-min="1" data-max='7'
                                                        data-class="corn_job_after_message"
                                                        class="form-control after_pay_message corn_job_day"
                                                        name="message_send_after_day"
                                                        value="{{ option('message_send_after_day') != null ? option('message_send_after_day') : 1 }}">

                                                    <span class="text-danger text-sm corn_job_after_message"></span>
                                                    @error('message_send_after_day')
                                                        <span class="text-danger">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label>متن پیامک ارسالی:</label>
                                                    <textarea id="reccredit_day" type="text" class="form-control after_pay_message" name="installment_after_message">{{ option('installment_after_message') != null ? option('installment_after_message') : 'مشتری عزیز موعد قسط شما به پایان رسیده است لطفا هرچه زود تر جهت پرداخت قسط اقدام کنید!' }}</textarea>
                                                    <span class="text-danger" id="validationMessage"></span>
                                                    @error('installment_after_message')
                                                        <span class="text-danger">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>

                                            </div>

                                            <div class="row my-2">

                                                <div class="form-group col-md-4">
                                                    <fieldset class="checkbox">
                                                        <div class="vs-checkbox-con vs-checkbox-primary">
                                                            <input data-class="after_pay_message" type="checkbox"
                                                                id="after_message_stat" class="checkbox"
                                                                name="message_send_after_status"
                                                                {{ option('message_send_after_status') == 'on' ? 'checked' : '' }}>
                                                            <span class="vs-checkbox">
                                                                <span class="vs-checkbox--check">
                                                                    <i class="vs-icon feather icon-check"></i>
                                                                </span>
                                                            </span>
                                                            <span class="">فعال</span>
                                                        </div>
                                                    </fieldset>
                                                </div>

                                            </div>
                                        </fieldset>
                                        <hr>
                                        <fieldset>
                                            <legend>
                                                بکاپ خودکار دیتابیس
                                            </legend>
                                            <h3 style="font-size:large" class="mx-2">دوره:</h3>
                                            <div class="row mt-1">
                                                <div class="col-md-3 ">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio"
                                                            class="custom-control-input info_map_type backup-period"
                                                            id="daily_backup" name="backup_period" value="daily"
                                                            {{ option('backup_period') == 'daily' ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="daily_backup">روزانه</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio"
                                                            class="custom-control-input info_map_type backup-period"
                                                            id="weekly_backup" name="backup_period" value="weekly"
                                                            {{ option('backup_period') == 'weekly' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="weekly_backup">هفته
                                                            وار</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio"
                                                            class="custom-control-input info_map_type backup-period"
                                                            id="monthly_backup" name="backup_period" value="monthly"
                                                            {{ option('backup_period') == 'monthly' ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="monthly_backup">ماهانه</label>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row my-2">

                                                <div class="form-group col-md-4">
                                                    <fieldset class="checkbox">
                                                        <div class="vs-checkbox-con vs-checkbox-primary ">
                                                            <input data-class='backup-period'
                                                                class="check_status checkbox" id='backup_status'
                                                                type="checkbox" name="backup_status"
                                                                {{ option('backup_status') == 'on' ? 'checked' : '' }}>
                                                            <span class="vs-checkbox">
                                                                <span class="vs-checkbox--check">
                                                                    <i class="vs-icon feather icon-check"></i>
                                                                </span>
                                                            </span>
                                                            <span class="">فعال</span>
                                                        </div>
                                                    </fieldset>
                                                </div>

                                            </div>
                                        </fieldset>
                                        <div class="row">
                                            <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                                                <button type="button" id="cornjob_form_button"
                                                    class="btn btn-primary glow">ذخیره
                                                    تغییرات</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- users edit ends -->
            </div>
        </div>
    </div>
@endsection

@include('back.partials.plugins', ['plugins' => ['jquery.validate']])

@php
    $help_videos = [config('general.video-helpes.sms-config')];
@endphp

@push('scripts')
    <script src="{{ asset('back/assets/js/pages/settings/cornjob.js') }}?v=51"></script>
@endpush
