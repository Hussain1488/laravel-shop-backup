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
                                    <li class="breadcrumb-item active">لیست نوشته ها
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">

                @if ($posts->count())
                    <section class="card">
                        <div class="card-header">
                            <h4 class="card-title">لیست نوشته ها</h4>
                        </div>
                        <div class="card-content" id="main-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center">تصویر شاخص</th>
                                                <th>عنوان</th>
                                                <th class="text-center">پست</th>
                                                <th class="text-center">عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($posts as $post)
                                                <tr id="post-{{ $post->id }}-tr">
                                                    <td class="text-center">
                                                        <img class="post-thumb"
                                                            src="{{ $post->image ? asset($post->image) : asset('/empty.jpg') }}"
                                                            alt="image">
                                                    </td>
                                                    <td><span class="d-inline-block">{{ $post->title }}</span> <a
                                                            href="{{ Route::has('front.posts.show') ? route('front.posts.show', ['post' => $post]) : '' }}"
                                                            target="_blank"><i class="feather icon-external-link"></i></a>
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($post->published)
                                                            <div class="badge badge-pill badge-success badge-md">منتشر شده
                                                            </div>
                                                        @else
                                                            <div class="badge badge-pill badge-danger badge-md">پیش نویس
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($post->type == 'match')
                                                            <button class="add-participant btn btn-info"
                                                                data-id="{{ $post->id }}"
                                                                data-name="{{ $post->title }}">
                                                                ثبت شرکت کننده
                                                            </button>
                                                        @endif
                                                        @can('posts.update')
                                                            <a href="{{ route('admin.posts.edit', ['post' => $post]) }}"
                                                                class="btn btn-success mr-1 waves-effect waves-light">ویرایش</a>
                                                        @endcan

                                                        @can('posts.delete')
                                                            <button type="button" data-post="{{ $post->slug }}"
                                                                data-id="{{ $post->id }}"
                                                                class="btn btn-danger mr-1 waves-effect waves-light btn-delete"
                                                                data-toggle="modal" data-target="#delete-modal">حذف</button>
                                                        @endcan

                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                @else
                    <section class="card">
                        <div class="card-header">
                            <h4 class="card-title">لیست نوشته ها</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="card-text">
                                    <p>چیزی برای نمایش وجود ندارد!</p>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif
                {{ $posts->links() }}

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
                    با حذف نوشته دیگر قادر به بازیابی آن نخواهید بود
                </div>
                <div class="modal-footer">
                    <form action="#" id="post-delete-form">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-success waves-effect waves-light"
                            data-dismiss="modal">خیر</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">بله حذف شود</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade text-left" id="participant-state-add-modal" tabindex="-1" role="dialog" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel19">شما درحال ساخت پست جدید برای شرکت کننده هستید!</h4>
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
                                    <label>تصاویر محصول ( <small>بهترین اندازه <span
                                                class="text-danger">{{ config('front.imageSizes.productGalleryImage') }}</span>
                                            پیکسل میباشد.</small> )</label>

                                    <div class="dropzone dropzone-area participant-images" id="participant-images">

                                        <div class="dz-message h-auto">تصاویر را به اینجا بکشید
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-warning waves-effect waves-light"
                            data-dismiss="modal">انصراف</button>
                        <button type="submit" class="btn btn-success waves-effect waves-light">ثبت</button>
                        <button type="button" data-id=""
                            class="btn btn-outline-success waves-effect waves-light submit-state-add-state">ثبت و افزودن
                            پست</button>
                    </form>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade text-left" id="participant-add-modal" tabindex="-1" role="dialog" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>
                        افزودن شرکت کننده برای پست:
                    </h4>
                    <div class="col col-6 post-name">
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>

                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.participant.store') }}" method="post" id="add-participant-form">
                        @csrf
                        <input type="hidden" name="post_id" value="" class="post-id">
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-6">
                                <div class="m-1">
                                    اسم کامل:
                                </div>
                            </div>
                            <div class="col-12 col-md-12 col-lg-6">
                                <div class="m-1">
                                    <input type="text" name="name" class="form-control" placeholder="اسم کامل">
                                </div>
                            </div>
                            <div class="col-12 col-md-12 col-lg-6">
                                <div class="m-1">
                                    اسم کاربری:
                                </div>
                            </div>
                            <div class="col-12 col-md-12 col-lg-6">
                                <div class="m-1">
                                    <input type="text" name="username" class="form-control" placeholder="اسم کاربری">
                                </div>
                            </div>
                            <div class="col-12 col-md-12 col-lg-6">
                                <div class="m-1">
                                    شماره تماس:
                                </div>
                            </div>
                            <div class="col-12 col-md-12 col-lg-6">
                                <div class="m-1">
                                    <input type="number" name="phone" class="form-control" placeholder="شماره تماس">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-warning waves-effect waves-light"
                            data-dismiss="modal">انصراف</button>
                        <button type="submit" class="btn btn-success waves-effect waves-light">ثبت</button>
                        <button type="button"
                            class="btn btn-outline-success waves-effect waves-light submit-add-state">ثبت و
                            افزدن
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
    <script src="{{ asset('back/assets/js/pages/posts/index.js') }}"></script>
@endpush
