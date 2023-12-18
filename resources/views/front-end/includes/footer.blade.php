@section('footer')
    <footer id="dc-footer" class="dc-footer dc-haslayout">
        @if (Helper::getFooterSettings('show_contact_info_sec') === 'true')
            @if(!empty(Helper::getFooterSettings('contact_info_img_one')) || !empty(Helper::getFooterSettings('contact_info_img_two')))
                <div class="dc-footertopbar">
                    <div class="container">
                        <div class="row justify-content-center align-self-center">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 push-lg-2">
                                <div class="dc-footer-call-email">
                                    <div class="dc-callinfoholder">
                                        @if (!empty(Helper::getFooterSettings('contact_info_img_one')))
                                            <figure class="dc-callinfoimg">
                                                <img src="{{ asset(Helper::getImage('uploads/settings/general/footer', Helper::getFooterSettings('contact_info_img_one'), 'small-')) }}" alt="{{ trans('lang.img_desc') }}">
                                            </figure>
                                        @endif
                                        <div class="dc-callinfocontent">
                                            <h3>
                                                <span>{{ html_entity_decode(clean(Helper::getFooterSettings('contact_info_title_one'))) }}</span> 
                                                <a href="tel:{{ clean(Helper::getFooterSettings('contact_info_number')) }}">{{ clean(Helper::getFooterSettings('contact_info_number')) }}</a>
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="dc-callinfoholder dc-mailinfoholder">
                                        @if (!empty(Helper::getFooterSettings('contact_info_img_two')))
                                            <figure class="dc-callinfoimg">
                                                <img src="{{ asset(Helper::getImage('uploads/settings/general/footer', Helper::getFooterSettings('contact_info_img_two'), 'small-')) }}" alt="{{ trans('lang.img_desc') }}">
                                            </figure>
                                        @endif
                                        <div class="dc-callinfocontent">
                                            <h3>
                                                <span>{{ html_entity_decode(clean(Helper::getFooterSettings('contact_info_title_two'))) }}</span> 
                                                <a href="mailto:{{ clean(Helper::getFooterSettings('contact_info_email')) }}">{{ clean(Helper::getFooterSettings('contact_info_email')) }}</a>
                                            </h3>
                                        </div>
                                    </div>
                                    <span class="dc-or-text">- {{ trans('lang.or') }} -</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
        <div class="dc-fthreecolumns">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 float-left">
                        <div class="dc-fcol dc-widgetcontactus">
                            <strong class="dc-logofooter"><a href="{{ url('/') }}"><img src="{{ asset(Helper::getFooterSettings('footer_logo')) }}" alt="{{ trans('lang.site_logo') }}"></a></strong>
                            <div class="dc-footercontent">
                                <div class="dc-description">
                                    {!! clean(str_limit(Helper::getFooterSettings('footer_about_us_note'), 100)) !!}
                                </div>
                                @if (!empty(Helper::getFooterSettings('footer_address'))
                                    || !empty(Helper::getFooterSettings('footer_email'))
                                    || !empty(Helper::getFooterSettings('footer_phone')))
                                    <ul class="dc-footercontactus">
                                        <li><address><i class="lnr lnr-location"></i> {{ html_entity_decode(clean(Helper::getFooterSettings('footer_address'))) }}</address></li>
                                        <li><a href="mailto:{{ clean(Helper::getFooterSettings('footer_email')) }}"><i class="lnr lnr-envelope"></i> {{ clean(Helper::getFooterSettings('footer_email')) }}</a></li>
                                        <li>
                                            <span>
                                                <i class="lnr lnr-phone-handset"></i> 
                                                <a href="tel:{{ clean(Helper::getFooterSettings('footer_phone')) }}">{{ clean(Helper::getFooterSettings('footer_phone')) }}</a>
                                            </span>
                                        </li>
                                    </ul>
                                @endif

                                @if (Helper::getFooterSettings('show_footer_socials') === 'true')
                                    <div class="dc-fsocialicon">
                                        {{ Helper::displaySocials('footer') }}
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 float-left">
                        <div class="tg-widgettwitter dc-fcol dc-flatestad dc-twitter-live-wgdets">
                            <div class="dc-ftitle"><h3>{{ trans('lang.footer_twitter_title') }}</h3></div>				
                            <div class="dc-footercontent">
                                @include('front-end.includes.twitter')
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 float-left">
                        {{-- <div class="dc-fcol dc-newsletterholder">
                            <div class="dc-footercontent dc-newsletterholder">
                                @if ( !empty(Helper::getDownloadAppSection('show_app_sec')) && Helper::getDownloadAppSection('show_app_sec') == 'true')
                                    <div class="dc-footerapps">
                                        <div class="dc-ftitle"><h3>{{ html_entity_decode(clean(Helper::getDownloadAppSection('title'))) }}</h3></div>
                                        <ul class="dc-btnapps">
                                            <li>
                                                <a href="{{ Helper::getDownloadAppSection('android_url') }}">
                                                    <img src="{{ asset(Helper::getImage('uploads/settings/home', Helper::getDownloadAppSection('android_img'), 'small-', 'default-footer-android.png')) }}" alt="{{ trans('lang.img_desc') }}">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ Helper::getDownloadAppSection('ios_url') }}">
                                                    <img src="{{ asset(Helper::getImage('uploads/settings/home', Helper::getDownloadAppSection('ios_img'), 'small-', 'default-footer-ios.png')) }}" alt="{{ trans('lang.img_desc') }}">
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div> --}}
                        <div class="dc-fcol dc-newsletterholder">
                            <div class="dc-footercontent dc-newsletterholder">
                                <div class="dc-footerapps">
                                    <div class="dc-ftitle"><h3>{{ html_entity_decode(clean(Helper::getFooterSettings('app_sec_title'))) }}</h3></div>
                                    <ul class="dc-btnapps">
                                        @if (!empty(Helper::getFooterSettings('android_icon')))
                                            <li>
                                                <a href="{{ Helper::getFooterSettings('android_url') }}">
                                                    <img src="{{ asset(Helper::getFooterSettings('android_icon')) }}" alt="{{ trans('lang.img_desc') }}">
                                                </a>
                                            </li>
                                        @endif
                                        @if (!empty(Helper::getFooterSettings('ios_icon')))
                                            <li>
                                                <a href="{{ Helper::getFooterSettings('ios_url') }}">
                                                    <img src="{{ asset(Helper::getFooterSettings('ios_icon')) }}" alt="{{ trans('lang.img_desc') }}">
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        {{-- New code --}}
                        <div class="dc-fcol dc-newsletterholder dc-newfootermenu">
                            <div class="dc-footercontent dc-newsletterholder">
                                @if (!empty(Helper::getFooterSettings('menu_title_1')) && !empty(Helper::getFooterSettings('menu_pages_1')))
                                    <div class="dc-footerapps">
                                        <div class="dc-ftitle"><h3>{{ Helper::getFooterSettings('menu_title_1') }}</h3></div>
                                        @if(!empty(Helper::getFooterSettings('menu_pages_1')))
                                            <ul class="dc-btnapps">
                                                @foreach(Helper::getFooterSettings('menu_pages_1') as $menu_1_page)
                                                    @php  $page = \App\Page::where('id', $menu_1_page)->first(); @endphp
                                                    @if (!empty($page))
                                                        <li>
                                                            <a href="{{{ url('page/'.$page->slug) }}}">
                                                                {{{ $page->title }}}
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        {{-- New code end --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="dc-footerbottom">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <p class="dc-copyright">{{ html_entity_decode(clean(Helper::getFooterSettings('footer_copyright'))) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
@endsection

