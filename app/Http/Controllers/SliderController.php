<?php

namespace App\Http\Controllers;

use App\Slider;
use App\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session;
use View;

class SliderController extends Controller
{
    /**
     * Defining scope of the variable
     *
     * @access public
     * @var    array $slider
     */
    protected $slider;

    /**
     * Create a new controller instance.
     *
     * @param mixed $slider slider instance
     *
     * @return void
     */
    public function __construct(Slider $slider)
    {
        $this->slider = $slider;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $styles = Helper::getSliderStyles();
        if (!empty($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            $sliders = $this->slider::where('title', 'like', '%' . $keyword . '%')->paginate(10)->setPath('');
            $pagination = $sliders->appends(
                array(
                    'keyword' => $request->get('keyword')
                )
            );
        } else {
            $sliders = $this->slider->paginate(10);
        }
        if (file_exists(resource_path('views/extend/back-end/admin/sliders/index.blade.php'))) {
            return View::make(
                'extend.back-end.admin.sliders.index',
                compact('sliders', 'styles')
            );
        } else {
            return View::make(
                'back-end.admin.sliders.index',
                compact('sliders', 'styles')
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $server_verification = Helper::doctieIsDemoSite();
        if (!empty($server_verification)) {
            Session::flash('error', $server_verification);
            return Redirect::back();
        }
        if (!empty($request)) {
            $this->validate($request, [
                'title' => 'required',
            ]);
            $save_slider = $this->slider->saveSlider($request);
            if ($save_slider['type'] == 'success') {
                Session::flash('message', trans('lang.save_slider'));
                return Redirect::back();
            } else {
                Session::flash('error', $save_slider['message']);
                return Redirect::back();
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!empty($id)) {
            $styles = Helper::getSliderStyles();
            $slider =  $this->slider::find($id);
            $meta =  $slider->metaValue('slider');
            $slides  = Helper::getUnserializeData($meta['meta_value']);
            $slide = !empty($slides['slide']) ? $slides['slide'] : array(); 
            // dd(json_encode($slide));
            $search_title = !empty($slide['search_title']) ? $slide['search_title'] :''; 
            $search_subtitle1 = !empty($slide['search_subtitle1']) ? $slide['search_subtitle1'] :''; 
            $search_subtitle2 = !empty($slide['search_subtitle2']) ? $slide['search_subtitle2'] :''; 
            if (!empty($slider)) {
                if (file_exists(resource_path('views/extend/back-end/admin/sliders/edit.blade.php'))) {
                    return View::make(
                        'extend.back-end.admin.sliders.edit',
                        compact(
                            'slider',
                            'styles',
                            'slides',
                            'slide',
                            'search_title',
                            'search_subtitle1',
                            'search_subtitle2'
                        )
                    );
                } else {
                    return View::make(
                        'back-end.admin.sliders.edit',
                        compact(
                            'slider',
                            'styles',
                            'slides',
                            'slide',
                            'search_title',
                            'search_subtitle1',
                            'search_subtitle2'
                        )
                    );
                }
                Session::flash('message', trans('lang.slider_updated'));
                return Redirect::to('admin/sliders');
            }
        } else {
            return Redirect::to('/404');
        }   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $server_verification = Helper::doctieIsDemoSite();
        if (!empty($server_verification)) {
            Session::flash('error', $server_verification);
            return Redirect::back();
        }
        if (!empty($request)) {
            $this->validate($request, [
                'title' => 'required',
            ]);
            $update_slider = $this->slider->updateSlider($request, $id);
            if ($update_slider['type'] == 'success') {
                Session::flash('message', trans('lang.update_slider'));
                return Redirect::to('admin/sliders');
            } else {
                Session::flash('error', $update_slider['message']);
                return Redirect::back();
            }
        }  else {
            Session::flash('error', trans('lang.empty_field'));
            return Redirect::back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $server_verification = Helper::doctieIsDemoSite();
        if (!empty($server_verification)) {
            Session::flash('error', $server_verification);
            return Redirect::back();
        }
        if (!empty($request['id'])) {
            $slider =  $this->slider::find($request['id']);
            $slider->delete();
            $slider->meta()->delete();
            $json['type'] = 'success';
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
            unset($checked[0]);
            foreach ($checked as $id) {
                $slider =  $this->slider::find($id);
                $slider->meta()->delete();
                $this->slider::where("id", $id)->delete();
            }
            $json['type'] = 'success';
            $json['message'] = trans('lang.slider_deleted');
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }

    /**
     * Get sliders.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSliders()
    {
        $json = array();
        $sliders = $this->slider->select('id', 'title')->get()->toArray();
        if (!empty($sliders)) {
            foreach ($sliders as $key => $slider) {
                $slide = $this->slider->find($slider['id']);
                $slides[$key]['title'] = $slide->title;
                $slides[$key]['id'] = $slide->id;
                $slides[$key]['slides'] = !empty($slide->metaValue('slider')['meta_value']) ? unserialize($slide->metaValue('slider')['meta_value']) : '';
            }
            // $json['data']['sliders'] = $sliders;
            $json['slides'] = $slides;
            $json['type'] = 'success';
            return $json;
        } else {
            $json['type'] = 'error';
            return $json;
        }
    }

    /**
     * Get sliders.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSelectedSlider($id)
    {
        $json = array();
        if (!empty($id)) {
            $slide = $this->slider->find($id);
            if (!empty($slide)) {
                $slides['title'] = $slide->title;
                $slides['id'] = $slide->id;
                $slides['style'] = $slide->style;
                $slides['slides'] = !empty($slide->metaValue('slider')['meta_value']) ? unserialize($slide->metaValue('slider')['meta_value']) : '';
                $json['slides'] = $slides;
                $json['type'] = 'success';
                return $json;
            }  else {
                $json['slides'] = [];
                $json['type'] = 'error';
                return $json;
            }
        } else {
            $json['type'] = 'error';
            return $json;
        }
    }

    /**
     * Get sliders.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSearchFormSetting($id)
    {
        $json = array();
        if (!empty($id)) {
            $slide = $this->slider->find($id);
            $slides = !empty($slide->metaValue('slider')['meta_value']) ? unserialize($slide->metaValue('slider')['meta_value']) : '';
            $json['search_form'] = !empty($slides) && !empty($slides['slide']['enable_search']) ? $slides['slide']['enable_search'] : '';
            $json['type'] = 'success';
            return $json;
        } else {
            $json['type'] = 'error';
            return $json;
        }
    }

    /**
     * Get sliders.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStoredType($id)
    {
        $json = array();
        if (!empty($id)) {
            $slide = $this->slider->find($id);
            // $slides = !empty($slide->metaValue('slider')['meta_value']) ? unserialize($slide->metaValue('slider')['meta_value']) : '';
            $json['style'] = !empty($slide) ? $slide->style : '';
            $json['type'] = 'success';
            return $json;
        } else {
            $json['type'] = 'error';
            return $json;
        }
    }
}
