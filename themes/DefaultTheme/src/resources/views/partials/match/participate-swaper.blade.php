<div id="" class="position-relative my-3 py-3">
    <div class="bg-info d-inline-block pl-2 mt-4 justify-content-center align-items-center rounded-lg position-absolute"
        style="z-index: 100; top:10px; right:10px;">
        <p class="p-1 m-1 text-light">شرکت کننده : <span class="text-light">{{ $data['username'] }}</span></p>
    </div>
    <div class="swiper mySwiper ">
        <div class="swiper-wrapper d-flex align-items-center bg-dark">

            {{-- @dump($images); --}}
            @php
                $images = explode(',', $data['images']);
                array_shift($images);
            @endphp
            @if ($data['photos'])

                @foreach ($data['photos'] as $key => $photo)
                    <div class="swiper-slide">
                        <img class="w-100" src="{{ asset($photo) }}" alt="">
                    </div>
                @endforeach
            @endif
            @foreach ($images as $key => $photo)
                <div class="swiper-slide">
                    <img class="w-100" src="{{ asset('uploads/tmp/' . $photo) }}" alt="">

                </div>
            @endforeach

        </div>
        <div class="swiper-pagination"></div>
        {{-- @dump($images)
        @dump($photos) --}}
    </div>
    <div class="post-footer-option text-sm">
        <ul class="list-unstyled d-flex mb-0 justify-content-between">

            <!-- Thumbs up -->

            <div class="d-flex">

                <div class="mr-2">

                    <a class="text-dark" href="#">
                        <div class="grow  ">
                            <i class="mdi mdi-thumb-up mdi-24px" aria-hidden="true"></i>
                            <span class="like_count like">
                                3
                            </span>
                        </div>
                    </a>
                </div>
                <div class="mr-1">

                    <a class="text-dark" href="#">
                        <div class="grow mr-2 ">
                            <i class="mdi mdi-thumb-down mdi-24px" aria-hidden="true"></i>
                            <span class="like_count dislike">
                                1
                            </span>
                        </div>
                    </a>
                </div>
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
                                    <a href="#" data-original-title="LinkedIn" rel="tooltip" href="#"
                                        class="btn btn-linkedin" data-placement="left">
                                        <img class="w-100" src="{{ theme_asset('img/eitaa.svg') }}" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a href="#" data-original-title="Pinterest" rel="tooltip"
                                        class="btn btn-pinterest" data-placement="left">
                                        <i class="mdi mdi-whatsapp mdi-24px"></i>
                                    </a>

                                </li>
                                <li>
                                    <a href="#" target="_blank" data-original-title="Pinterest" rel="tooltip"
                                        class="btn btn-telegram" data-placement="left">
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
            $truncatedComment = Str::limit($data['caption'], 30); // Adjust the character limit as needed
        @endphp

        <p class="mr-3">

            <span class="truncated">{{ $truncatedComment }}</span>
            @if (strlen($data['caption']) > 30)
                <span class="full-comment d-none">{{ $data['caption'] }}</span>
                <a href="javascript:void(0);" class="show-more">بیشتر</a>
            @endif
        </p>

        {{-- <p class="mr-3">{{ $data->comment }}</p> --}}

    </div>
</div>
