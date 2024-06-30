@extends('front::layouts.master', ['title' => $post->meta_title ?: $post->title])

@push('meta')
    <meta property="og:title" content="{{ $post->meta_title ?: $post->title }}" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{ route('front.posts.show', ['post' => $post]) }}" />
    <meta name="description" content="{{ $post->meta_description ?: $post->short_description }}">
    <meta name="keywords" content="{{ $post->getTags }}">
    <link rel="canonical" href="{{ route('front.posts.show', ['post' => $post]) }}" />

    @if ($post->image)
        <meta property="og:image" content="{{ asset($post->image) }}">
    @endif

@endpush

@section('content')
    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">

            <div class="row">
                <div class="col-12">
                    <!-- Start title - breadcrumb -->
                    <div class="title-breadcrumb-special dt-sl">
                        <div class="breadcrumb dt-sl">
                            <nav>
                                <a href="/">{{ trans('front::messages.posts.home') }}</a>
                                <a href="{{ route('front.posts.index') }}">{{ trans('front::messages.posts.blog') }}</a>
                                <a href="#">{{ $post->title }}</a>
                            </nav>
                        </div>
                        <div class="title-page dt-sl pb-3">
                            <h1>{{ $post->title }}</h1>
                        </div>

                    </div>
                    <!-- End title - breadcrumb -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-9 col-md-8 col-sm-12 col-12 mb-3">
                    <div class="content-page">
                        <div class="content-desc dt-sn dt-sl">
                            <header class="entry-header dt-sl mb-3">
                                <div class="post-meta date">
                                    <i
                                        class="mdi mdi-calendar-month"></i>{{ jdate($post->created_at)->format('%d %B %Y') }}
                                </div>

                                @if ($post->category)
                                    <div class="post-meta category">
                                        <i class="mdi mdi-folder"></i>

                                        <a
                                            href="{{ route('front.posts.category', ['category' => $post->category]) }}">{{ $post->category->title }}</a>
                                    </div>
                                @endif
                                <div class="post-meta category">
                                    <i class="mdi mdi-eye"></i>
                                    {{ $post->view }} {{ trans('front::messages.posts.visit') }}
                                </div>
                            </header>


                            @if ($post->image)
                                <div class="post-thumbnail dt-sl mb-4">
                                    <img class="w-100" data-src="{{ $post->image }}" alt="{{ $post->title }}">
                                </div>
                            @endif


                            <div class="col-12 mt-4">
                                {!! $post->content !!}

                            </div>

                            @if ($post->video)
                                <div class="m-4 text-center">
                                    <div class="col-12 col-lg-10 col-xl-10 video-container">
                                        <div class="post-aparat-video">
                                            {!! aparat_iframe($post->video) !!}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @isset($participants)
                                @foreach ($participants as $participantIndex => $participant_obj)
                                    @foreach ($participant_obj->participantPost as $postIndex => $state)
                                        <div id="state-{{ $state->id }}" class="position-relative my-3 py-3">
                                            <div class="bg-info d-inline-block px-2 justify-content-center align-items-center rounded-lg position-absolute"
                                                style="z-index: 100; top:20px; right:10px;">
                                                <p class="p-1 m-1 text-light">شرکت کننده : <span
                                                        class="text-light">{{ $state->username }}</span></p>
                                            </div>
                                            <div class="swiper mySwiper ">
                                                <div class="swiper-wrapper d-flex align-items-center bg-dark">
                                                    @php
                                                        $photos = $state ? explode(',', $state->photo) : [];

                                                    @endphp
                                                    @if ($state->video)
                                                        <div class="swiper-slide">
                                                            <div class="post-aparat-video">
                                                                {!! aparat_iframe($state->video) !!}
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @foreach ($photos as $key => $photo)
                                                        <div class="swiper-slide">
                                                            <img src="{{ $photo }}" alt="">
                                                        </div>
                                                    @endforeach

                                                </div>
                                                <div class="swiper-pagination"></div>
                                            </div>
                                            <div class="post-footer-option text-sm">
                                                <ul class="list-unstyled d-flex mb-0 justify-content-between">

                                                    <!-- Thumbs up -->

                                                    <div class="d-flex">
                                                        @auth
                                                            <div class="like grow like-{{ $state->id }} @auth {{ $state->like }} @endauth mr-1 "
                                                                data-class="active-like" data-type='like'
                                                                data-unique="like-{{ $state->id }}"
                                                                action="{{ route('front.participant.like', [$state->id]) }}">
                                                                <i class="mdi mdi-thumb-up mdi-24px" aria-hidden="true"></i>
                                                                <span class="like_count like-{{ $state->id }}">
                                                                    {{ $state->like_count }}
                                                                </span>
                                                            </div>
                                                            <div class="dislike grow like-{{ $state->id }} @auth {{ $state->dislike }} @endauth"
                                                                data-class="active-dislike" data-unique="like-{{ $state->id }}"
                                                                data-type='dislike'
                                                                action="{{ route('front.participant.like', [$state->id]) }}">
                                                                <i class="mdi mdi-thumb-down mdi-24px" aria-hidden="true"></i>
                                                                <span class="like_count dislike-{{ $state->id }}">
                                                                    {{ $state->dislike_count }}
                                                                </span>
                                                            </div>
                                                        @else
                                                            <div class="mr-2">

                                                                <a class="text-dark"
                                                                    href="{{ route('login', ['redirect', url()->current()]) }}">
                                                                    <div class="grow  ">
                                                                        <i class="mdi mdi-thumb-up mdi-24px" aria-hidden="true"></i>
                                                                        <span class="like_count like-{{ $state->id }}">
                                                                            {{ $state->like_count }}
                                                                        </span>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                            <div class="mr-1">

                                                                <a class="text-dark"
                                                                    href="{{ route('login', ['redirect', url()->current()]) }}">
                                                                    <div class="grow mr-2 ">
                                                                        <i class="mdi mdi-thumb-down mdi-24px"
                                                                            aria-hidden="true"></i>
                                                                        <span class="like_count dislike-{{ $state->id }}">
                                                                            {{ $state->dislike_count }}
                                                                        </span>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        @endauth
                                                    </div>

                                                    <div class="d-flex ml-2">
                                                        <div class=" mobile-social-share">
                                                            <div id="socialHolder" class="col-md-1">
                                                                <div id="socialShare" class="btn-group share-group">
                                                                    <a data-toggle="dropdown" class="btn p-0">
                                                                        <i class="mdi mdi-share-variant mdi-24px"></i>
                                                                    </a>

                                                                    <ul class="dropdown-menu">

                                                                        <li>
                                                                            <a href="https://eitaa.com/share/url?url={{ url('posts/' . $post->slug . '/?state-' . $state->id) }}"
                                                                                data-original-title="LinkedIn" rel="tooltip"
                                                                                href="#" class="btn btn-linkedin"
                                                                                data-placement="left">
                                                                                <img class="w-100"
                                                                                    src="{{ theme_asset('img/eitaa.svg') }}"
                                                                                    alt="">
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="https://api.whatsapp.com/send?text={{ url('posts/' . $post->slug . '/?state-' . $state->id) }}"
                                                                                data-original-title="Pinterest" rel="tooltip"
                                                                                class="btn btn-pinterest"
                                                                                data-placement="left">
                                                                                <i class="mdi mdi-whatsapp mdi-24px"></i>
                                                                            </a>

                                                                        </li>
                                                                        <li>
                                                                            <a href="https://telegram.me/share/url?url={{ url('posts/' . $post->slug . '/?state-' . $state->id) }}"
                                                                                target="_blank"
                                                                                data-original-title="Pinterest" rel="tooltip"
                                                                                class="btn btn-telegram"
                                                                                data-placement="left">
                                                                                <i class="mdi mdi-telegram mdi-24px"></i>
                                                                            </a>
                                                                        </li>

                                                                    </ul>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div>

                                                        </div>
                                                    </div>
                                                </ul>
                                                @php
                                                    $truncatedComment2 = Str::limit($state->comment, 10); // Adjust the character limit as needed
                                                @endphp
                                                @php
                                                    $truncatedComment = Str::limit($state->caption, 30); // Adjust the character limit as needed
                                                @endphp

                                                <p class="mr-3">

                                                    <span class="truncated">{{ $truncatedComment }}</span>
                                                    @if (strlen($state->caption) > 30)
                                                        <span class="full-comment d-none">{{ $state->caption }}</span>
                                                        <a href="javascript:void(0);" class="show-more">بیشتر</a>
                                                    @endif
                                                </p>
                                                <p class="mr-3" style="font-size: 13px">
                                                    <span class="truncated">{{ $truncatedComment2 }}</span>
                                                    <a class="text-dark state-comments-button"
                                                        action="{{ route('front.participant.comments', [$state->id]) }}"
                                                        data-id="{{ $state->id }}" style="margin-right: 10px;"><i
                                                            class="mdi mdi-comment-text mdi-24px"></i><span class="">
                                                            {{ $state->comments_count }} نظر
                                                        </span></a>
                                                    @if (strlen($state->comment) > 10)
                                                        <span class="full-comment d-none">{{ $state->comment }}</span>
                                                        <a href="javascript:void(0);" class="show-more">بیشتر</a>
                                                    @endif
                                                </p>
                                                {{-- <p class="mr-3">{{ $state->comment }}</p> --}}

                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            @endisset


                        </div>

                        <div class="dt-sl mt-3">
                            @include('front::components.comments', [
                                'model' => $post,
                                'route_link' => route('front.post.comments', ['post' => $post]),
                            ])
                        </div>
                    </div>

                </div>
                @include('front::posts.partials.sidebar')
                @include('front::partials.modal-comments')

            </div>
        </div>
    </main>
    <!-- End main-content -->
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ theme_asset('css/post.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endpush

@push('scripts')
    <script src="{{ theme_asset('js/pages/comments.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endpush
