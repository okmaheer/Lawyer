<?php

/**
 * Class ForumController
 *
 * @category Doctry
 *
 * @package Doctry
 * @author  Amentotech <theamentotech@gmail.com>
 * @license http://www.amentotech.com Amentotech
 * @link    http://www.amentotech.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Speciality;
use App\Forum;
use App\Helper;
use App\User;
use App\SiteManagement;
use Auth;
use Illuminate\Support\Facades\Redirect;
use View;
use Session;
use DB;

/**
 * Class ForumController
 *
 */
class ForumController extends Controller
{
    /**
     * Defining scope of the variable
     *
     * @access protected
     * @var    array $forum
     */
    protected $forum;

    /**
     * Create a new controller instance.
     *
     * @param instance $forum instance
     *
     * @return void
     */
    public function __construct(Forum $forum)
    {
        $this->forum = $forum;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sidebar  = SiteManagement::getMetaValue('sidebar_settings');
        $display_sidebar = !empty($sidebar) && !empty($sidebar['display_sidebar']) ? $sidebar['display_sidebar'] : '';
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
        $forum_settings  = SiteManagement::getMetaValue('forum_settings');
        $forum_banner_image = !empty($forum_settings) && !empty($forum_settings['hidden_forum_banner_image']) ? $forum_settings['hidden_forum_banner_image'] : '';
        $forum_banner_title = !empty($forum_settings) && !empty($forum_settings['forum_banner_title']) ? $forum_settings['forum_banner_title'] : '';
        $forum_banner_subtitle = !empty($forum_settings) && !empty($forum_settings['forum_banner_subtitle']) ? $forum_settings['forum_banner_subtitle'] : '';
        $forum_banner_desc = !empty($forum_settings) && !empty($forum_settings['forum_banner_desc']) ? $forum_settings['forum_banner_desc'] : '';
        $specialities = array_pluck(Speciality::get()->toArray(), 'title', 'id');
        $sort_by = !empty($_GET['sort_by']) ? $_GET['sort_by'] : '';
        $keyword = !empty($_GET['keyword']) ? $_GET['keyword'] : '';
        $specialty = !empty($_GET['speciality']) ? $_GET['speciality'] : '';
        $questions = new Forum();
        if (!empty($keyword)) {
            $questions = $questions->where('question_title', 'like', '%' . $keyword . '%');
        }
        if (!empty($specialty)) {
            $questions = $questions->where('speciality_id', $specialty);
        }
        if (!empty($sort_by)) {
            if ($sort_by == 'name') {
                $questions = $this->forum->orderBy('question_title', 'asc');
            } elseif ($sort_by == 'date') {
                $questions = $this->forum->orderBy('created_at', 'asc');
            } else {
                $questions = $this->forum->orderBy('id', 'asc');
            }
        }
        $questions = $questions->paginate(8);
        $inner_page  = SiteManagement::getMetaValue('inner_page_data');
        $health_meta_title = !empty($inner_page) && !empty($inner_page['health_meta_title']) ? $inner_page['health_meta_title'] : '';
        $health_meta_desc = !empty($inner_page) && !empty($inner_page['health_meta_desc']) ? $inner_page['health_meta_desc'] : '';
        return View(
            'front-end.forum.post-questions',
            compact(
                'health_meta_desc',
                'health_meta_title',
                'forum_banner_image',
                'forum_banner_title',
                'forum_banner_subtitle',
                'forum_banner_desc',
                'specialities',
                'questions',
                'display_sidebar',
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
                'ad_content'
            )
        );
    }

