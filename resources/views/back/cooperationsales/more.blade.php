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
                                    <li class="breadcrumb-item">همکاران
                                    </li>
                                    <li class="breadcrumb-item active">بیشتر
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">

                {{--  creating new installments form --}}
                @isset($shop)
                    <section id="main-card" class="card">
                        <div class="card-header">
                            <h4 class="card-title">درباره فروشگاه: {{ $shop->nameofstore }}</h4>
                        </div>
                        <div id="main-card" class="card-content">
                            <div class="card-body">
                                <div class="col-12 col-md-10 offset-md-1">
                                    <form class="form" id="shop-update-form"
                                        action="{{ route('admin.shop_more.moreStore', [$shop->id]) }}" method="post"
                                        enctype="multipart/form-data">
                                        @method('put')
                                        @csrf
                                        <div class="form-body">
                                            <div class="card-header">
                                                <h4 class="card-title">افزودن اطلاعات فروشگاه</h4>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-12 pt-2">
                                                    <h5>

                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="">عکس پروفایل</label>
                                                        <div class=""
                                                            style="width:120px; border: 1px solid rgba(80, 80, 80, 0.425); border-radius:10px">
                                                            <img style="border-radius:10px" src="{{ asset($shop->photo) }}">
                                                        </div>

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
                                                        نام فروشگاه
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="nameofstore"
                                                            value="{{ $shop->nameofstore ?? '' }}">
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
                                                        شماره تماس
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control imageInput" name="phone"
                                                            value="{{ old('phone') }}">
                                                        <span class="text-danger">
                                                            @error('phone')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                    </div>
                                                    <div class="imgContainer"></div>
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
                                                        <input type="file" class="form-control imageInput" name="photo"
                                                            value="{{ old('photo') }}">
                                                        <span class="text-danger">
                                                            @error('photo')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                    </div>
                                                    <div class="imgContainer"></div>
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
                                                            value="{{ $shop->addressofstore }}">
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
                                                            value="{{ $shop->location }}">
                                                        <span class="text-danger">
                                                            @error('location')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-1">

                                                <div class="col-12 col-lg-12 col-xl-12">
                                                    <label>گالری مجموعه ( <small>بهترین اندازه <span
                                                                class="text-danger">{{ config('front.imageSizes.productGalleryImage') }}</span>
                                                            پیکسل میباشد.</small> )</label>

                                                    <div class="dropzone dropzone-area mb-2 gallery-images"
                                                        id="participant-images">

                                                        <div class="dz-message h-auto">تصاویر را به اینجا بکشید
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <div class="d-flex align-items-center">
                                                            <input multiple type="file"
                                                                class="form-control mt-1 mr-1 imageInput2" name="gallery[]"
                                                                value="{{ old('gallery') }}">
                                                        </div>
                                                        <span class="text-danger price_limit_error"></span>

                                                        <div class="imgContainer2"></div>
                                                    </div>

                                                </div> --}}
                                            </div>
                                            <div class="row mt-1">
                                                <div class="col-md-6 col-12">
                                                    <h5>
                                                        گالری موجود
                                                    </h5>
                                                </div>
                                                <div class="d-flex flex-wrap col-md-6 col-12">
                                                    @foreach ($shop->gallery as $key)
                                                        <div
                                                            class="d-flex align-items-start gallery_container_{{ $key->id }}">
                                                            <img src="{{ $key->photo }}" class="GalleryImage" alt="">
                                                            <button type="button"
                                                                class="badge badge-pill badge-danger galleryImageDeleteButton"
                                                                data-class="gallery_container_{{ $key->id }}"
                                                                data-action="{{ route('admin.services.deletePhoto', [$key->id]) }}">
                                                                <i class="feather icon-trash"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-12 pt-2">
                                                    <h5>
                                                        توضیحات
                                                    </h5>
                                                    <div class="form-group">
                                                        <textarea id="description" class="form-control" rows="2" name="description">{{ $shop->description ?? '' }}</textarea>
                                                        <span class="text-danger">
                                                            @error('location')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-12">
                                                <button type="button" id="submit_button"
                                                    data-end-date="{{ $shop->enddate }}"
                                                    class="btn btn-primary mr-1 mb-1 waves-effect waves-light">
                                                    تأیید نهایی
                                                </button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                    </section>
                @else
                    <section id="main-card" class="card">
                        <div class="card-header m-3 ">
                            <h3 class="text-danger">فروشگاهی برای شما ایجاد نشده است</h3>
                        </div>
                    </section>
                @endisset

                <!-- Description -->


            </div>
        </div>
    </div>


    {{-- styling for modals --}}
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
    <script>
        var url = '{{ route('admin.user.searchUser') }}';
    </script>
    {{-- <script src="{{ asset('back/assets/js/pages/cooperationSales/create.js') }}?v=51"></script> --}}
    <script src="{{ asset('back/assets/js/pages/cooperationSales/more.js') }}?v=51"></script>
@endpush
