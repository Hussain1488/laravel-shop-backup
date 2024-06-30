@extends('front::user.layouts.master')

@section('user-content')
    <!-- Start Content -->
    <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12">

        @if ($fittings->count())
            <div class="row">
                <div class="col-12">
                    <div class="section-title text-sm-title title-wide no-after-title-wide dt-sl mb-2 px-res-1">
                        <h2>{{ trans('front::messages.profile.all-orders') }}</h2>
                    </div>
                    <div class="dt-sl">
                        <div class="table-responsive">
                            <div class="m-2 d-flex justify-content-end">
                                <input type="image" data-action="{{ route('front.participant.participate') }}"
                                    src="{{ theme_asset('img/match.png') }}"
                                    class="btn btn-secondary match-participation w-3" value="اشتراک در مسابقه">

                            </div>
                            <div class="col-12">
                                <div class="d-flex flex-wrap">
                                    @foreach ($fittings as $key)
                                        @include('front::partials.fitting-product')
                                    @endforeach
                                </div>

                            </div>


                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="page dt-sl dt-sn pt-3">
                        <p>{{ trans('front::messages.profile.there-nothing-show') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-3">
            {{-- {{ $orders->links('front::components.paginate') }} --}}
        </div>

        <div class="modal fade text-left" id="match-participation-modal" tabindex="-1" role="dialog"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg match-participation-modal-content"
                role="document">

            </div>
        </div>
    </div>
    {{-- @include('front:partials.match-modal') --}}

    <!-- End Content -->
@endsection
@include('back.partials.plugins', [
    'plugins' => [
        'ckeditor',
        'dropzone',
        'jquery-tagsinput',
        'jquery.validate',
        'jquery-ui',
        'jquery-ui-sortable',
    ],
])
@push('styles')
    <link rel="stylesheet" href="{{ theme_asset('css/fitting.css') }}">
    {{-- <link rel="stylesheet" href="{{ theme_asset('css/post.css') }}"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endpush
@push('scripts')
    <script src="{{ theme_asset('js/pages/fitting/show.js') }}?v=51"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endpush
