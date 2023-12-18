<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SiteManagement;
use App\Helper;
use App\Page;
use View;
use DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Schema::hasTable('site_managements')) {
            $homepage = DB::table('site_managements')->select('meta_value')->where('meta_key', 'homepage')->get()->first();
            if (!empty($homepage)) {
                $page = DB::table('pages')->select('*')->where('id', $homepage->meta_value)->get()->first();
                if (!empty($page)) {
                    $slug = $page->slug;
                    $page_meta = !empty($page) ? Helper::getUnserializeData($page->meta) : array();
                    $meta_desc = !empty($page_meta['seo_desc']) ? $page_meta['seo_desc'] : '';
                    $meta_title = !empty($page_meta['meta_title']) ? $page_meta['meta_title'] : '';
                    $show_sidebar = !empty($page_meta['sidebar']) ? $page_meta['sidebar'] : '';
                    $sidebarPosition = !empty($page_meta['sidebar']) && !empty($page_meta['sidebarOrder']) ? $page_meta['sidebarOrder'] : 'right';
                    $general_settings  = SiteManagement::getMetaValue('general_settings');
                    $selected_search_form_type = !empty($general_settings) && !empty($general_settings['search_form_type']) ? $general_settings['search_form_type'] : 'global_searching';
                    $home = true;
                    if (file_exists(resource_path('views/extend/front-end/pages/show.blade.php'))) {
                        return View::make('extend.front-end.pages.show', compact('page', 'slug', 'meta_desc', 'meta_title', 'home', 'show_sidebar', 'sidebarPosition', 'selected_search_form_type'));
                    } else {
                        return View::make('front-end.pages.show', compact('page', 'slug', 'meta_desc', 'meta_title', 'home', 'show_sidebar', 'sidebarPosition', 'selected_search_form_type'));
                    }
                } else {
                    if (file_exists(resource_path('views/extend/front-end/index.blade.php'))) {
                        return view('extend.front-end.index');
                    } else {
                        return view('front-end.index');
                    }
                }
            } else {
                if (file_exists(resource_path('views/extend/front-end/index.blade.php'))) {
                    return view('extend.front-end.index');
                } else {
                    return view('front-end.index');
                }
            }
        }
    }
}
