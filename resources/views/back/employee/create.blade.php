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
                                    <li class="breadcrumb-item">پرسنل
                                    </li>
                                    <li class="breadcrumb-item active">پرسنل جدید
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <section id="main-card" class="card">
                    <div class="card-header">
                        <h4 class="card-title">اضافه نمودن پرسنل جدید</h4>
                    </div>
                    <div id="main-card" class="card-content">
                        <div class="card-body">
                            <div class="col-12 col-md-10 offset-md-1">
                                @if (!$employee)
                                    <form class="form" id="user-create-form" action="{{ route('admin.employee.store') }}"
                                        method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-body">
                                            <div class="row mt-1">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        نام و نام خانوادگی:
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>اسم پرسنل را کامل وارد کنید</label>

                                                        <input type="text" placeholder="نام و نام خانوادگی"
                                                            class="form-control " name="full_name" style="margin-left: 4px"
                                                            value="{{ old('full_name') }}">

                                                        @error('full_name')
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-1">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        سمت:
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>سمت پرسنل در مجموعه</label>
                                                        <div class="d-flex align-items-center">
                                                            <input type="text" placeholder="سمت" class="form-control"
                                                                name="role" style="margin-left: 4px"
                                                                value="{{ old('role') }}">
                                                        </div>
                                                        <span class="text-danger price_limit_error"></span>
                                                        @error('role')
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-1">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        عنوان:
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <div class="d-flex align-items-center">
                                                            <input type="text" placeholder="عنوان" class="form-control"
                                                                name="title" style="margin-left: 4px"
                                                                value="{{ old('title') }}">
                                                        </div>
                                                        <span class="text-danger price_limit_error"></span>
                                                        @error('title')
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        توضیحات
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <div class="d-flex align-items-center">
                                                            <textarea id="description" placeholder="توضیحات" name="description" class="form-control" style="margin-left: 4px">{{ old('description') }}</textarea>
                                                        </div>
                                                        <span class="text-danger price_limit_error"></span>
                                                        @error('description')
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-1">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        عکس پرسنل
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <div class="d-flex align-items-center">
                                                            <input type="file" class="form-control mt-1 mr-1 imageInput"
                                                                name="photo" value="{{ old('photo') }}">
                                                        </div>
                                                        <span class="text-danger price_limit_error"></span>
                                                        <span class="text-danger">
                                                            @error('photo')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                    </div>
                                                    <div class="imgContainer"></div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-12">
                                                <button type="submit" id="submit_button"
                                                    class="btn btn-primary mr-1 mb-1 waves-effect waves-light">
                                                    تأیید نهایی
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @else
                                    <form class="form" id="user-create-form"
                                        action="{{ route('admin.employee.update', [$employee->id]) }}" method="post"
                                        enctype="multipart/form-data">
                                        @method('put')
                                        @csrf
                                        <div class="form-body">
                                            <div class="row mt-1">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        نام و نام خانوادگی:
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>اسم پرسنل را کامل وارد کنید</label>

                                                        <input type="text" placeholder="نام و نام خانوادگی"
                                                            class="form-control " name="full_name"
                                                            style="margin-left: 4px" value="{{ $employee->full_name }}">

                                                        @error('full_name')
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-1">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        سمت:
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>سمت پرسنل در مجموعه</label>
                                                        <div class="d-flex align-items-center">

                                                            <input type="text" placeholder="سمت" class="form-control"
                                                                name="role" style="margin-left: 4px"
                                                                value="{{ $employee->role }}">
                                                        </div>
                                                        <span class="text-danger price_limit_error"></span>
                                                        @error('role')
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-1">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        عنوان:
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <div class="d-flex align-items-center">
                                                            <input type="text" placeholder="عنوان"
                                                                class="form-control" name="title"
                                                                style="margin-left: 4px" value="{{ $employee->title }}">
                                                        </div>
                                                        <span class="text-danger price_limit_error"></span>
                                                        @error('title')
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        توضیحات
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <div class="d-flex align-items-center">
                                                            <textarea id="description" class="form-control" name="description" style="margin-left: 4px">{{ $employee->description }}</textarea>
                                                        </div>
                                                        <span class="text-danger price_limit_error"></span>
                                                        @error('description')
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-1">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        عکس پرسنل
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <div class="d-flex align-items-center">
                                                            <input type="file"
                                                                class="form-control mt-1 mr-1 imageInput" name="photo"
                                                                value="{{ old('photo') }}">
                                                        </div>
                                                        <span class="text-danger price_limit_error"></span>
                                                        <span class="text-danger">
                                                            @error('photo')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                        <div class="imgContainer"></div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row mt-1">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        عکس فعلی:
                                                    </h5>
                                                </div>
                                                <div class="d-flex flex-wrap col-md-6 col-12">

                                                    <div
                                                        class="d-flex align-items-start gallery_container_{{ $employee->id }}">
                                                        <img src="{{ $employee->photo }}" class="GalleryImage"
                                                            alt="">
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-12">
                                                <button type="submit"
                                                    class="btn btn-primary mr-1 mb-1 waves-effect waves-light">
                                                    تأیید نهایی
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection

@include('back.partials.plugins', ['plugins' => ['jquery.validate', 'ckeditor']])

@push('scripts')
    <script>
        var url = '{{ route('admin.user.searchUser') }}';
    </script>
    <script src="{{ asset('back/assets/js/pages/cooperationSales/employeeCreate.js') }}?v=51"></script>
@endpush
