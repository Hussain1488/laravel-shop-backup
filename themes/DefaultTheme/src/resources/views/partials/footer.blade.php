<!-- Start footer -->
<footer class="main-footer dt-sl position-relative">
    <input type="hidden" value="{{ $dailyCodeSite }}" id="dailyCodeSite">

    <div class="back-to-top">
        <a href="#"><span class="icon"><i class="mdi mdi-chevron-up"></i></span>
            <span>{{ trans('front::messages.index.back-to-top') }}</span></a>
    </div>
    <div class="container main-container">


        <div class="footer-widgets">
            <div class="row">
                @foreach ($footer_links as $group)
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="widget-menu widget card">
                            <header class="card-header">
                                <h3 class="card-title">{{ option('link_groups_' . $group['key'], $group['name']) }}</h3>
                            </header>
                            <ul class="footer-menu">
                                @foreach ($links->where('link_group_id', $group['key']) as $link)
                                    <li>
                                        <a href="{{ $link->link }}">{{ $link->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach

                <div class="col-12 col-md-6 col-lg-3">
                    <h5>نماد اعتماد</h5>
                    <a referrerpolicy='origin' target='_blank'
                        href='https://trustseal.enamad.ir/?id=415093&Code=H0hGLabjUsZEVske1xtadMMpzsSTyT4N'><img
                            referrerpolicy='origin'
                            src='https://trustseal.enamad.ir/logo.aspx?id=415093&Code=H0hGLabjUsZEVske1xtadMMpzsSTyT4N'
                            alt='' style='cursor:pointer' Code='H0hGLabjUsZEVske1xtadMMpzsSTyT4N'></a>

                    {{-- <div class="symbol footer-logo">

                        @if (option('info_enamad'))
                            {!! option('info_enamad') !!}
                        @endif

                        @if (option('info_samandehi'))
                            {!! option('info_samandehi') !!}
                        @endif

                    </div>
                    <div class="socials">
                        <div class="footer-social">
                            <ul class="text-center">

                                <li class="d-flex justify-content-start">
                                    <a referrerpolicy='origin' target='_blank'
                                        href='https://trustseal.enamad.ir/?id=415093&Code=H0hGLabjUsZEVske1xtadMMpzsSTyT4N'><img
                                            style="width: 200px" referrerpolicy='origin'
                                            src='{{ asset('back/assets/eNamad.jpg') }}' alt=''
                                            style='cursor:pointer' Code='H0hGLabjUsZEVske1xtadMMpzsSTyT4N'></a>
                                </li>
                                @if (option('social_instagram'))
                                    <li><a href="{{ option('social_instagram') }}"><i class="mdi mdi-instagram"></i></a>
                                    </li>
                                @endif

                                @if (option('social_whatsapp'))
                                    <li><a href="{{ option('social_whatsapp') }}"><i class="mdi mdi-whatsapp"></i></a>
                                    </li>
                                @endif

                                @if (option('social_telegram'))
                                    <li><a href="{{ option('social_telegram') }}"><i class="mdi mdi-telegram"></i></a>
                                    </li>
                                @endif

                                @if (option('social_facebook'))
                                    <li><a href="{{ option('social_facebook') }}"><i class="mdi mdi-facebook"></i></a>
                                    </li>
                                @endif

                                @if (option('social_twitter'))
                                    <li><a href="{{ option('social_twitter') }}"><i class="mdi mdi-twitter"></i></a>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>

    </div>
    <div class="copyright">
        <div class="container main-container">
            <p class="text-center">{{ option('info_footer_text') }}</p>
        </div>
    </div>
</footer>
<!-- End footer -->
