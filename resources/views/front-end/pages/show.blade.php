@extends(file_exists(resource_path('views/extend/front-end/master.blade.php')) ?
 'extend.front-end.master': 'front-end.master', ['body_class' => 'dc-innerbgcolor'] )
 @push('PackageStyle')
    <link href="{{ asset('css/dd.css') }}" rel="stylesheet">
@endpush
@push('stylesheets')
    <link href="{{ asset('css/prettyPhoto.css') }}" rel="stylesheet">
@endpush
@push('front_end_stylesheets')
    <link href="{{ asset('css/owl.carousel.min.css') }}" rel="stylesheet">
@endpush
@section('title')
@if (!empty($meta_title))
    {{ clean($meta_title) }} 
@elseif (empty(Request::segment(1)))
    {{ env('APP_NAME') }} 
@else 
    {{ clean($page->title) }}   
@endif
@stop
@section('description', clean("$meta_desc"))
@section('content')
@include('includes.pre-loader')
    @if (!empty(Request::segment(1)))
        {!! Helper::displayBreadcrumbs('showPage', $page) !!}
    @endif
    @if (!empty($page))
        @if ($home == true)
            <div class="dc-contentwrappers" id="pages">
                @if (!($page->body) || $page->body != 'null')
                    <div class="container">
                        <div class="at-description">
                            @php echo htmlspecialchars_decode(stripslashes($page->body)); @endphp
                        </div>
                    </div>
                @else
                <show-page :page_id="'{{($page->id)}}'" :selected_search_form_type="'{{$selected_search_form_type}}'"></show-page>
                @endif
            </div>
        @elseif ($home == false)  
            <div class="dc-haslayout dc-main-section" id="pages">
                <div class="dc-preloader-section" v-if="loading" v-cloak>
                    <div class="dc-preloader-holder">
                        <div class="dc-loader"></div>
                    </div>
                </div>
                <div class="container">
                    @if ($show_sidebar == true) 
                        <div class="row">
						    <div id="dc-twocolumns" class="dc-twocolumns dc-haslayout">
							    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 col-xl-9 float-left">
                                    @if (!($page->body) || $page->body != 'null')
                                        <div class="at-description">
                                            @php echo htmlspecialchars_decode(stripslashes($page->body)); @endphp
                                        </div>
                                    @else
                                        <show-page :page_id="'{{($page->id)}}'" :selected_search_form_type="'{{$selected_search_form_type}}'"></show-page>
                                    @endif
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 col-xl-3 float-{{$sidebarPosition}}">
                                    @include('front-end.sidebar.index')
                                </div>
                            </div>
                        </div>
                    @else
                        @if (!($page->body) || $page->body != 'null')
                            <div class="at-description">
                                @php echo htmlspecialchars_decode(stripslashes($page->body)); @endphp
                            </div>
                        @else
                            <show-page :page_id="'{{($page->id)}}'" :selected_search_form_type="'{{$selected_search_form_type}}'"></show-page>
                        @endif
                    @endif
                </div>
            </div>
        @endif
    @else
        @if (file_exists(resource_path('views/extend/errors/404.blade.php')))
            @include('extend.errors.404')
        @else
            @include('errors.404')
        @endif
    @endif
@endsection
@push('front_end_scripts')
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
@endpush
@push('scripts')
    <script src="{{ asset('js/prettyPhoto.js') }}"></script>
    <script src="{{ asset('js/jquery.dd.min.js') }}"></script>
    <script>
        jQuery("a[data-rel]").each(function () {
            jQuery(this).attr("rel", jQuery(this).data("rel"));
        });
        jQuery("a[data-rel^='prettyPhoto']").prettyPhoto({
            animation_speed: 'normal',
            theme: 'dark_square',
            slideshow: 3000,
            autoplay_slideshow: false,
            social_tools: false
        });
    </script>
@endpush