    /**
     * Post Question.
     *
     * @param \Illuminate\Http\Request $request request attributes
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
        if (Auth::check()) {
            $this->validate(
                $request,
                [
                    'speciality' => 'required',
                    'question_title' => 'required',
                    'question_desc' => 'required',
                ]
            );
            $post_question = $this->forum->postQuestion($request);
            if ($post_question === 'success') {
                return response()->json(
                    [
                        'type' => 'success',
                        'progressing' => trans('lang.saving'),
                        'message' => trans('lang.quest_post_success')
                    ]
                );
            } else {
                return response()->json(
                    [
                        'type' => 'error',
                        'message' => trans('lang.something_went_wrong')
                    ]
                );
            }
        } else {
            return response()->json(
                [
                    'type' => 'error',
                    'message' => trans('lang.need_to_reg')
                ]
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug slug
     * 
     * @return view
     */
    public function getForumAnswers($slug)
    {
        if (!empty($slug)) {
            $sidebar  = SiteManagement::getMetaValue('sidebar_settings');
            $display_sidebar = !empty($sidebar) && !empty($sidebar['display_sidebar']) ? $sidebar['display_sidebar'] : '';
            $display_query_section = !empty($sidebar) && !empty($sidebar['display_query_section']) ? $sidebar['display_query_section'] : '';
            $ask_query_img = !empty($sidebar) && !empty($sidebar['ask_query_img']) ? $sidebar['ask_query_img'] : '';
            $query_title = !empty($sidebar) && !empty($sidebar['query_title']) ? $sidebar['query_title'] : '';
            $query_subtitle = !empty($sidebar) && !empty($sidebar['query_subtitle']) ? $sidebar['query_subtitle'] : '';
            $query_btn_title = !empty($sidebar) && !empty($sidebar['query_btn_title']) ? $sidebar['query_btn_title'] : '';
            $query_btn_link = !empty($sidebar) && !empty($sidebar['query_btn_link']) ? $sidebar['query_btn_link'] : '';
            $query_desc = !empty($sidebar) && !empty($sidebar['query_desc']) ? $sidebar['query_desc'] : '';
            $display_get_app_sec = !empty($sidebar) && !empty($sidebar['display_get_app_sec']) ? $sidebar['display_get_app_sec'] : '';
            $download_app_img = !empty($sidebar) && !empty($sidebar['download_app_img']) ? $sidebar['download_app_img'] : '';
            $download_app_title = !empty($sidebar) && !empty($sidebar['download_app_title']) ? $sidebar['download_app_title'] : '';
            $download_app_subtitle = !empty($sidebar) && !empty($sidebar['download_app_subtitle']) ? $sidebar['download_app_subtitle'] : '';
            $download_app_desc = !empty($sidebar) && !empty($sidebar['download_app_desc']) ? $sidebar['download_app_desc'] : '';
            $download_app_link = !empty($sidebar) && !empty($sidebar['download_app_link']) ? $sidebar['download_app_link'] : '';
            $display_get_ad_sec = !empty($sidebar) && !empty($sidebar['display_get_ad_sec']) ? $sidebar['display_get_ad_sec'] : '';
            $ad_content = !empty($sidebar) && !empty($sidebar['ad_content']) ? $sidebar['ad_content'] : '';
            $forum_settings  = SiteManagement::getMetaValue('forum_settings');
            $forum_banner_image = !empty($forum_settings) && !empty($forum_settings['hidden_forum_banner_image']) ? $forum_settings['hidden_forum_banner_image'] : '';
            $forum_banner_title = !empty($forum_settings) && !empty($forum_settings['forum_banner_title']) ? $forum_settings['forum_banner_title'] : '';
            $forum_banner_subtitle = !empty($forum_settings) && !empty($forum_settings['forum_banner_subtitle']) ? $forum_settings['forum_banner_subtitle'] : '';
            $forum_banner_desc = !empty($forum_settings) && !empty($forum_settings['forum_banner_desc']) ? $forum_settings['forum_banner_desc'] : '';
            $id = Forum::where('slug', $slug)->pluck('id')->first();
            $forum = $this->forum::findOrFail($id);
            $user = $forum->questioner->count() > 0 ? User::findOrFail($forum->questioner->first()->id) : '';
            $specialities = array_pluck(Speciality::get()->toArray(), 'title', 'id');
            $liked_answers = !empty(auth()->user()->profile->liked_answers) ? unserialize(auth()->user()->profile->liked_answers) : array();
            $inner_page  = SiteManagement::getMetaValue('inner_page_data');
            $health_meta_desc = !empty($inner_page) && !empty($inner_page['health_meta_desc']) ? $inner_page['health_meta_desc'] : '';
            return View(
                'front-end.forum.answers',
                compact(
                    'health_meta_desc',
                    'forum_banner_image',
                    'forum_banner_title',
                    'forum_banner_subtitle',
                    'forum_banner_desc',
                    'forum',
                    'user',
                    'liked_answers',
                    'specialities',
                    'display_sidebar',
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
                    'slug'
                )
            );
        } else {
            return Redirect::to('/404');
        }
    }

