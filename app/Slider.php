<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use File;

class Slider extends Model
{

    /**
     * locations can have multiple meta
     *
     * @return relation
     */
    public function meta()
    {
        return $this->morphMany('App\Meta', 'metable');
    }
    
    /**
     * locations can have multiple meta
     *
     * @return relation
     */
    public function metaValue($meta_key)
    {
        return $this->morphMany('App\Meta', 'metable')->where('meta_key', $meta_key)->select('id', 'meta_value')->first();
    }

    /**
     * Fillable for the database
     *
     * @access protected
     * @var    array $fillable
     */
    protected $fillable = array(
        'title', 'slug', 'style'
    );

    /**
     * Protected Date
     *
     * @access protected
     * @var    array $dates
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Set slug attribute
     *
     * @param int $value page ID
     *
     * @return array
     */
    public function setSlugAttribute($value)
    {
        $temp = str_slug($value, '-');
        if (!Slider::all()->where('slug', $temp)->isEmpty()) {
            $i = 1;
            $new_slug = $temp . '-' . $i;
            while (!Slider::all()->where('slug', $new_slug)->isEmpty()) {
                $i++;
                $new_slug = $temp . '-' . $i;
            }
            $temp = $new_slug;
        }
        $this->attributes['slug'] = $temp;
    }

    /**
     * Get Meta Values form meta keys.
     *
     * @param string $request req->attr
     *
     * @return \Illuminate\Http\Response
     */
    public function saveSlider($request)
    {
        $json = array();
        if (!empty($request)) {
            $slides = $request['slide'];
            $slider = array();
            $old_path = Helper::PublicPath() . '/uploads/sliders/temp';
            $this->title = filter_var($request['title'], FILTER_SANITIZE_STRING);
            $this->slug = filter_var($request['title'], FILTER_SANITIZE_STRING);
            $this->style = filter_var($request['style'], FILTER_SANITIZE_STRING);
            $this->save();
            $new_path = Helper::PublicPath() . '/uploads/sliders/'.$this->id;
            $counter = 0;
            if (!empty($slides)) {
                if ($request['style'] == 'style1') {
                    foreach ($slides as $key => $slide) {
                        $slider['slide'][$counter]['slide_title_one'] = !empty($slide['slide_title_one']) ? $slide['slide_title_one'] : '';
                        $slider['slide'][$counter]['slide_title_two'] = !empty($slide['slide_title_two']) ? $slide['slide_title_two'] : ''; 
                        $slider['slide'][$counter]['slide_title_three'] = !empty($slide['slide_title_three']) ? $slide['slide_title_three'] : '';
                        $slider['slide'][$counter]['slide_btn_title_one'] = !empty($slide['slide_btn_title_one']) ? $slide['slide_btn_title_one'] : '';
                        $slider['slide'][$counter]['slide_btn_url_one'] = !empty($slide['slide_btn_url_one']) ? $slide['slide_btn_url_one'] : '';
                        $slider['slide'][$counter]['slide_btn_title_two'] = !empty($slide['slide_btn_title_two']) ? $slide['slide_btn_title_two'] : '';
                        $slider['slide'][$counter]['slide_btn_url_two'] = !empty($slide['slide_btn_url_two']) ? $slide['slide_btn_url_two'] : '';
                        if (!empty($slide['hidden_slide_inner_image'])) {
                            if (file_exists($old_path . '/' . $slide['hidden_slide_inner_image'])) {
                                if (!file_exists($new_path)) {
                                    File::makeDirectory($new_path, 0755, true, true);
                                }
                                $filename = time() . '-' . $slide['hidden_slide_inner_image'];
                                rename($old_path . '/' . $slide['hidden_slide_inner_image'], $new_path . '/' . $filename);
                                $slider['slide'][$counter]['hidden_slide_inner_image'] = $filename;
                            }
                        }
                        $counter++;
                    } 
                } elseif ($request['style'] == 'style2') {
                    foreach ($slides as $key => $slide) {
                        if (!empty($slide['image'])) {
                            if (file_exists($old_path . '/' . $slide['image'])) {
                                if (!file_exists($new_path)) {
                                    File::makeDirectory($new_path, 0755, true, true);
                                }
                                $filename = time() . '-' . $slide['image'];
                                rename($old_path . '/' . $slide['image'], $new_path . '/' . $filename);
                                $slider['slide']['slides'][$counter]['image'] = $filename;
                            }
                        }
                        $counter++;
                    }
                    $slider['slide']['search_title'] = !empty($request['search_title']) ? $request['search_title'] : '';
                    $slider['slide']['search_subtitle1'] = !empty($request['search_subtitle1']) ? $request['search_subtitle1'] : '';
                    $slider['slide']['search_subtitle2'] = !empty($request['search_subtitle2']) ?  $request['search_subtitle2'] : '';
                    if (!empty($request['slider_inner_image'])) {
                        if (file_exists($old_path . '/' . $request['slider_inner_image'])) {
                            if (!file_exists($new_path)) {
                                File::makeDirectory($new_path, 0755, true, true);
                            }
                            $filename = time() . '-' . $request['slider_inner_image'];
                            rename($old_path . '/' . $request['slider_inner_image'], $new_path . '/' . $filename);
                            $slider['slide']['slider_inner_image'] = $filename;
                        }
                    }
                }
            }
            if (!empty($request['slider_bg_img'])) {
                if (file_exists($old_path . '/' . $request['slider_bg_img'])) {
                    if (!file_exists($new_path)) {
                        File::makeDirectory($new_path, 0755, true, true);
                    }
                    $filename = time() . '-' . $request['slider_bg_img'];
                    rename($old_path . '/' . $request['slider_bg_img'], $new_path . '/' . $filename);
                    // $request['slider_bg_img'] = $filename;
                    $slider['slider_bg_img'] = $filename;
                }
            } 
            $new_slider = self::findOrFail($this->id);
            $meta = new Meta();
            $meta->meta_key = 'slider';
            $meta->meta_value = serialize($slider);
            $new_slider->meta()->save($meta);
            $json['type'] = 'success';
            $json['message'] = '';
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = '';
            return $json;
        }
    }

