@if (!(Schema::hasTable('site_managements'))) 
    @php 
        echo trans('lang.table_missing'); 
        die; 
    @endphp
@else
    @php 
        $registration = 'true';
        $page_header = '';
        if (Schema::hasTable('site_managements')) {
            $settings = !empty(\App\SiteManagement::getMetaValue('general_settings')) ? \App\SiteManagement::getMetaValue('general_settings') : array();
            $registration = !empty($settings) && !empty($settings['display_registration']) ? $settings['display_registration'] : 'true';
        }
        $page_id='';
        $default_header_styling = \App\SiteManagement::getMetaValue('menu_settings');
        $default_menu_color = !empty($default_header_styling) && !empty($default_header_styling['menu_color']) ? $default_header_styling['menu_color'] : '';
        $default_menu_hover_color = !empty($default_header_styling) && !empty($default_header_styling['menu_hover_color']) ? $default_header_styling['menu_hover_color'] : '';
        $default_color = !empty($default_header_styling) && !empty($default_header_styling['color']) ? $default_header_styling['color'] : '';
        if (!empty(Route::getCurrentRoute()) && Route::getCurrentRoute()->uri() != '/' && Route::getCurrentRoute()->uri() != 'home') {
            if (Request::segment(1) == 'page') {
                $selected_page_data = Helper::getPageData(Request::segment(2));
                $selected_page = !empty($selected_page_data) ? APP\Page::find($selected_page_data->id) : '';
                $page_id = !empty($selected_page) ? $selected_page->id : '';
                $page_meta = !empty($page->meta) ? unserialize($page->meta) : '';
                $page_header = Helper::getPageHeader($page_id);
                $page_header_styling = !empty($page_meta) && !empty($page_meta['headerStyling']) ? $page_meta['headerStyling'] : '';
                $selected_logo = !empty($page_header_styling) && !empty($page_header_styling['logo']) ? 'uploads/pages/'.$page_id .'/'.$page_header_styling['logo'] : '';
                $selected_menu_color = !empty($page_header_styling) && !empty($page_header_styling['menuColor']) ? $page_header_styling['menuColor'] : '';
                $selected_menu_hover_color = !empty($page_header_styling) && !empty($page_header_styling['menuHoverColor']) ? $page_header_styling['menuHoverColor'] : '';    
                $selected_color =  !empty($page_header_styling) && !empty($page_header_styling['color']) ? $page_header_styling['color'] : '';  
            }   elseif (Request::segment(1) == 'search-results') {
                    if (Schema::hasTable('site_managements')) {
                        $inner_page  = App\SiteManagement::getMetaValue('inner_page_data');
                        $header_styling = !empty($inner_page) && !empty($inner_page['show_search_list_header_styling']) ? $inner_page['show_search_list_header_styling'] : 'false';
                        if ($header_styling == 'true') {
                            $selected_menu_color = !empty($inner_page) && !empty($inner_page['search_menu_color']) ? $inner_page['search_menu_color'] : '';
                            $selected_menu_hover_color = !empty($inner_page) && !empty($inner_page['search_hover_color']) ? $inner_page['search_hover_color'] : '';    
                            $selected_color = !empty($inner_page) && !empty($inner_page['search_menu_text_color']) ? $inner_page['search_menu_text_color'] : '';  
                            $selected_logo = !empty($inner_page) && !empty($inner_page['search_logo']) ? 'uploads/settings/inner-page/'.$inner_page['search_logo'] : '';  
                        }
                        $page_header = !empty($inner_page) && !empty($inner_page['search_list_header_style']) ? $inner_page['search_list_header_style'] : '';
                    }
            }   elseif (Request::segment(1) == 'articles') {
                    if (Schema::hasTable('site_managements')) {
                        $inner_page  = App\SiteManagement::getMetaValue('inner_page_data');
                        $header_styling = !empty($inner_page) && !empty($inner_page['show_article_header_styling']) ? $inner_page['show_article_header_styling'] : 'false';
                        if ($header_styling == 'true') {
                            $selected_menu_color = !empty($inner_page) && !empty($inner_page['article_menu_color']) ? $inner_page['article_menu_color'] : '';
                            $selected_menu_hover_color = !empty($inner_page) && !empty($inner_page['article_hover_color']) ? $inner_page['article_hover_color'] : '';    
                            $selected_color = !empty($inner_page) && !empty($inner_page['article_menu_text_color']) ? $inner_page['article_menu_text_color'] : '';  
                            $selected_logo = !empty($inner_page) && !empty($inner_page['article_logo']) ? 'uploads/settings/inner-page/'.$inner_page['article_logo'] : '';  
                        }
                        $page_header = !empty($inner_page) && !empty($inner_page['article_header_style']) ? $inner_page['article_header_style'] : '';
                    }
            } elseif (Request::segment(1) == 'health-forum') {
                if (Schema::hasTable('site_managements')) {
                    $inner_page  = App\SiteManagement::getMetaValue('inner_page_data');
                    $header_styling = !empty($inner_page) && !empty($inner_page['show_forum_header_styling']) ? $inner_page['show_forum_header_styling'] : 'false';
                    if ($header_styling == 'true') {
                        $selected_menu_color = !empty($inner_page) && !empty($inner_page['forum_menu_color']) ? $inner_page['forum_menu_color'] : '';
                        $selected_menu_hover_color = !empty($inner_page) && !empty($inner_page['forum_hover_color']) ? $inner_page['forum_hover_color'] : '';    
                        $selected_color = !empty($inner_page) && !empty($inner_page['forum_menu_text_color']) ? $inner_page['forum_menu_text_color'] : '';  
                        $selected_logo = !empty($inner_page) && !empty($inner_page['forum_logo']) ? 'uploads/settings/inner-page/'.$inner_page['forum_logo'] : '';  
                    }
                    $page_header = !empty($inner_page) && !empty($inner_page['forum_header_style']) ? $inner_page['forum_header_style'] : '';
                }
            }
        } else {
            if (Schema::hasTable('site_managements')) {
                $page_id = APP\SiteManagement::select('meta_value')->where('meta_key', 'homepage')->pluck('meta_value')->first();
                $page_header = Helper::getPageHeader($page_id);
            }
        }
        if (empty($page_header) && !empty($settings)) {
            $page_header = !empty($settings['header_style']) ? $settings['header_style'] : '';
        }
    @endphp
