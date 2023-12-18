@extends('front-end.master', ['body_class' => 'dc-home dc-userlogin'])
@if (Schema::hasTable('site_managements')) 
    @php 
        $seo_settings = App\SiteManagement::getMetaValue('seo_settings'); 
        $meta_title = !empty($seo_settings['meta_title']) ? $seo_settings['meta_title'] : 'Doctory - Lawyers &  Directory Laravel Theme';
        $meta_desc = !empty($seo_settings['meta_desc']) ? $seo_settings['meta_desc'] : '';
    @endphp
    @section('title'){{ $meta_title }} @stop
    @section('description', "$meta_desc")
@endif
@section('banner')
    @if (!empty(Helper::getHomeSlider('home_slides')))
        @include('front-end.slider')
    @endif
@endsection
@push('front_end_stylesheets')
    <link href="{{ asset('css/owl.carousel.min.css') }}" rel="stylesheet">
@endpush
@section('content')
@include('includes.pre-loader')
    <div id="home" class="old-home">
        @if (Session::has('error'))
            <div class="flash_msg">
                <flash_messages :message_class="'danger'" :time='10' :message="'{{{ Session::get('error') }}}'" v-cloak>
                </flash_messages>
            </div>
        @endif
        <section class="dc-searchholder dc-haslayout">
            @if (Helper::getSearchBanner('show_banner') === 'true')
                @php 
                    $locations = App\Location::all(); 
                    $roles     = Spatie\Permission\Models\Role::all()->toArray();
                @endphp
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="dc-searchform-holder">
                                <div class="dc-advancedsearch">
                                    <div class="dc-title">
                                        <h2>{{ html_entity_decode(clean(Helper::getSearchBanner('form_title'))) }}</h2>
                                        <a href="javascript:void(0);" class="dc-docsearch" v-on:click="displayFilfer">
                                            <span class="dc-advanceicon"><i></i> <i></i> <i></i></span>
                                            <span>{{ trans('lang.advanced') }} <br>{{ trans('lang.search') }}</span>
                                        </a>
                                    </div>
                                    {!! Form::open(['url' => url('search-results'), 'method' => 'get', 'id' =>'search_form', 'class' => 'dc-formtheme dc-form-advancedsearch']) !!}
                                        <fieldset>
                                            <div class="form-group">
                                                <input type="text" name="search" value="" class="form-control" placeholder="{{ trans('lang.ph.hospitals_clinic_etc') }}">
                                            </div>
                                            <div class="form-group">
                                                <div class="dc-select">
                                                    <select class="chosen-select locations" data-placeholder="{{ clean(trans('lang.select_country')) }}" name="locations">
                                                        <option value="">{{ clean(trans('lang.select_country')) }}</option>
                                                        @php Helper::displaySearchLocationList(); @endphp
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="dc-formbtn">
                                                {{ Form::button('<i class="ti-arrow-right"></i>', ['type' => 'submit', 'class' => 'btn-sm'] )  }}
                                            </div>
                                        </fieldset>
                                        <fieldset style="display: none;" class="dc-home-advancedsearch">
                                            <div class="form-group">
                                                <div class="dc-select">
                                                    <select class="chosen-select locations" name="type">
                                                        @if (!empty($roles))
                                                            <option value="both" selected>{{ trans('lang.both') }}</option>
                                                            @foreach ($roles as $key => $role)
                                                                @if (!in_array($role['role_type'] == 'admin', $roles) && !in_array($role['role_type'] == 'regular', $roles))
                                                                    @php $selected = !empty($_GET['type']) && $_GET['type'] == $role['role_type'] ? 'selected' : ''; @endphp
                                                                    <option value="{{ clean($role['role_type']) }}" {{$selected}}>{{ html_entity_decode(clean($role['name'])) }}</option>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <speciality-services 
                                            :specialities="specialities" 
                                            v-if="show_speciality"
                                            :speciality_value_type="'slug'"
                                            :service_value_type="'slug'"
                                            v-cloak >
                                            </speciality-services>
                                        </fieldset>
                                    {!! form::close(); !!}
                                </div>
                                <div class="dc-jointeamholder">
                                    <div class="dc-jointeam">
                                        <span class="dc-jointeamnoti"><i class="ti-light-bulb"></i></span>
                                        <figure class="dc-jointeamimg">
                                            <img src="{{ asset(Helper::getImage('uploads/settings/home', Helper::getSearchBanner('banner_img'), 'small-', 'img-04.png')) }}" alt="{{ trans('lang.img_desc') }}">
                                        </figure>
                                        <div class="dc-jointeamcontent">
                                            <h3><span>{{ html_entity_decode(clean(Helper::getSearchBanner('banner_subheading'))) }}</span>{{ html_entity_decode(clean(Helper::getSearchBanner('banner_heading'))) }}</h3>
                                            <a href="{{ Helper::getSearchBanner('btn_url') }}" class="dc-btn dc-btnactive">{{ html_entity_decode(clean(Helper::getSearchBanner('btn_title'))) }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (Helper::getServicesSection('show_services_section') === 'true')
                @if (!empty(Helper::getServicesSection('services_tabs')))
                    <div class="dc-haslayout">
                        <div class="container-fluid">
                            <div class="row">
                                <div id="dc-doctorslider" class="dc-doctorslider owl-carousel">
                                    @php $count = 1; @endphp
                                    @foreach (Helper::getServicesSection('services_tabs') as $key => $service_tab)
                                        @php 
                                            $color = !empty($service_tab['color']) ? html_entity_decode(clean($service_tab['color'])) : ''; 
                                            $hex = $color;
                                            list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
                                        @endphp
                                        @push('inlineStyle')
                                            <style>
                                                .dc-titlecolor{{$count}} h3 {
                                                    color: {{$color}};
                                                }
                                                .dc-titlecolor{{$count}} .dc-btn {
                                                    border-color: {{$color}};
                                                }
                                                .dc-titlecolor{{$count}}.dc-doctordetails-holder:after {
                                                    background: {{$color}};
                                                }
                                                .dc-titlecolor{{$count}} .dc-btn:hover {
                                                    background: {{$color}};
                                                    -webkit-box-shadow: 0 9px 20px 0 rgba({{$r}},{{$g}},{{$b}},0.5);
                                                    box-shadow: 0 9px 20px 0 rgba({{$r}},{{$g}},{{$b}},0.5);
                                                }
                                            </style>
                                        @endpush
                                        <div class="item dc-doctordetails-holder dc-titlecolor{{ $count }}">
                                            <span class="dc-slidercounter">{{ sprintf("%02d.", $count) }}</span>
                                            <h3><span>{{ html_entity_decode(clean($service_tab['title'])) }}</span> {{ html_entity_decode(clean($service_tab['subtitle'])) }}</h3>
                                            <a href="{{ $service_tab['btn_url'] }}" class="dc-btn">{{ html_entity_decode(clean($service_tab['btn_title'])) }}</a>
                                        </div>
                                        @php $count++; @endphp
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </section>
        <!-- Bring Care Start -->
        @if (!empty(Helper::getAboutUsSection('show_about_sec')) && Helper::getAboutUsSection('show_about_sec') === 'true')
            <section class="dc-haslayout dc-main-section dc-sectionbg">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6 align-self-center">
                            <div class="dc-bringcarecontent">
                                <div class="dc-sectionhead dc-sectionheadvtwo">
                                    <div class="dc-sectiontitle">
                                        <h2>{{ html_entity_decode(clean(Helper::getAboutUsSection('title'))) }}<span>{{ html_entity_decode(clean(Helper::getAboutUsSection('subtitle'))) }}</span></h2>
                                    </div>
                                    <div class="dc-description">
                                        {!! clean(Helper::getAboutUsSection('description')) !!}
                                    </div>
                                </div>
                                @if (!empty(Helper::getAboutUsSection('btn_one_title')) || !empty(Helper::getAboutUsSection('btn_two_title')))
                                    <div class="dc-btnarea">
                                        @if (!empty(Helper::getAboutUsSection('btn_one_title')))
                                            <a href="{{ Helper::getAboutUsSection('btn_one_url') }}" class="dc-btn">{{ html_entity_decode(clean(Helper::getAboutUsSection('btn_one_title'))) }}</a>
                                        @endif
                                        @if (!empty(Helper::getAboutUsSection('btn_one_title')))
                                            <a href="{{ Helper::getAboutUsSection('btn_two_url') }}" class="dc-btn dc-btnactive">{{ html_entity_decode(clean(Helper::getAboutUsSection('btn_two_title'))) }}</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                            <div class="dc-bringimg-holder">
                                <figure class="dc-doccareimg">
                                    @if (!empty(Helper::getAboutUsSection('about_us_img')))
                                        <img src="{{ asset(Helper::getImage('uploads/settings/home', Helper::getAboutUsSection('about_us_img'), '', 'doc-img.png')) }}" alt="{{ trans('lang.img_desc') }}">
                                    @endif
                                    @if (!empty(Helper::getAboutUsSection('img_title')) || !empty(Helper::getAboutUsSection('img_subtitle')))
                                        <figcaption>
                                            <div class="dc-doccarecontent">
                                                <h3><em>{{ html_entity_decode(clean(Helper::getAboutUsSection('img_title'))) }}</em>{{ html_entity_decode(clean(Helper::getAboutUsSection('img_subtitle'))) }}</h3>
                                            </div>
                                            <span class="dc-content-afetr"></span>
                                        </figcaption>
                                    @endif
                                </figure>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
        <!-- Bring Care End -->
        <!-- Works Section Start -->
        <section class="dc-haslayout">
            <div class="dc-haslayout dc-bgcolor dc-main-section dc-workholder">
                <div class="container">
                    @if ( !empty(Helper::getHowItWorksSection('show_how_work_sec')))
                        @if (Helper::getHowItWorksSection('show_how_work_sec') == 'true')
                            <div class="row justify-content-center align-self-center">
                                <div class="col-xs-12 col-sm-12 col-md-8 push-md-2 col-lg-8 push-lg-2">
                                    <div class="dc-sectionhead dc-text-center">
                                        <div class="dc-sectiontitle">
                                            <h2><span>{{ html_entity_decode(clean(Helper::getHowItWorksSection('subtitle'))) }}</span>{{ html_entity_decode(clean(Helper::getHowItWorksSection('title'))) }}</h2>
                                        </div>
                                        <div class="dc-description">
                                            {!! clean(Helper::getHowItWorksSection('description')) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="row justify-content-center align-self-center">
                            <div class="col-xs-12 col-sm-12 col-md-8 push-md-2 col-lg-8 push-lg-2">
                                <div class="dc-sectionhead dc-text-center">
                                    <div class="dc-sectiontitle">
                                        <h2><span>{{ html_entity_decode(clean(Helper::getHowItWorksSection('subtitle'))) }}</span>{{ html_entity_decode(clean(Helper::getHowItWorksSection('title'))) }}</h2>
                                    </div>
                                    <div class="dc-description">
                                        {!! clean(Helper::getHowItWorksSection('description')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @if (!empty(Helper::getHowItWorksSection('show_how_work_tabs')))
                @if (Helper::getHowItWorksSection('show_how_work_tabs') == 'true')
                    @if (!empty(Helper::getHowItWorksSection('how_works_tabs')))
                        <div class="dc-haslayout dc-main-section dc-workdetails-holder">
                            <div class="container">
                                <div class="row">
                                    @foreach (Helper::getHowItWorksSection('how_works_tabs') as $key => $hw_tab)
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                                            <div class="dc-workdetails">
                                                <div class="dc-workdetail">
                                                    <figure>
                                                        <img src="{{ asset(Helper::getImage('uploads/settings/home', $hw_tab['tab_img'], '', 'hw-tab-default.png')) }}" alt="{{ trans('lang.img_desc') }}">
                                                    </figure>
                                                </div>
                                                <div class="dc-title">
                                                    <span>{{ html_entity_decode(clean($hw_tab['subtitle'])) }}</span>
                                                    <h3>{{ html_entity_decode(clean($hw_tab['title'])) }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @else
                @if (!empty(Helper::getHowItWorksSection('how_works_tabs')))
                    <div class="dc-haslayout dc-main-section dc-workdetails-holder">
                        <div class="container">
                            <div class="row">
                                @foreach (Helper::getHowItWorksSection('how_works_tabs') as $key => $hw_tab)
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                                        <div class="dc-workdetails">
                                            <div class="dc-workdetail">
                                                <figure>
                                                    <img src="{{ asset(Helper::getImage('uploads/settings/home', $hw_tab['tab_img'], '', 'hw-tab-default.png')) }}" alt="{{ trans('lang.img_desc') }}">
                                                </figure>
                                            </div>
                                            <div class="dc-title">
                                                <span>{{ html_entity_decode(clean($hw_tab['subtitle'])) }}</span>
                                                <h3>{{ html_entity_decode(clean($hw_tab['title'])) }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </section>
        <!-- Works Section End -->
        <!-- Our Rated Start -->
        @if (Helper::getSpecialitySlider('display') == 'true')
            <section class="dc-haslayout dc-main-section">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-3">
                            <div class="row">
                                <div class="dc-ratedecontent dc-bgcolor">
                                    <figure class="dc-neurosurgeons-img">
                                        <img src="{{{asset(Helper::getSpecialitySlider('speciality')['image'])}}}" alt="{{ trans('lang.img_desc') }}">
                                    </figure>
                                    <div class="dc-sectionhead dc-sectionheadvtwo dc-text-center">
                                        <div class="dc-sectiontitle">
                                            <h2>{{ trans('lang.our_top_rated') }}<span>{{{ html_entity_decode(clean( Helper::getSpecialitySlider('speciality')['title'])) }}}</span></h2>
                                        </div>
                                        <div class="dc-description">
                                            <p>{{ clean(Helper::getSpecialitySlider('speciality')['description']) }}</p>
                                        </div>
                                    </div>
                                    <div class="dc-btnarea">
                                        <a href="{{{url('search-results?search=&type=doctor&speciality='.Helper::getSpecialitySlider('speciality')['slug'])}}}" class="dc-btn">{{ trans('lang.view_all') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (!empty(Helper::getSpecialitySlider('speciality')['doctors']) && count(Helper::getSpecialitySlider('speciality')['doctors']) > 0)
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-9">
                                <div class="row">
                                    <div id="dc-docpostslider" class="dc-docpostslider owl-carousel">
                                        @foreach (Helper::getSpecialitySlider('speciality')['doctors'] as $service_id)
                                            @php 
                                                $doctor = App\User::find($service_id); 
                                                $user = App\User::findOrFail($doctor->id);
                                                $saved_doctors = Auth::check() && !empty(Auth::user()->profile->saved_doctors) ? unserialize(Auth::user()->profile->saved_doctors) : array();
                                                $avg_rating = App\Feedback::where('user_id', $user->id)->pluck('avg_rating')->first();
                                                $stars  = $avg_rating != 0 ? $avg_rating / 5 * 100 : 0;
                                            @endphp 
                                            <div class="item">
                                                <div class="dc-docpostholder">
                                                    <figure class="dc-docpostimg">
                                                        <img src="{{{asset(Helper::getImage('uploads/users/'.$doctor->id,  $doctor->profile->avatar, 'medium-', 'user.jpg'))}}}" alt="{{ trans('lang.img_desc') }}">
                                                    </figure>
                                                    <div class="dc-docpostcontent">
                                                        @if (!empty($saved_doctors) && in_array($user->id, $saved_doctors))
                                                            <a href="javascrip:void(0);" class="dc-like dc-clicksave dc-btndisbaled">
                                                                <i class="fa fa-heart"></i>
                                                            </a>
                                                        @else
                                                            <a href="javascript:void(0);" class="dc-like"><i class="fa fa-heart"></i></a>
                                                            <a href="javascrip:void(0);" class="dc-like" id="doctor-{{ $user->id }}" @click.prevent="add_wishlist('doctor-{{ $user->id }}', '{{ $user->id }}', 'saved_doctors', '')" v-cloak>
                                                                <i class="fa fa-heart"></i>
                                                            </a>
                                                        @endif
                                                        <div class="dc-title">
                                                            <a href="javascript:void(0)" class="dc-docstatus">{{{ html_entity_decode(clean(Helper::getSpecialitySlider('speciality')['title'])) }}}</a>
                                                            <h3>
                                                                <a href="{{ route('userProfile', clean($doctor->slug)) }}">
                                                                    {{ !empty($doctor->profile->gender_title) ? Helper::getDoctorArray(clean($doctor->profile->gender_title)) : '' }} 
                                                                    {{{Helper::getUserName($doctor->id)}}}
                                                                </a> 
                                                                {{ Helper::verifyMedical(clean($doctor->id)) }} {{ Helper::verifyUser(clean($doctor->id)) }}
                                                            </h3>
                                                            <ul class="dc-docinfo">
                                                                <li>{{ html_entity_decode(clean($doctor->profile->tagline)) }}</li>
                                                                <li>
                                                                    <span class="dc-stars"><span style="width: {{ $stars }}%;"></span></span><em>{{ $doctor->feedbacks->count() }} {{ trans('lang.feedbacks') }}</em>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="dc-doclocation">
                                                            <span><i class="ti-direction-alt"></i> {{{!empty($doctor->location) ? html_entity_decode(clean($doctor->location->title)) : ''}}}</span>
                                                            @if (!empty($doctor->profile->available_days))
                                                                <span>
                                                                    <i class="ti-calendar"></i>
                                                                    @foreach (Helper::getAppointmentDays() as $key => $day)
                                                                        @if (!in_array($key, unserialize($doctor->profile->available_days)))
                                                                            <em class="dc-dayon">{{ html_entity_decode(clean($day['title'])) }}</em>
                                                                        @else
                                                                            {{ html_entity_decode(clean($day['title'])) }},
                                                                        @endif
                                                                    @endforeach
                                                                </span>
                                                            @endif
                                                            <a href="{{{route('userProfile', clean($doctor->slug))}}}" class="dc-btn">{{ trans('lang.view_more') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>    
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif
        <!-- Our Rated End -->
        <!-- Mobile App Start -->
        @if ( !empty(Helper::getDownloadAppSection('show_app_sec')) && Helper::getDownloadAppSection('show_app_sec') == 'true')
            <section class="dc-haslayout dc-bgcolor">
                <div class="container">
                    <div class="row">
                        @if (!empty(Helper::getDownloadAppSection('app_sec_img')))
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                <div class="dc-appbgimg">
                                    <figure>
                                        <img src="{{ asset(Helper::getImage('uploads/settings/home', Helper::getDownloadAppSection('app_sec_img'), '', 'app-default.png')) }}" alt="{{ trans('lang.img_desc') }}">
                                    </figure>
                                </div>
                            </div>
                        @endif
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 justify-content-center align-self-center">
                            <div class="dc-appcontent">
                                <div class="dc-sectionhead dc-sectionheadvtwo">
                                    <div class="dc-sectiontitle">
                                        <h2>{{ html_entity_decode(clean(Helper::getDownloadAppSection('title'))) }}<span>{{ html_entity_decode(clean(Helper::getDownloadAppSection('subtitle'))) }}</span></h2>
                                    </div>
                                    <div class="dc-description">
                                        {!! clean(Helper::getDownloadAppSection('description')) !!}
                                    </div>
                                </div>
                                <ul class="dc-appicons">
                                    @if (!empty(Helper::getDownloadAppSection('android_url')))
                                        <li>
                                            <a href="{{ Helper::getDownloadAppSection('android_url') }}">
                                                <img src="{{ asset(Helper::getImage('uploads/settings/home', Helper::getDownloadAppSection('android_img'), '', 'google-default.png')) }}" 
                                                    alt="{{ trans('lang.img_desc') }}">
                                            </a>
                                        </li>
                                    @endif
                                    @if (!empty(Helper::getDownloadAppSection('ios_url')))
                                        <li>
                                            <a href="{{ Helper::getDownloadAppSection('ios_url') }}">
                                                <img src="{{ asset(Helper::getImage('uploads/settings/home', Helper::getDownloadAppSection('ios_img'), '', 'ios-default.png')) }}" 
                                                alt="{{ trans('lang.img_desc') }}">
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
        @if (Helper::getArticleSectionSettings('show_article_sec') === 'true')
            <section class="dc-haslayout dc-main-section">
                <div class="container">
                    <div class="row justify-content-center align-self-center">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 push-lg-2">
                            <div class="dc-sectionhead dc-text-center">
                                <div class="dc-sectiontitle">
                                    <h2>
                                        <span>{{ html_entity_decode(clean(Helper::getArticleSectionSettings('section_subtitle'))) }}</span>
                                        {{ html_entity_decode(clean(Helper::getArticleSectionSettings('section_title'))) }}
                                    </h2>
                                </div>
                                <div class="dc-description">
                                    <p>{{ clean(Helper::getArticleSectionSettings('section_description')) }}</p>
                                </div>
                            </div>
                        </div>
                        @if(!empty(\App\Article::getArticles(3, true)->count() > 0) )
                            <div class="dc-articlesholder">
                                @foreach (\App\Article::getArticles(3, true) as $article)
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-4 float-left">
                                        <div class="dc-article">
                                            <figure class="dc-articleimg">
                                                <img src="{{ asset(Helper::getImage('uploads/users/'.$article->author->id.'/articles/', $article->image, 'featured-', 'featured-article-default.jpg')) }}" alt="{{ trans('lang.img_desc') }}">
                                                <figcaption>
                                                    <div class="dc-articlesdocinfo">
                                                        <img src="{{ asset(Helper::getImage('uploads/users/'.$article->author->id, App\User::find($article->author->id)->profile->avatar, 'extra-small-', 'user-login.png')) }}" alt="{{ trans('lang.img_desc') }}">
                                                        <span>{{ Helper::getUserName($article->author_id) }}</span>
                                                    </div>
                                                </figcaption>
                                            </figure>
                                            <div class="dc-articlecontent">
                                                <div class="dc-title">
                                                    <div class="dc-articleby-holder">
                                                        @if (!empty($article->categories) && $article->categories->count() > 0)
                                                            @foreach ($article->categories as $category)
                                                                <a href="{{ route('articleListing', clean($category->slug)) }}" class="dc-articleby">{{ $category->title }}</a>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <h3><a href="{{ route('articleDetail', ['slug' => clean($article->slug) ]) }}">{{ html_entity_decode(clean($article->title)) }}</a></h3>
                                                    <span class="dc-datetime"><i class="ti-calendar"></i> {{ Carbon\Carbon::parse($article->created_at)->format('M d, Y') }}</span>
                                                </div>
                                                <ul class="dc-moreoptions">
                                                    <li><a href="javascript:void(0);"><i class="ti-heart"></i></a> {{{ !empty($article->likes) ? clean($article->likes) : 0 }}} {{ trans('lang.likes') }}</li>
                                                    <li><a href="javascript:void(0);"><i class="ti-eye"></i></a>{{{ !empty($article->views) ? clean($article->views) : 0 }}} {{ trans('lang.views') }}</li>
                                                    <li id="dc-share-{{ clean($article->id) }}" @click="socialPopup('{{ clean($article->id) }}')" class="la-shareicon">
                                                        <a href="javascript:void(0);"><i class="ti-share"></i> {{ trans('lang.share') }}</a>
                                                        <ul class="dc-simplesocialicons dc-socialiconsborder">
                                                            <li class="dc-facebook">
                                                                <a href="javascript:void()" @click="socialShare('https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('articleDetail', ['slug' => clean($article->slug)])) }}')" class="social-share">
                                                                    <i class="fab fa-facebook-f"></i>
                                                                </a>
                                                            </li>
                                                            <li class="dc-twitter">
                                                                <a href="javascript:void()" @click="socialShare('https://twitter.com/intent/tweet?url={{ urlencode(route('articleDetail', ['slug' => clean($article->slug)])) }}')" class="social-share">
                                                                    <i class="fab fa-twitter"></i>
                                                                </a>
                                                            </li>
                                                            <li class="dc-linkedin">
                                                                <a href="javascript:void()" @click="socialShare('https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('articleDetail', ['slug' => clean($article->slug)])) }}')" class="social-share">
                                                                    <i class="fab fa-linkedin-in"></i></a>
                                                            </li>
                                                            <li class="dc-googleplus">
                                                                <a href="javascript:void()" @click="socialShare('https://plus.google.com/share?url={{ urlencode(route('articleDetail', ['slug' => clean($article->slug)])) }}')" class="social-share">
                                                                    <i class="fab fa-google-plus-g"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif
        <!-- Latest Articles End -->
        <section class="dc-haslayaout dc-footeraboutus dc-bgcolor">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="dc-widgetskills">
                            <div class="dc-fwidgettitle">
                                <h3>{{ html_entity_decode(clean(Helper::getFooterSettings('first_menu_title'))) }}</h3>
                            </div>
                            @if (!empty(\App\Speciality::count() > 0 ))
                                <ul class="dc-fwidgetcontent">
                                    @foreach (\App\Speciality::limit(5)->get() as $key => $speciality)
                                        <li><a href="{{{url('search-results?search=&speciality='.clean($speciality->slug))}}}">{{ html_entity_decode(clean($speciality->title)) }}</a></li>
                                    @endforeach
                                    <li class="dc-viewmore"><a href="{{{ url('search-results') }}}">{{trans('lang.view_all')}}</a></li>
                                </ul>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="dc-widgetskills">
                            <div class="dc-fwidgettitle">
                                <h3>{{ html_entity_decode(clean(Helper::getFooterSettings('second_menu_title'))) }}</h3>
                            </div>
                            @if (!empty(\App\Speciality::count() > 0 ))
                                <ul class="dc-fwidgetcontent">
                                    @foreach (\App\Speciality::limit(5)->get() as $key => $speciality)
                                        <li>
                                            <a href="{{{url('search-results?search=&speciality='.clean($speciality->slug).'&type=doctor&locations='.Helper::getFooterSettings('second_menu_location'))}}}">
                                                {{ html_entity_decode(clean($speciality->title)) }}
                                            </a>
                                        </li>
                                    @endforeach
                                    <li class="dc-viewmore">
                                        <a href="{{{ url('search-results?type=doctor&locations='.Helper::getFooterSettings('second_menu_location')) }}}">
                                            {{trans('lang.view_all')}}
                                        </a>
                                    </li>
                                </ul>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="dc-widgetskills">
                            <div class="dc-fwidgettitle">
                                <h3>{{ html_entity_decode(clean(Helper::getFooterSettings('third_menu_title'))) }}</h3>
                            </div>
                            @if (!empty(\App\Speciality::count() > 0 ))
                                <ul class="dc-fwidgetcontent">
                                    @foreach (\App\Speciality::limit(5)->get() as $key => $speciality)
                                        <li>
                                            <a href="{{{url('search-results?search=&speciality='.clean($speciality->slug).'&type=doctor&locations='.Helper::getFooterSettings('third_menu_location'))}}}">
                                                {{ html_entity_decode(clean($speciality->title)) }}
                                            </a>
                                        </li>
                                    @endforeach
                                    <li class="dc-viewmore">
                                        <a href="{{{ url('search-results?type=doctor&locations='.Helper::getFooterSettings('third_menu_location')) }}}">
                                            {{trans('lang.view_all')}}
                                        </a>
                                    </li>
                                </ul>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="dc-footercol dc-widgetcategories">
                            <div class="dc-fwidgettitle">
                                <h3>{{ html_entity_decode(clean(Helper::getFooterSettings('fourth_menu_title'))) }}</h3>
                            </div>
                            @if (!empty(\App\Location::count() > 0 ))
                                <ul class="dc-fwidgetcontent">
                                    @foreach (\App\Location::limit(5)->get() as $key => $location)
                                        <li>
                                            <a href="{{{url('search-results?search=&locations='.clean($location->slug))}}}">
                                                {{ html_entity_decode(clean($location->title)) }}
                                            </a>
                                        </li>
                                    @endforeach
                                    <li class="dc-viewmore"><a href="{{{ url('search-results') }}}">{{trans('lang.view_all')}}</a></li>
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('front_end_scripts')
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script type="text/javascript">
        // Services Section Slider
        <?php $loop = !empty(Helper::getServicesSection('services_tabs')) && count(Helper::getServicesSection('services_tabs')) > 5 ? true : false; ?>
        var _dc_doctorslider = jQuery("#dc-doctorslider")
        _dc_doctorslider.owlCarousel({
            loop:<?php echo json_encode($loop); ?>,
            margin:0,
            navSpeed:500,
            nav:false,
            autoplay: false,
            // rtl:true,
            items:5,
            responsiveClass:true,
            responsive:{
                0:{
                    items:1,
                },
                600:{
                    items:2,
                },
                800:{
                    items:3,
                },
                1080:{
                    items:4,
                },
                1280:{
                    items:5,
                },
            }
        });
    </script>
    <script>
        /* Our Rated Slider */
        var _dc_docpostslider = jQuery("#dc-docpostslider")
        _dc_docpostslider.owlCarousel({
            loop:false,
            margin:30,
            navSpeed:1000,
            nav:false,
            // rtl:true,
            items:5,
            autoplayHoverPause:true,
            autoplaySpeed:1000,
            autoplay: false,
            mouseDrag:false,
            navClass: ['dc-prev', 'dc-next'],
            navContainerClass: 'dc-docslidernav',
            navText: ['<span class="ti-arrow-left"></span>', '<span class="ti-arrow-right"></span>'],
            responsiveClass:true,
            responsive:{
                0:{
                    items:1,
                },
                480:{
                    items:2,
                },
                801:{
                    items:3,
                },
                992:{
                    items:2,
                },
                1200:{
                    items:3,
                },
                1367:{
                    items:4,
                },
                1681:{
                    items:5,
                }
            }
        });
    </script>
@endpush
