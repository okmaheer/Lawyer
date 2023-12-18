<?php

/**
 * Class DoctorController
 *
 * @category Doctry
 *
 * @package Doctry
 * @author  Amentotech <theamentotech@gmail.com>
 * @license http://www.amentotech.com Amentotech
 * @link    http://www.amentotech.com
 */

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use View;
use Illuminate\Support\Facades\Redirect;
use Session;
use DB;
use App\Helper;
use App\SiteManagement;

/**
 * Class PageController
 *
 */
class PageController extends Controller
{
    /**
     * Defining scope of the variable
     *
     * @access public
     * @var    array $page
     */
    protected $page;

    /**
     * Create a new controller instance.
     *
     * @param instance $page instance
     *
     * @return void
     */
    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = $this->page::getPages();
        if (file_exists(resource_path('views/extend/back-end/admin/pages/index.blade.php'))) {
            return View::make('extend.back-end.admin.pages.index', compact('pages'));
        } else {
            return View::make(
                'back-end.admin.pages.index',
                compact('pages')
            );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parent_page = DB::table('pages')->select('title', 'id')->where('relation_type', 0)->get()->toArray();
        $page_created = trans('lang.page_created');
        $sections = Helper::getPageSections();
        $layouts = Helper::getSectionLayouts();
        if (file_exists(resource_path('views/extend/back-end/admin/pages/create.blade.php'))) {
            return View::make(
                'extend.back-end.admin.pages.create',
                compact(
                    'parent_page',
                    'page_created',
                    'sections',
                    'layouts'
                )
            );
        } elseif (file_exists(resource_path('views/back-end/admin/pages/create.blade.php'))) {
            return View::make(
                'back-end.admin.pages.create',
                compact(
                    'parent_page',
                    'page_created',
                    'sections',
                    'layouts'
                )
            );
        } else {
            return Redirect::to('/404');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param mixed $request $req->attr
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return $response;
        }
        $json = array();
        if (!empty($request)) {
            $this->validate(
                $request,
                [
                    'title' => 'required|string',
                ]
            );
            $save_page = $this->page->savePage($request);
            if ($request->parent_type == 'page') {
                if ($request['parent_id']) {
                    DB::table('child_pages')->insert(
                        ['parent_id' => $request['parent_id'], 'child_id' => $save_page['id']]
                    );
                }
            }
            $json['type'] = 'success';
            $json['message'] = trans('lang.page_created');
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug page slug
     *
     * @return view
     */
    public function show($slug)
    {
        if (!empty($slug)) {
            $page = DB::table('pages')->select('*')->where('slug', $slug)->get()->first();
            if (!empty($page)) {
                $page_meta = !empty($page) ? Helper::getUnserializeData($page->meta) : array();
                $meta_desc = !empty($page_meta['seo_desc']) ? $page_meta['seo_desc'] : '';
                $meta_title = !empty($page_meta['meta_title']) ? $page_meta['meta_title'] : '';
                $show_sidebar = !empty($page_meta['sidebar']) ? $page_meta['sidebar'] : '';
                $sidebarPosition = !empty($page_meta['sidebar']) && !empty($page_meta['sidebarOrder']) ? $page_meta['sidebarOrder'] : 'right';
                $home = false;
                $sidebar  = SiteManagement::getMetaValue('sidebar_settings');
                $general_settings  = SiteManagement::getMetaValue('general_settings');
                $selected_search_form_type = !empty($general_settings) && !empty($general_settings['search_form_type']) ? $general_settings['search_form_type'] : 'global_searching';
                $display_query_section = !empty($sidebar) && !empty($sidebar['display_query_section']) ? $sidebar['display_query_section'] : '';
                $ask_query_img = !empty($sidebar) && !empty($sidebar['hidden_ask_query_img']) ? $sidebar['hidden_ask_query_img'] : '';
                $query_title = !empty($sidebar) && !empty($sidebar['query_title']) ? $sidebar['query_title'] : '';
                $query_subtitle = !empty($sidebar) && !empty($sidebar['query_subtitle']) ? $sidebar['query_subtitle'] : '';
                $query_btn_title = !empty($sidebar) && !empty($sidebar['query_btn_title']) ? $sidebar['query_btn_title'] : '';
                $query_btn_link = !empty($sidebar) && !empty($sidebar['query_btn_link']) ? $sidebar['query_btn_link'] : '';
                $query_desc = !empty($sidebar) && !empty($sidebar['query_desc']) ? $sidebar['query_desc'] : '';
                $display_get_app_sec = !empty($sidebar) && !empty($sidebar['display_get_app_sec']) ? $sidebar['display_get_app_sec'] : '';
                $download_app_img = !empty($sidebar) && !empty($sidebar['hidden_download_app_img']) ? $sidebar['hidden_download_app_img'] : '';
                $download_app_title = !empty($sidebar) && !empty($sidebar['download_app_title']) ? $sidebar['download_app_title'] : '';
                $download_app_subtitle = !empty($sidebar) && !empty($sidebar['download_app_subtitle']) ? $sidebar['download_app_subtitle'] : '';
                $download_app_desc = !empty($sidebar) && !empty($sidebar['download_app_desc']) ? $sidebar['download_app_desc'] : '';
                $download_app_link = !empty($sidebar) && !empty($sidebar['download_app_link']) ? $sidebar['download_app_link'] : '';
                $display_get_ad_sec = !empty($sidebar) && !empty($sidebar['display_get_ad_sec']) ? $sidebar['display_get_ad_sec'] : '';
                $ad_content = !empty($sidebar) && !empty($sidebar['ad_content']) ? $sidebar['ad_content'] : '';
                if (file_exists(resource_path('views/extend/front-end/pages/show.blade.php'))) {
                    return View::make('extend.front-end.pages.show', compact(
                        'selected_search_form_type',
                        'show_sidebar',
                        'page',
                        'slug',
                        'meta_desc',
                        'meta_title',
                        'home',
                        'sidebar',
                        'sidebarPosition',
                        'display_query_section',
                        'ask_query_img',
                        'query_title',
                        'query_subtitle',
                        'query_btn_title',
                        'query_btn_link',
                        'query_desc',
                        'display_get_app_sec',
                        'download_app_img',
                        'download_app_title',
                        'download_app_subtitle',
                        'download_app_desc',
                        'download_app_link',
                        'display_get_ad_sec',
                        'ad_content',
                        'show_sidebar'
                    ));
                } elseif (file_exists(resource_path('views/front-end/pages/show.blade.php'))) {
                    return View::make('front-end.pages.show', compact(
                        'selected_search_form_type',
                        'show_sidebar',
                        'page',
                        'slug',
                        'meta_desc',
                        'meta_title',
                        'home',
                        'sidebar',
                        'sidebarPosition',
                        'display_query_section',
                        'ask_query_img',
                        'query_title',
                        'query_subtitle',
                        'query_btn_title',
                        'query_btn_link',
                        'query_desc',
                        'display_get_app_sec',
                        'download_app_img',
                        'download_app_title',
                        'download_app_subtitle',
                        'download_app_desc',
                        'download_app_link',
                        'display_get_ad_sec',
                        'ad_content',
                        'show_sidebar'
                    ));
                } else {
                    return Redirect::to('/404');
                }
            } else {
                return Redirect::to('/404');
            }
        } else {
            return Redirect::to('/404');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param integer $id page Id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!empty($id)) {
            $page = $this->page::find($id);
            $sections = Helper::getPageSections();
            $layouts = Helper::getSectionLayouts();
            $parent_selected_id = '';
            $parent_page = $this->page->getParentPages($id);
            $has_child = $this->page->pageHasChild($id);
            $child_parent_id = DB::table('child_pages')->select('parent_id')->where('child_id', $id)->get()->first();
            $page_parent_type = !empty($page->metaValue('parent_type')) ? $page->metaValue('parent_type')['meta_value'] : '';
            if ($page_parent_type == 'custom_link') {
                $parent_selected_id =!empty($page->metaValue('custom_link')) ? $page->metaValue('custom_link')['meta_value'] : '';
            }
            if ($page_parent_type == 'page') {
                if (!empty($child_parent_id->parent_id)) {
                    $parent_selected_id = $child_parent_id->parent_id;
                } else {
                    $parent_selected_id = '';
                }
            }
            $meta = !empty($page->meta) ? Helper::getUnserializeData($page->meta) : '';
            $seo_description = !empty($meta) ? $meta['seo_desc'] : '';
            if (file_exists(resource_path('views/extend/back-end/admin/pages/edit.blade.php'))) {
                return View::make(
                    'extend.back-end.admin.pages.edit',
                    compact('page', 'parent_page', 'parent_selected_id', 'id', 'has_child', 'seo_description', 'sections', 'layouts')
                );
            } elseif (file_exists(resource_path('views/back-end/admin/pages/edit.blade.php'))) {
                return View::make(
                    'back-end.admin.pages.edit',
                    compact('page', 'parent_page', 'parent_selected_id', 'id', 'has_child', 'seo_description', 'sections', 'layouts')
                );
            } else {
                return Redirect::to('/404');
            }
        } else {
            return Redirect::to('/404');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param integer $id page Id
     *
     * @return \Illuminate\Http\Response
     */
    public function getEditPageData($id)
    {
        $json = array();
        $page = array();
        if (!empty($id)) {
            $selected_page = $this->page::find($id);
            if (!empty($selected_page)) {
                $page_options = !empty($selected_page->meta) ? Helper::getUnserializeData($selected_page->meta) : '';
                $page_data = $selected_page->toArray();
                $page['id'] = $page_data['id'];
                $page['title'] = $page_data['title'];
                $page['slug'] = $page_data['slug'];
                $page['body'] = $page_data['body'];
                $page['page_options'] = $page_options;
                $page['section_list'] = !empty($page_data['sections']) ? Helper::getUnserializeData($page_data['sections']) : '';
                $page['parent_type'] = !empty($selected_page->metaValue('parent_type')) ? $selected_page->metaValue('parent_type')['meta_value'] : '';
                $json['page'] = $page;
                $json['type'] = 'success';
                return $json;
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.page_not_found');
                return $json;
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.page_not_found');
            return $json;
        }
    }

    /**
     * Get the specified page.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEditPageSections($id)
    {
        $json = array();
        $selected_page = $this->page::find($id);
        $count = 0;
        $prepare_array = array();
        if (!empty($selected_page->metas) && $selected_page->metas->count() > 0) {
            foreach ($selected_page->metas->toArray() as $key => $meta) {
                $meta_key_modify = preg_replace('/\d/', '', $meta['meta_key']);
                $section_index = preg_match_all('!\d+!', $meta['meta_key'], $matches);
                if (
                    $meta['meta_key'] !== 'show_page_title' && $meta['meta_key'] !== 'page_order'
                    && $meta['meta_key'] !== 'page_banner' && $meta['meta_key'] !== 'meta_desc'
                    && $meta['meta_key'] !== 'meta_title' && $meta['meta_key'] !== 'show_page_banner'
                    && $meta['meta_key'] !== 'parent_type' && $meta['meta_key'] !== 'custom_link'
                ) {
                    $prepare_array[$meta_key_modify][$count] = Helper::getUnserializeData($meta['meta_value'] . $section_index);
                }
                $count++;
            }
        }
        $sections_data = array_map('array_values', $prepare_array);
        $json['section_data'] = $sections_data;
        $json['type'] = 'success';
        return $json;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param mixed $request $req->attr
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $server_verification = Helper::doctieIsDemoSite();
        if (!empty($server_verification)) {
            Session::flash('error', $server_verification);
            return Redirect::to('admin/pages');
        }
        if (!empty($request)) {
            $this->validate(
                $request,
                [
                    'title' => 'required|string',
                ]
            );
            $id = $request['id'];
            $parent_id = filter_var($request['parent_id'], FILTER_SANITIZE_NUMBER_INT);
            $child_id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
            $this->page->savePage($request);

            if ($parent_id == null) {
                DB::table('child_pages')->where('child_id', '=', $child_id)->delete();
            } elseif ($parent_id) {
                DB::table('child_pages')->where('child_id', '=', $child_id)->delete();
                if ($request->parent_type == 'page') {
                    if ($parent_id) {
                        DB::table('child_pages')->insert(
                            ['parent_id' => $parent_id, 'child_id' => $child_id]
                        );
                    }
                }
            }
            return response()->json(
                [
                    'type' => 'success',
                    'message' => trans('lang.page_updated')
                ]
            );
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $request $req->attr
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $json['type'] = 'error';
            $json['message'] = $server->getData()->message;
            return $json;
        }
        $json = array();
        $id = $request['id'];
        if (!empty($id)) {
            $child_pages = $this->page::pageHasChild($id);
            if (!empty($child_pages)) {
                foreach ($child_pages as $page) {
                    DB::table('pages')->where('id', $page->child_id)->update(['relation_type' => 0]);
                }
            } else {
                $relation = DB::table('pages')->select('relation_type')->where('id', $id)->get()->first();
                if ($relation->relation_type == 1) {
                    DB::table('pages')->where('id', $id)->update(['relation_type' => 0]);
                }
            }
            DB::table('child_pages')->where('child_id', '=', $id)->orWhere('parent_id', '=', $id)->delete();
            DB::table('pages')->where('id', '=', $id)->delete();
            $json['type'] = 'success';
            $json['message'] = trans('lang.page_deleted');
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $request request attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteSelected(Request $request)
    {
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $json['type'] = 'error';
            $json['message'] = $server->getData()->message;
            return $json;
        }
        $json = array();
        $checked = !empty($request) && !empty($request['ids']) ? $request['ids'] : '';
        if (!empty($checked)) {
            foreach ($checked as $id) {
                $this->page::where("id", $id)->delete();
            }
            $json['type'] = 'success';
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }

    /**
     * Get parent pages
     *
     * @param mixed $request request attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function getParentPages($id)
    {
        $homepage = DB::table('site_managements')->select('meta_value')->where('meta_key', 'homepage')->get()->first(); 
        $home_page_id = !empty($homepage) && !empty($homepage->meta_value) ? $homepage->meta_value : '';
        if (!empty($id)) {
            $parent_page = DB::table('pages')
                ->select('title', 'id')
                ->where('id', '!=', $id)
                ->where('relation_type', 0)
                ->where('id', '!=', $home_page_id)
                ->get()->toArray();
        } else {
            $parent_page = DB::table('pages')
                ->select('title', 'id')
                ->where('relation_type', 0)
                ->get()->toArray();
        }
        $json = array();
        if (!empty($parent_page)) {
            $json['parent'] = $parent_page;
            $json['type'] = 'success';
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }

    /**
     * Get parent pages
     *
     * @param mixed $request request attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllParentPages()
    {
        $homepage = DB::table('site_managements')->select('meta_value')->where('meta_key', 'homepage')->get()->first(); 
        $home_page_id = !empty($homepage) && !empty($homepage->meta_value) ? $homepage->meta_value : '';
        $parent_page = DB::table('pages')
            ->select('title', 'id')
            ->where('relation_type', 0)
            ->where('id', '!=', $home_page_id)
            ->get()
            ->toArray();
        $json = array();
        if (!empty($parent_page)) {
            $json['parent'] = $parent_page;
            $json['type'] = 'success';
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }

    /**
     * Get pages List.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPagesList () {
        $json = array();
        $child_pages = DB::table('child_pages')->select('child_id')->get()->pluck('child_id')->toArray();
        $pages = Page::select('id', 'title', 'meta')->whereNotIn('id', $child_pages)->get()->toArray();
        $show_pages_list = array();
        $count = 0 ;
        foreach ($pages as $key => $page) {
            $page_meta = !empty($page['meta']) ? Helper::getUnserializeData($page['meta']) : '';
            $enable_page = !empty($page_meta) && ($page_meta['show_page'] == 'true' || $page_meta['show_page'] == true) ? true : false;
            if (!empty($enable_page) && $enable_page == true) {
                $show_pages_list[$count] = $page;
                $count++;
            }
        }
        // dd($show_pages_list);
        if (!empty($show_pages_list)) {
            $json['type'] = 'success';
            $json['pages'] = $show_pages_list;
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }

    /**
     * Get inner pages List.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getInnerPagesList () {
        $json = array();
        $count = 0;
        $show_pages_list = array();
        $menu_settings = !empty(SiteManagement::getMetaValue('menu_settings')) ? SiteManagement::getMetaValue('menu_settings') : array();
        if (!empty($menu_settings['custom_links'])) {
            foreach ($menu_settings['custom_links'] as $key => $custom_menu) {
                if ($custom_menu['relation_type'] == 'parent') {
                    $show_pages_list[$count]['id'] = $custom_menu['custom_slug'];
                    $show_pages_list[$count]['title'] = $custom_menu['custom_title'];
                    $show_pages_list[$count]['type'] = 'custom_menu';
                    $count++;
                }
            }
        }
        $inner_pages = array( 
            '0' => array(
                'id' => 'health-forum',
                'title' => 'Health Forum',
                'type' => 'innerPages',
            ),
            '1' => array(
                'id' => 'search-result',
                'title' => 'Search Result',
                'type' => 'innerPages',
            ),
            '2' => array(
                'id' => 'articles',
                'title' => 'Articles',
                'type' => 'innerPages',
            ),
            '3' => array(
                'id' => 'pages',
                'title' => 'Pages',
                'type' => 'innerPages',
            ),
        );
        foreach ($inner_pages as $innerPage) {
            array_push($show_pages_list, $innerPage);
        }
        if (!empty($show_pages_list)) {
            $json['type'] = 'success';
            $json['pages'] = $show_pages_list;
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }
}