@endif
@php
    $logo =  !empty($selected_logo) && file_exists($selected_logo) ? $selected_logo : Helper::getGeneralSettings('site_logo');
    $menuColor =  !empty($selected_menu_color) ? $selected_menu_color : $default_menu_color;
    $menuHoverColor =  !empty($selected_menu_hover_color) ? $selected_menu_hover_color : $default_menu_hover_color;
    $color =  !empty($selected_color) ? $selected_color : $default_color;
@endphp
@push('stylesheets')
    <style>
        .dc-navigation ul li a {
            color: {{$menuColor}};
        }
        .dc-navigation > ul > li.current-menu-item > a,
        .dc-navigation > ul > li:hover > a{
            color: {{$menuHoverColor}};
        }
        .dc-navigationarea .dc-navigation > ul > li > a:after{
            background: {{$menuHoverColor}};
        }
        .dc-username h4 {color: {{$color}} };
    </style>
@endpush
@section('header')
    @if ($page_header == 'headerv1')
        @if (file_exists(resource_path('views/extend/front-end.includes/headers/headerv1.blade.php'))) 
            @include('extend.front-end.includes.headers.headerv1')
        @else 
            @include('front-end.includes.headers.headerv1')
        @endif
    @elseif ($page_header == 'headerv2')
        @if (file_exists(resource_path('views/extend/front-end.includes/headers/headerv2.blade.php'))) 
            @include('extend.front-end.includes.headers.headerv2')
        @else 
            @include('front-end.includes.headers.headerv2')
        @endif
    @else
        @if (file_exists(resource_path('views/extend/front-end.includes/headers/headerv1.blade.php'))) 
            @include('extend.front-end.includes.headers.headerv1')
        @else 
            @include('front-end.includes.headers.headerv1')
        @endif
    @endif
@endsection
