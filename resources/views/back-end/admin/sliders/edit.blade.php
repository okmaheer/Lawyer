@extends('back-end.master')
@section('content')
@include('includes.pre-loader')
    <div class="dc-sliders-edit dc-sliders" id="sliders">
        @if (Session::has('message'))
            <div class="flash_msg">
                <flash_messages :message_class="'success'" :time ='5' :message="'{{{ Session::get('message') }}}'" v-cloak></flash_messages>
            </div>
        @elseif (Session::has('error'))
            <div class="flash_msg">
                <flash_messages :message_class="'danger'" :time ='5' :message="'{{{ Session::get('error') }}}'" v-cloak></flash_messages>
            </div>
        @endif
        <section class="dc-haslayout dc-dbsectionspace la-editcategory">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 float-left">
                    <div class="dc-dashboardbox">
                        <div class="dc-dashboardboxtitle">
                            <h2>{{{ trans('lang.update_slider') }}}</h2>
                        </div>
                        <div class="dc-dashboardboxcontent">
                            {!! Form::open(['url' => url('admin/sliders/update/'.$slider->id.''),
                                'class' =>'dc-formtheme dc-formprojectinfo dc-formcategory dc-userform', 'id' => 'sliders-form'] )
                            !!}
                                <fieldset>
                                    <div class="form-group">
                                        {!! Form::text( 'title', $slider->title, ['class' =>'form-control'.($errors->has('title') ? ' is-invalid' : ''), 'placeholder' => trans('lang.ph.title')] ) !!}
                                        @if ($errors->has('title'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('title') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <span class="dc-select dc-tabsinfo">
                                            <select name="style" class="form-control" v-model="selectedStyle">
                                                @foreach ($styles as $key => $style)
                                                    <option value="{{$key}}">{{$style}}</option>
                                                @endforeach
                                            </select>
                                        </span>
                                    </div>
                                </fieldset>
                                <div v-if="selectedStyle == 'style1'">
                                    <div class="dc-settingscontent dc-tabsinfo dc-sliderimg-holder">
                                        <div class = "dc-formtheme dc-userform">
                                            @if (!empty($slides['slider_bg_img']))
                                            <div>
                                                <upload-media
                                                :title="trans('lang.slider_background_image')"
                                                :img="'{{ $slides['slider_bg_img'] }}'"
                                                :img_id="'slider_bg_img'"
                                                :img_name="'slider_bg_img'"
                                                :img_ref="'slider_bg_img'"
                                                :img_hidden_name="'slider_bg_img'"
                                                :img_hidden_id="'hidden_slider_bg_img'"
                                                :existed_img="'{{$slides['slider_bg_img']}}'"
                                                :url="'{{ url("media/upload-temp-image/sliders/slider_bg_img") }}'"
                                                :existing_img_url="'{{ url("uploads/sliders/$slider->id/$slides[slider_bg_img]") }}'"
                                                :size = "'{{ Helper::getImageDetail( $slides['slider_bg_img'], 'size', 'uploads/sliders/'.$slider->id) }}'"
                                                :existing_img_name = "'{{ Helper::getImageDetail( $slides['slider_bg_img'], 'name', 'uploads/sliders/'.$slider->id) }}'"
                                                >
                                                </upload-media>
                                                </div>
                                            @else
                                            <div>
                                                <upload-media
                                                :title="trans('lang.slider_background_image')"
                                                :img="'slider_bg_img'"
                                                :img_id="'slider_bg_img'"
                                                :img_name="'slider_bg_img'"
                                                :img_ref="'slider_bg_img'"
                                                :img_hidden_name="'slider_bg_img'"
                                                :img_hidden_id="'hidden_slider_bg_img'"
                                                :url="'{{ url("media/upload-temp-image/sliders/slider_bg_img") }}'"
                                                >
                                                </upload-media>
                                                </div>  
                                            @endif
                                        </div>
                                    </div>
                                    <div class="dc-tabsinfo dc-addslider-holder">
                                        <slider
                                            :ph_slide_title_one="'{{ trans('lang.ph.slide_title_one') }}'"
                                            :ph_slide_title_two="'{{ trans('lang.ph.slide_title_two') }}'"
                                            :ph_slide_title_three="'{{ trans('lang.ph.slide_title_three') }}'"
                                            :ph_slide_btn_title_one="'{{ trans('lang.ph.slide_btn_title_one') }}'"
                                            :ph_slide_btn_url_one="'{{ trans('lang.ph.slide_btn_url_one') }}'"
                                            :ph_slide_btn_title_two="'{{ trans('lang.ph.slide_btn_title_two') }}'"
                                            :ph_slide_btn_url_two="'{{ trans('lang.ph.slide_btn_url_two') }}'"
                                            section_sliders='{{ json_encode($slide) }}'
                                            :slider_id="{{$slider->id}}"
                                            :component="'edit'"
                                        >
                                        </slider>
                                    </div>
                                </div>
                                <div v-if="selectedStyle == 'style2'">
                                    <div class="dc-settingscontent dc-tabsinfo dc-sliderimg-holder">
                                        <div class = "dc-formtheme dc-userform">
                                            @if (!empty($slides['slide']) && !empty($slides['slide']['slider_inner_image']))
                                                <upload-media
                                                    :title="trans('lang.slide_inner_image')"
                                                    :img="'slider_inner_image'"
                                                    :img_id="'slider_inner_image'"
                                                    :img_name="'slider_inner_image'"
                                                    :img_ref="'slider_inner_image'"
                                                    :img_hidden_name="'slider_inner_image'"
                                                    :img_hidden_id="'hidden_slider_inner_image'"
                                                    :url="'{{ url("media/upload-temp-image/sliders/slider_inner_image") }}'"
                                                    :existed_img="'{{$slides['slide']['slider_inner_image']}}'"
                                                    :existing_img_url="'{{url("uploads/sliders/$slider->id/".$slides['slide']['slider_inner_image'])}}'"
                                                    :size = "'{{ Helper::getImageDetail( $slides['slide']['slider_inner_image'], 'size', 'uploads/sliders/'.$slider->id) }}'"
                                                    :existing_img_name = "'{{ Helper::getImageDetail( $slides['slide']['slider_inner_image'], 'name', 'uploads/sliders/'.$slider->id) }}'"
                                                />
                                            @else
                                                <upload-media
                                                :title="trans('lang.slide_inner_image')"
                                                :img="'slider_inner_image'"
                                                :img_id="'slider_inner_image'"
                                                :img_name="'slider_inner_image'"
                                                :img_ref="'slider_inner_image'"
                                                :img_hidden_name="'slider_inner_image'"
                                                :img_hidden_id="'hidden_slider_inner_image'"
                                                :url="'{{ url("media/upload-temp-image/sliders/slider_inner_image") }}'"
                                                >
                                                </upload-media>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="dc-tabscontenttitle dc-addnew">
                                        <h3>{{{ trans('lang.search_form') }}}</h3>
                                        <div class="float-right">
                                            <switch_button v-model="enable_search">{{{ trans('lang.enable_disable_search_form') }}}</switch_button>
                                            <input type="hidden" :value="enable_search" name="enable_search">
                                        </div>
                                    </div>
                                    <div class="dc-slider-search" v-if="enable_search">
                                        <fieldset>
                                            <div class="form-group">
                                                {!! Form::text('search_title', $search_title, array('class' => 'form-control', 'placeholder' => trans('lang.title'))) !!}
                                            </div>
                                            <div class="form-group">
                                                {!! Form::text('search_subtitle1', $search_subtitle1, array('class' => 'form-control', 'placeholder' => trans('lang.subtitle1'))) !!}
                                            </div>
                                            <div class="form-group">
                                                {!! Form::text('search_subtitle2', $search_subtitle2, array('class' => 'form-control', 'placeholder' => trans('lang.subtitle2'))) !!}
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="dc-tabscontenttitle dc-addnew">
                                        <h3>Add Slides</h3> 
                                        <a href="javascript:void(0);" class="add-slide-btn" v-on:click="addSlide">Add New</a>
                                    </div>
                                    <ul class="dc-experienceaccordion accordion" id="slides-list">
                                        @if (!empty($slides['slide']['slides']))
                                            @foreach ( $slides['slide']['slides'] as $key => $slide)
                                                <li class="slide-inner-list" key="stored_{{$key}}" id="stored-{{$key}}">
                                                    <div class="slide-inner-list-item dc-settingscontent" id="slide-element-{{$key}}">
                                                        <div class="slide-inner-list-item dc-settingscontent">
                                                            <div id="storedslideaccordion{{$key}}" class="slide-inner-list-item dc-accordioninnertitle" data-toggle="collapse" data-target="#storedslideaccordioninner{{$key}}">
                                                                <span>slide</span>
                                                                <div class="dc-rightarea">
                                                                    <div class="dc-btnaction">
                                                                        <a href="javascript:void(0);" class="dc-editbtn" id="{{$key}}" data-toggle="collapse" aria-expanded="true">
                                                                            <i class="lnr lnr-pencil"></i>
                                                                        </a>
                                                                        <a href="javascript:void(0);" v-on:click="removeStoredSlide('stored-{{$key}}')"  class="dc-delbtn"><i class="lnr lnr-trash"></i></a></div>
                                                                    </div>
                                                                </div>
                                                            <div class="dc-collapseexp collapse hide" id="storedslideaccordioninner{{$key}}" aria-labelledby="storedslideaccordion{{$key}}" data-parent="#accordion">
                                                                <div class="dc-formtheme dc-userform">
                                                                    <fieldset>
                                                                        <upload-media
                                                                            :title="''"
                                                                            :img="'slide_image{{$key}}'"
                                                                            :img_id="'slide_image{{$key}}'"
                                                                            :img_name="'slide_image{{$key}}'"
                                                                            :img_ref="'slide_image{{$key}}'"
                                                                            :img_hidden_name="'slide[{{$key}}][image]'"
                                                                            :img_hidden_id="'hidden_slide_image{{$key}}'"
                                                                            :url="'{{ url("media/upload-temp-image/sliders/slide_image") }}{{$key}}'"
                                                                            :existed_img="'{{$slide['image']}}'"
                                                                            :existing_img_url="'{{ url("uploads/sliders/$slider->id/".$slide['image']) }}'"
                                                                            :size = "'{{ Helper::getImageDetail( $slide['image'], 'size', 'uploads/sliders/'.$slider->id) }}'"
                                                                            :existing_img_name = "'{{ Helper::getImageDetail( $slide['image'], 'name', 'uploads/sliders/'.$slider->id) }}'"
                                                                        />
                                                                    </fieldset>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif
                                        <li class="slide-inner-list" v-for="(slide, index) in slides" :key="index" ref="slidelistelement">
                                            <div class="slide-inner-list-item dc-settingscontent">
                                                <div :id="'slideaccordion['+slide.count+']'" class="slide-inner-list-item dc-accordioninnertitle" data-toggle="collapse" :data-target="'#slideaccordioninner['+slide.count+']'">
                                                    <span>slide</span>
                                                    <div class="dc-rightarea">
                                                        <div class="dc-btnaction">
                                                            <a 
                                                                href="javascript:void(0);" 
                                                                class="dc-editbtn" 
                                                                :id="'slideaccordion['+slide.count+']'" 
                                                                data-toggle="collapse" 
                                                                aria-expanded="true"
                                                            >
                                                                <i class="lnr lnr-pencil"></i>
                                                            </a>
                                                            <a 
                                                                href="javascript:void(0);" 
                                                                class="dc-delbtn" 
                                                                :id="'del-slider'+slide.count" 
                                                                @click="removeSlide(slide.count, 'del-slider'+slide.count)"
                                                            >
                                                                <i class="lnr lnr-trash"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="dc-collapseexp collapse hide" :id="'slideaccordioninner['+slide.count+']'" :aria-labelledby="'slideaccordion['+slide.count+']'" data-parent="#accordion">
                                                    <div class="dc-formtheme dc-userform">
                                                        <fieldset>
                                                            <div class="form-group">
                                                                <upload-media
                                                                    :title="''"
                                                                    :img="'slide_image'+index"
                                                                    :img_id="'slide_image'+index"
                                                                    :img_name="'slide_image'+index"
                                                                    :img_ref="'slide_image'+index"
                                                                    :img_hidden_name="'slide['+[index]+'][image]'"
                                                                    :img_hidden_id="'hidden_slide_image'+index"
                                                                    :url="'{{ url("media/upload-temp-image/sliders/slide_image") }}'+index"
                                                                />
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="dc-btnarea">
                                    {!! Form::submit(trans('lang.update'), ['class' => 'dc-btn']) !!}
                                </div>
                            {!! Form::close(); !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
@stack('backend_scripts')
<script src="{{ asset('js/vendor/bootstrap.min.js') }}"></script>
@endpush