@if (!(Schema::hasTable('site_managements'))) 
    @php 
        echo trans('lang.table_missing'); 
        die; 
    @endphp
@else
    @php 
        $inner_page_settings = !empty(App\SiteManagement::getMetaValue('inner_page_data'))
                                ? App\SiteManagement::getMetaValue('inner_page_data')  
                                : array();
        $show_forum_menu = !empty($inner_page_settings['show_forum_menu']) ? $inner_page_settings['show_forum_menu'] : 'true';
        $show_article_menu = !empty($inner_page_settings['show_article_menu']) ? $inner_page_settings['show_article_menu'] : 'true';
        $show_search_menu = !empty($inner_page_settings['show_search_menu']) ? $inner_page_settings['show_search_menu'] : 'true';
        $menu_settings  = App\SiteManagement::getMetaValue('menu_settings');
        $forum_order = !empty($menu_settings['pages']) ? Helper::getInnerPageOrder($menu_settings['pages'], 'id', 'health-forum') : ''; 
        $search_order = !empty($menu_settings['pages']) ? Helper::getInnerPageOrder($menu_settings['pages'], 'id', 'search-result') : ''; 
        $article_order = !empty($menu_settings['pages']) ? Helper::getInnerPageOrder($menu_settings['pages'], 'id', 'articles') : ''; 
        $pages_order = !empty($menu_settings['pages']) ? Helper::getInnerPageOrder($menu_settings['pages'], 'id', 'pages') : ''; 
    @endphp
@endif
<ul class="navbar-nav">
    @if ($show_forum_menu == 'true')
        <li class="nav-item" style="{{!empty($forum_order) ? 'order:'.$forum_order : 'order:99' }}">
            <a href="{{ route('forumQuestions') }}">{{ trans('lang.health_forum') }}</a>
        </li>
    @endif
    @if ($show_search_menu == 'true')
        <li class="menu-item-has-children page_item_has_children" style="{{!empty($search_order) ? 'order:'.$search_order : 'order:99' }}">
            <a href="javascript:void(0);">{{ trans('lang.search_results') }}</a>
            <ul class="sub-menu menu-item-moved">
                <li>
                    <a href="{{{ url('search-results?type=doctor') }}}">{{ trans('lang.search_doctors') }}</a>
                </li>
                <li>
                    <a href="{{{ url('search-results?type=hospital') }}}">{{ trans('lang.search_hospitals') }}</a>
                </li>
            </ul>
        </li>
    @endif
    @if ($show_article_menu == 'true')
        <li class="nav-item" style="{{!empty($article_order) ? 'order:'.$article_order : 'order:99' }}">
            <a href="{{ route('articleListing') }}">{{ trans('lang.articles') }}</a>
        </li>
    @endif
    @if (Schema::hasTable('pages'))
        @php $pages = App\Page::all();@endphp
        @if (!empty($pages) && $pages->count() > 0)
            <li class="menu-item-has-children page_item_has_children" style="{{!empty($pages_order) ? 'order:'.$pages_order : 'order:99' }}">
                <a href="javascript:void(0);">{{ trans('lang.pages') }}</a>
                <ul class="sub-menu">
                    @foreach ($pages as $key => $page)
                        @php
                            $page_order = DB::table('metas')
                                        ->select('meta_value')
                                        ->where('meta_key', 'page_order')
                                        ->where('metable_type', 'App\Page')
                                        ->where('metable_id', $page->id)->first();
                            $order = !empty($page_order->meta_value) ? $page_order->meta_value : '';
                            $page_has_child = App\Page::pageHasChild($page->id); 
                            $pageID = Request::segment(2);
                            $meta = !empty($page->meta) ? Helper::getUnserializeData($page->meta) : '';
                            $has_parent = App\Page::pageHasParent($page->id);
                        @endphp
                        @if (!empty($meta['show_page']) && $meta['show_page'] == 'true' && $has_parent == 0)
                            @if (!empty($page_has_child))
                                <li class="menu-item-has-children page_item_has_children "
                                    style="{{!empty($order) ? 'order:'.$order : 'order:99' }}"
                                 >
                            @else
                                <li style="{{!empty($order) ? 'order:'.$order : 'order:99' }}">    
                            @endif
                                <a href="{{url('page/'.$page->slug)}}">{{{ html_entity_decode(clean($page->title)) }}}</a>
                                <ul class="sub-menu">
                                    @foreach($page_has_child as $parent)
                                        @php $child = App\Page::getChildPages($parent->child_id);
                                            $page_obj = App\Page::find($parent->child_id);
                                            $child_meta = Helper::getUnserializeData($page_obj->meta);
                                            $parent_type = !empty($page_obj->metaValue('parent_type')) ? $page_obj->metaValue('parent_type')['meta_value'] : '';
                                            $show_child_page = $child_meta['show_page'];
                                        @endphp
                                        @if ($parent_type == 'page')
                                            @if (!empty ($show_child_page) && ($show_child_page == true || $show_child_page == 'true'))
                                                <li><a href="{{url('page/'.$child->slug.'/')}}">{{{ html_entity_decode(clean($child->title)) }}}</a></li>
                                            @endif
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </li>
        @endif
    @endif
    {{-- New Custom Links --}}
    @php 
        $order=''; $page_order=''; 
        $custom_menus = !empty($menu_settings['custom_links']) ? $menu_settings['custom_links'] : '';
    @endphp
    @if (!empty($custom_menus))
        @foreach($custom_menus as $custom_key => $custom_value)
            @if ($custom_value['relation_type'] == 'parent')
                @php 
                    $order = Helper::getCustomMenuPageOrder($custom_value['custom_slug']);
                @endphp
                <li style="{{!empty($order) ? 'order:'.$order : 'order:99' }}">
                    <a href="{{ empty($custom_value['custom_link']) || $custom_value['custom_link'] == '#' ? 'javascript:void(0)' : $custom_value['custom_link'] }}">
                        {{$custom_value['custom_title']}}
                    </a>
                    @php 
                        $custom_menu_child = Helper::getCustomMenuChild($custom_value['custom_slug']);
                    @endphp
                    @if (!empty($custom_menu_child))
                        <ul class="sub-menu">
                            @foreach($custom_menu_child as $custom_child)
                                @if (!empty($custom_child) && !empty($custom_child['type']) && $custom_child['type'] == 'custom_menu')
                                    <li>
                                        <a href="{{empty($custom_child['link']) || $custom_child['link'] == '#' ? 'javascript:void(0)' : $custom_child['link']}}">
                                            {{{$custom_child['title']}}}
                                        </a>
                                    </li>
                                @elseif (!empty($custom_child)) 
                                    @php 
                                        if ($custom_child['type'] == 'page') {
                                            $page_obj = App\Page::find($custom_child['id']);
                                            $child_meta = Helper::getUnserializeData($page_obj->meta);
                                            $show_child_page = $child_meta['show_page'];
                                        }
                                    @endphp
                                    @if (!empty($show_child_page) && ($show_child_page == true || $show_child_page == 'true'))
                                        <li class="@if ($pageID == $custom_child['slug'] ) current-menu-item @endif">
                                            <a href="{{url('page/'.$custom_child['slug'].'/')}}">
                                                {{{$custom_child['title']}}}
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endif
        @endforeach
    @endif
</ul>