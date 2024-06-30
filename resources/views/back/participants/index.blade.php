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
                                    <li class="breadcrumb-item">مدیریت وبلاگ
                                    </li>
                                    <li class="breadcrumb-item active">لیست اشتراک کننده ها در مسابقه </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">

                <section class="card">
                    <div class="card-header">
                        <h4 class="card-title">لیست شرکت کننده ها</h4>
                    </div>
                    <div class="card-content" id="main-card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0 participant_datatable"
                                    action='{{ route('admin.participant.datatable') }}'>

                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    {{-- delete post modal --}}
    <div class="modal fade text-left" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel19"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel19">آیا مطمئن هستید؟</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    با حذف شرکت کننده دیگر قادر به بازیابی آن نیستید.
                </div>
                <div class="modal-footer">
                    <form action="#" id="post-delete-form">
                        @csrf
                        @method('delete')
                        <input type="hidden" name='id' value id="participant-id">
                        <button type="button" class="btn btn-success waves-effect waves-light"
                            data-dismiss="modal">خیر</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">بله حذف شود</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade text-left" id="state-modal" tabindex="-1" role="dialog" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg participant-stat-content"
            role="document">

        </div>
    </div>
    <div class="modal fade text-left" id="participant-state-add-modal" tabindex="-1" role="dialog" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel19">فرم ساخت پست جدید</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row m-1">
                        <div class="col-4 ">
                            اسم شرکت کننده:
                        </div>
                        <div class="col-6 participant-name">

                        </div>

                    </div>
                    <div class="row mx-1">
                        <div class="col-4">
                            اسم مستعار شرکت کننده:
                        </div>
                        <div class="col-6 participant-username">

                        </div>
                    </div>
                    <div class="row m-1">
                        <div class="col-4">
                            شماره شرکت کننده:
                        </div>
                        <div class="col-6 participant-phone">

                        </div>
                    </div>

                    <form action="{{ route('admin.participant.postStore') }}" method="post"
                        id="add-participant-state-form">
                        @csrf
                        <input type="hidden" name="participant_id" value="" class="participant-id">
                        <div class="participant-stat m-1 border rounded-md">
                            <div class="row">
                                <div class="col-12 col-lg-6 col-xl-6">
                                    <div class="m-1">
                                        اسم پست:
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 col-xl-6">
                                    <div class="m-1">
                                        <input type="text" name="name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 col-xl-6">
                                    <div class="m-1">
                                        لینک ویدئو:
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 col-xl-6">
                                    <div class="m-1">
                                        <input type="text" name="video" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 col-xl-6">
                                    <div class="m-1">
                                        کپشن پست:
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 col-xl-6">
                                    <div class="m-1">
                                        <textarea name="caption" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 col-xl-6">
                                    <div class="m-1">
                                        تصاویر پست
                                    </div>
                                </div>

                                <div class="col-12 col-lg-12 col-xl-12">
                                    <label>تصاویر پست ( <small>بهترین اندازه <span
                                                class="text-danger">{{ config('front.imageSizes.productGalleryImage') }}</span>
                                            پیکسل میباشد.</small> )</label>

                                    <div class="dropzone dropzone-area  participant-images" id="participant-images">

                                        <div class="dz-message h-auto">تصاویر را به اینجا بکشید
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-warning waves-effect waves-light"
                            data-dismiss="modal">انصراف</button>
                        <button type="submit" class="btn btn-success waves-effect waves-light">ثبت</button>
                        <button type="button"
                            class="btn btn-outline-success waves-effect waves-light submit-state-add-state">ثبت و افزودن
                            پست</button>
                    </form>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
@endsection
@include('back.partials.plugins', [
    'plugins' => [
        'ckeditor',
        'jquery-tagsinput',
        'jquery.validate',
        'jquery-ui',
        'jquery-ui-sortable',
        'dropzone',
        'persian-datepicker',
    ],
])

@push('scripts')
    <script src="{{ asset('back/assets/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('back/assets/js/pages/participant/index.js') }}"></script>
@endpush
