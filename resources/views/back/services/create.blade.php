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
                                    <li class="breadcrumb-item">امکانات
                                    </li>
                                    <li class="breadcrumb-item active">امکانات جدید
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
                        <h4 class="card-title">اضافه نمودن امکانات جدید</h4>

                    </div>

                    <div id="main-card" class="card-content">
                        <div class="card-body">
                            <div class="col-12 col-md-10 offset-md-1">
                                @if (!$services)
                                    <form class="form" id="user-create-form" action="{{ route('admin.services.store') }}"
                                        method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-body">
                                            <div class="row mt-1">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        نام:
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>اسم امکانات را کامل وارد کنید</label>

                                                        <input id="main_price" type="text" placeholder="نام"
                                                            class="form-control " name="name" style="margin-left: 4px"
                                                            value="{{ old('name') }}">

                                                        @error('name')
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="row mt-1">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        سمت:
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>سمت امکانات در مجموعه</label>
                                                        <div class="d-flex align-items-center">

                                                            <input id="main_price" type="text" placeholder="سمت"
                                                                class="form-control" name="role" style="margin-left: 4px"
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
                                                            <input id="main_price" type="text" placeholder="عنوان"
                                                                class="form-control" name="title" style="margin-left: 4px"
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
                                            </div> --}}

                                            <div class="row mt-1">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        توضیحات
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <div class="d-flex align-items-center">
                                                            <textarea id="description" class="form-control" name="description" style="margin-left: 4px">{{ old('description') }}</textarea>

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
                                                        عکس پروفایل
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
                                        action="{{ route('admin.services.update', [$services->id]) }}" method="post"
                                        enctype="multipart/form-data">
                                        @method('put')
                                        @csrf

                                        <div class="form-body">
                                            <div class="row mt-1">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        اسم امکانات:
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>اسم امکانات را کامل وارد کنید</label>

                                                        <input id="main_price" type="text"
                                                            placeholder="نام و نام خانوادگی" class="form-control "
                                                            name="name" style="margin-left: 4px"
                                                            value="{{ $services->name }}">

                                                        @error('name')
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
                                                            <textarea id="description" placeholder="توضیحات" class="form-control" name="description" style="margin-left: 4px">{{ $services->description }}</textarea>

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
                                                        عکس ها پروفایل
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
                                                        عکس های موجود
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="d-flex">
                                                        <div
                                                            class="d-flex align-items-start gallery_container_{{ $services->id }}">
                                                            <img src="{{ $services->photo }}" class="GalleryImage"
                                                                alt="">
                                                        </div>

                                                    </div>
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
    <script src="{{ asset('back/assets/js/pages/cooperationSales/servicesCreate.js') }}?v=51"></script>
@endpush