    /**
     * Get Meta Values form meta keys.
     *
     * @param string $request req->attr
     *
     * @return \Illuminate\Http\Response
     */
    public function updateSlider($request, $id)
    {
        $json = array();
        if (!empty($request)) {
            // dd($request->all());
            $slides = $request['slide'];
            $slider = array();
            $old_path = Helper::PublicPath() . '/uploads/sliders/temp';
            $slider_obj  = $this::find($id);
            $slider_obj->title = filter_var($request['title'], FILTER_SANITIZE_STRING);
            if ($slider_obj->title != $request['title']) {
                $slider_obj->slug  =  filter_var($request['title'], FILTER_SANITIZE_STRING);
            }
            $slider_obj->style = filter_var($request['style'], FILTER_SANITIZE_STRING);
            $slider_obj->save();
            $new_path = Helper::PublicPath() . '/uploads/sliders/'.$slider_obj->id;
            $counter = 0;
            if (!empty($slides)) {
                if ($request['style'] == 'style1') {
                    foreach ($slides as $key => $slide) {
                        $slider['slide'][$counter]['slide_title_one'] = !empty($slide['slide_title_one']) ? $slide['slide_title_one'] : '';
                        $slider['slide'][$counter]['slide_title_two'] = !empty($slide['slide_title_two']) ? $slide['slide_title_two'] : ''; 
                        $slider['slide'][$counter]['slide_title_three'] = !empty($slide['slide_title_three']) ? $slide['slide_title_three'] : '';
                        $slider['slide'][$counter]['slide_btn_title_one'] = !empty($slide['slide_btn_title_one']) ? $slide['slide_btn_title_one'] : '';
                        $slider['slide'][$counter]['slide_btn_url_one'] = !empty($slide['slide_btn_url_one']) ? $slide['slide_btn_url_one'] : '';
                        $slider['slide'][$counter]['slide_btn_title_two'] = !empty($slide['slide_btn_title_two']) ? $slide['slide_btn_title_two'] : '';
                        $slider['slide'][$counter]['slide_btn_url_two'] = !empty($slide['slide_btn_url_two']) ? $slide['slide_btn_url_two'] : '';
                        if (!empty($slide['hidden_slide_inner_image'])) {
                            $filename = $slide['hidden_slide_inner_image'];
                            if (file_exists($old_path . '/' . $slide['hidden_slide_inner_image'])) {
                                if (!file_exists($new_path)) {
                                    File::makeDirectory($new_path, 0755, true, true);
                                }
                                $filename = time() . '-' . $slide['hidden_slide_inner_image'];
                                rename($old_path . '/' . $slide['hidden_slide_inner_image'], $new_path . '/' . $filename);
                            }
                            $slider['slide'][$counter]['hidden_slide_inner_image'] = $filename;
                        }
                        $counter++;
                    } 
                } elseif ($request['style'] == 'style2') {
                    foreach ($slides as $key => $slide) {
                        if (!empty($slide['image'])) {
                            if (file_exists($old_path . '/' . $slide['image'])) {
                                if (!file_exists($new_path)) {
                                    File::makeDirectory($new_path, 0755, true, true);
                                }
                                $filename = time() . '-' . $slide['image'];
                                rename($old_path . '/' . $slide['image'], $new_path . '/' . $filename);
                                $slider['slide']['slides'][$counter]['image'] = $filename;
                            } elseif (file_exists($new_path . '/' . $slide['image'])) {
                                $slider['slide']['slides'][$counter]['image'] = $slide['image'];
                            }
                        }
                        $counter++;
                    }
                    $slider['slide']['search_title'] = !empty($request['search_title']) ? $request['search_title'] : '';
                    $slider['slide']['search_subtitle1'] = !empty($request['search_subtitle1']) ? $request['search_subtitle1'] : '';
                    $slider['slide']['search_subtitle2'] = !empty($request['search_subtitle2']) ?  $request['search_subtitle2'] : '';
                    $slider['slide']['enable_search'] = !empty($request['enable_search']) ?  $request['enable_search'] : '';
                    if (!empty($request['slider_inner_image'])) {
                        if (file_exists($old_path . '/' . $request['slider_inner_image'])) {
                            if (!file_exists($new_path)) {
                                File::makeDirectory($new_path, 0755, true, true);
                            }
                            $filename = time() . '-' . $request['slider_inner_image'];
                            rename($old_path . '/' . $request['slider_inner_image'], $new_path . '/' . $filename);
                            $slider['slide']['slider_inner_image'] = $filename;
                        } elseif (file_exists($new_path . '/' . $request['slider_inner_image'])) {
                            $slider['slide']['slider_inner_image'] = $request['slider_inner_image'];
                        }
                    }
                }
            }
            if (!empty($request['slider_bg_img'])) {
                if (file_exists($old_path . '/' . $request['slider_bg_img'])) {
                    if (!file_exists($new_path)) {
                        File::makeDirectory($new_path, 0755, true, true);
                    }
                    $filename = time() . '-' . $request['slider_bg_img'];
                    rename($old_path . '/' . $request['slider_bg_img'], $new_path . '/' . $filename);
                    // $request['slider_bg_img'] = $filename;
                    $slider['slider_bg_img'] = $filename;
                } else {
                    $slider['slider_bg_img'] = $request['slider_bg_img'];
                }
            } 
            // dd($slider);
            $slider_obj->meta()->delete();
            $meta = new Meta();
            $meta->meta_key = 'slider';
            $meta->meta_value = serialize($slider);
            $slider_obj->meta()->save($meta);
            $json['type'] = 'success';
            $json['message'] = '';
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = '';
            return $json;
        }
    }
}