    /**
     * Post Answer.
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function postAnswer(Request $request)
    {
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return $response;
        }
        if (Auth::check()) {
            $this->validate(
                $request,
                [
                    'forum_answer' => 'required',
                ]
            );
            $post_answer = $this->forum->postAnswer($request);
            if ($post_answer === 'success') {
                return response()->json(
                    [
                        'type' => 'success',
                        'progressing' => trans('lang.saving'),
                        'message' => trans('lang.answer_post_success')
                    ]
                );
            } else {
                return response()->json(
                    [
                        'type' => 'error',
                        'message' => trans('lang.something_went_wrong')
                    ]
                );
            }
        } else {
            return response()->json(
                [
                    'type' => 'error',
                    'message' => trans('lang.need_to_reg')
                ]
            );
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminForumListing(Request $request)
    {
        if (!empty($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            $forums = $this->forum::where('question_title', 'like', '%' . $keyword . '%')->paginate(5)->setPath('');
            $pagination = $forums->appends(
                array(
                    'keyword' => $request->get('keyword')
                )
            );
        } else {
            $forums = $this->forum->paginate(5);
        }
        if (file_exists(resource_path('views/extend/back-end/admin/forums/index.blade.php'))) {
            return View::make(
                'extend.back-end.admin.forums.index',
                compact('forums')
            );
        } elseif (file_exists(resource_path('views/back-end/admin/forums/index.blade.php'))) {
            return View::make(
                'back-end.admin.forums.index',
                compact('forums')
            );
        } else {
            return Redirect::to('/404');
        }
    }

    /**
     * Edit Locations.
     *
     * @param int $slug string
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!empty($id)) {
            $forum = $this->forum::find($id);
            $specialities = array_pluck(Speciality::get()->toArray(), 'title', 'id');
            if (!empty($forum)) {
                if (file_exists(resource_path('views/extend/back-end/admin/forums/edit.blade.php'))) {
                    return View::make(
                        'extend.back-end.admin.forums.edit', compact('forum', 'specialities')
                    );
                } elseif (file_exists(resource_path('views/back-end/admin/forums/edit.blade.php'))) {
                    return View::make(
                        'back-end.admin.forums.edit', compact('forum', 'specialities')
                    );
                } else {
                    return Redirect::to('/404');
                }
                Session::flash('message', trans('lang.forum_updated'));
                return Redirect::to('admin/forum');
            } else {
                return Redirect::to('/404');
            }
        } else {
            return Redirect::to('/404');
        }
    }

    /**
     * Update locations.
     *
     * @param string $request string
     * @param int    $id      integer
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $server_verification = Helper::doctieIsDemoSite();
        if (!empty($server_verification)) {
            Session::flash('error', $server_verification);
            return Redirect::back();
        }
        $this->validate(
            $request,
            [
                'title' => 'required',
            ]
        );
        $this->forum->updateforum($request, $id);
        Session::flash('message', trans('lang.forum_updated'));
        return Redirect::to('admin/forums');
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug slug
     * 
     * @return view
     */
    public function getAdminForumAnswers($id)
    {
        if (!empty($id)) {
            $forum = $this->forum::findOrFail($id);
            $answers = $forum->answers->count() > 0 && !empty($forum->answers) ? $forum->answers : array();
            if (file_exists(resource_path('views/extend/back-end/admin/forums/answer.blade.php'))) {
                return View::make(
                    'extend.back-end.admin.forums.answer',
                    compact('answers', 'forum')
                );
            } elseif (file_exists(resource_path('views/back-end/admin/forums/answer.blade.php'))) {
                return View::make(
                    'back-end.admin.forums.answer',
                    compact('answers', 'forum')
                );
            } else {
                return Redirect::to('/404');
            }
        } else {
            return Redirect::to('/404');
        }
    }

    /**
     * Post Answer.
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function updateUserAnswer(Request $request)
    {
        $json = array();
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return $response;
        }
        if (!empty($request) && !empty($request['id'])) {
            $this->validate(
                $request,
                [
                    'answer' => 'required',
                ]
            );
            DB::table('user_forum')
            ->where('id', $request['id'])
            ->update(['answer' => $request['answer']]);
            // Session::flash('message', trans('lang.answer_updated'));
            // return Redirect::to(url('admin/question/answers/'.$request['id']));
            $json['type'] = 'success';
            $json['message'] = trans('lang.answer_updated');
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }

    /**
     * Remove service from database.
     *
     * @param mixed $request request attributes
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
            $forum = $this->forum::find($id);
            $forum->users()->detach();
            $forum->delete();
            $json['type'] = 'success';
            $json['message'] = trans('lang.forum_deleted');
            return $json;
        } else {
            $json['type'] = 'error';
            return $json;
        }
    }

    /**
     * Remove service from database.
     *
     * @param mixed $request request attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyAnswer(Request $request)
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
            DB::table('user_forum')->where('id', $id)->delete();
            $json['type'] = 'success';
            $json['message'] = trans('lang.answer_deleted');
            return $json;
        } else {
            $json['type'] = 'error';
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
        $server = Helper::DoctieIsDemoSiteAjax();
        if (!empty($server)) {
            $json['type'] = 'error';
            $json['message'] = $server->getData()->message;
            return $json;
        }
        $json = array();
        $checked = $request['ids'];
        if (!empty($checked)) {
            if ($checked[0] == 'on') {
                array_shift($checked);
            }
            foreach ($checked as $id) {
                $forum = $this->forum::find($id);
                if ($forum->users->count() > 0) {
                    $forum->users()->detach();
                }
                $forum->delete();
            }
            $json['type'] = 'success';
            $json['message'] = trans('lang.forums_deleted');
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
    public function deleteSelectedAnswers(Request $request)
    {
        $server = Helper::DoctieIsDemoSiteAjax();
        if (!empty($server)) {
            $json['type'] = 'error';
            $json['message'] = $server->getData()->message;
            return $json;
        }
        $json = array();
        $checked = $request['ids'];
        if (!empty($checked)) {
            if ($checked[0] == 'on') {
                array_shift($checked);
            }
            foreach ($checked as $id) {
                DB::table('user_forum')->where('id', $id)->delete();
            }
            $json['type'] = 'success';
            $json['message'] = trans('lang.answers_deleted');
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }
}
