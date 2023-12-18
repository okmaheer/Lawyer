@extends('back-end.master')
@push('backend_stylesheets')
<link href="{{ asset('css/basictable.css') }}" rel="stylesheet">
@endpush
@section('content')
@include('includes.pre-loader')
    <div class="dc-sliders" id="sliders">
        @if (Session::has('message'))
            <div class="flash_msg">
                <flash_messages :message_class="'success'" :time ='5' :message="'{{{ Session::get('message') }}}'" v-cloak></flash_messages>
            </div>
        @elseif (Session::has('error'))
            <div class="flash_msg">
                <flash_messages :message_class="'danger'" :time ='5' :message="'{{{ Session::get('error') }}}'" v-cloak></flash_messages>
            </div>
        @endif
        <section class="dc-haslayout">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-7 col-xl-6">
                    <div class="dc-dashboardbox">
                        <div class="dc-dashboardboxtitle dc-titlewithsearch dc-titlewithdel">
                            <h2>{{{ trans('lang.manage_sliders') }}}</h2>
                            <div class="dc-rightarea">
                                {!! Form::open(['url' => url('admin/sliders/search'),
                                    'method' => 'get', 'class' => 'dc-formtheme dc-formsearch'])
                                !!}
                                    <fieldset>
                                        <div class="form-group">
                                            <input type="text" name="keyword" value="{{{ !empty($_GET['keyword']) ? $_GET['keyword'] : '' }}}"
                                                class="form-control" placeholder="{{{ trans('lang.search_slider') }}}">
                                            <button type="submit" class="dc-searchgbtn"><i class="lnr lnr-magnifier"></i></button>
                                        </div>
                                    </fieldset>
                                {!! Form::close() !!}
                                <multi-delete
                                    v-cloak
                                    v-if="this.is_show"
                                    :title="'{{trans("lang.ph.delete_confirm_title")}}'"
                                    :message="'{{trans("lang.ph.sliders_del_delete_message")}}'"
                                    :url="'{{url('admin/slider/delete-checked-sliders')}}'"
                                    :redirect_url="'{{url('admin/sliders')}}'"
                                >
                                </multi-delete>
                            </div>
                        </div>
                        @if ($sliders->count() > 0)
                            <div class="dc-dashboardboxcontent dc-categoriescontentholder">
                                <table class="dc-tablecategories dc-table-responsive" id="checked-val">
                                    <thead>
                                        <tr>
                                            <th>
                                                <span class="dc-checkbox">
                                                    <input name="sliders[]" id="dc-sliders" @click="selectAll" type="checkbox">
                                                    <label for="dc-sliders"></label>
                                                </span>
                                            </th>
                                            <th>{{{ trans('lang.name') }}}</th>
                                            <th>{{{ trans('lang.slug') }}}</th>
                                            <th>{{{ trans('lang.action') }}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $counter = 0; @endphp
                                        @foreach ($sliders as $key => $slider)
                                            <tr class="del-{{{ $slider->id }}}">
                                                <td>
                                                    <span class="dc-checkbox">
                                                        <input name="sliders[]" @click="selectRecord" value="{{{ intVal(clean($slider->id)) }}}" id="wt-check-{{{ $counter }}}" type="checkbox">
                                                        <label for="wt-check-{{{ $counter }}}"></label>
                                                    </span>
                                                </td>
                                                <td>{{{ html_entity_decode(clean($slider->title)) }}}</td>
                                                <td>{{{ html_entity_decode(clean($slider->slug)) }}}</td>
                                                <td>
                                                    <div class="dc-actionbtn">
                                                        <a href="{{{ url('admin/sliders/edit') }}}/{{{ html_entity_decode(clean($slider->id)) }}}" class="dc-addinfo dc-skillsaddinfo">
                                                            <i class="lnr lnr-pencil"></i>
                                                        </a>
                                                        <delete 
                                                            :title="'{{trans("lang.ph_delete_confirm_title")}}'" 
                                                            :id="'{{ intVal(clean($slider->id)) }}'" 
                                                            :message="'{{trans("lang.slider_del_msg")}}'" 
                                                            :url="'{{url('admin/sliders/delete')}}'" 
                                                            :redirect_url="'{{url('admin/sliders')}}'"
                                                        >
                                                        </delete>
                                                    </div>
                                                </td>
                                            </tr>
                                            @php $counter++; @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                                @if ( method_exists($sliders,'links') )
                                    {{ $sliders->links('pagination.custom') }}
                                @endif
                            </div>
                        @else
                            @include('errors.no-record')
                        @endif
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-5 col-xl-6 dc-responsive-mt mt-lg-0 mt-xl-0">
                    <div class="dc-dashboardbox dc-offered-holder">
                        <div class="dc-dashboardboxtitle">
                            <h2>{{{ trans('lang.add_new_slider') }}}</h2>
                        </div>
                        <div class="dc-dashboardboxcontent">
                            {!! Form::open([
                                'url' => url('admin/slider/store'),
                                'class' =>'dc-formtheme dc-formprojectinfo dc-formcategory dc-userform', 'id'=> 'sliders-form'
                                ])
                            !!}
                                <fieldset>
                                    <div class="form-group">
                                        {!! Form::text( 'title', null, ['class' =>'form-control'.($errors->has('title') ? ' is-invalid' : ''), 'placeholder' => trans('lang.ph.title')] ) !!}
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
                                            @if (!empty($slider_bg_image))
                                                <upload-media
                                                :title="trans('lang.slider_background_image')"
                                                :img="'{{ $slider_bg_image }}'"
                                                :img_id="'slider_bg_img'"
                                                :img_name="'slider_bg_img'"
                                                :img_ref="'slider_bg_img'"
                                                :img_hidden_name="'slider_bg_img'"
                                                :img_hidden_id="'hidden_slider_bg_img'"
                                                :existed_img="'{{$slider_bg_image}}'"
                                                :url="'{{ url("media/upload-temp-image/sliders/slider_bg_img") }}'"
                                                :existing_img_url="'{{ url("uploads/sliders/$slider->id/$slider_bg_image") }}'"
                                                :size = "'{{ Helper::getImageDetail( $slider_bg_image, 'size', 'uploads/sliders/home') }}'"
                                                :existing_img_name = "'{{ Helper::getImageDetail( $slider_bg_image, 'name', 'uploads/sliders/home') }}'"
                                                >
                                                </upload-media>
                                            @else
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
                                        >
                                        </slider>
                                    </div>
                                </div>
                                <div v-if="selectedStyle == 'style2'">
                                    <div class="dc-settingscontent dc-tabsinfo dc-sliderimg-holder">
                                        <div class = "dc-formtheme dc-userform">
                                            <upload-media
                                                :title="'{{ trans('lang.slide_inner_image') }}'"
                                                :img="'slider_inner_image'"
                                                :img_id="'slider_inner_image'"
                                                :img_name="'slider_inner_image'"
                                                :img_ref="'slider_inner_image'"
                                                :img_hidden_name="'slider_inner_image'"
                                                :img_hidden_id="'hidden_slider_inner_image'"
                                                :url="'{{ url("media/upload-temp-image/sliders/slider_inner_image") }}'"
                                            />
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
                                                {!! Form::text('search_title', null, array('class' => 'form-control', 'placeholder' => trans('lang.title'))) !!}
                                            </div>
                                            <div class="form-group">
                                                {!! Form::text('search_subtitle1', null, array('class' => 'form-control', 'placeholder' => trans('lang.subtitle1'))) !!}
                                            </div>
                                            <div class="form-group">
                                                {!! Form::text('search_subtitle2', null, array('class' => 'form-control', 'placeholder' => trans('lang.subtitle2'))) !!}
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="dc-tabscontenttitle dc-addnew">
                                        <h3>Add Slides</h3> 
                                        <a href="javascript:void(0);" class="add-slide-btn" v-on:click="addSlide">Add New</a>
                                    </div>
                                    <ul class="dc-experienceaccordion accordion" id="slides-list">
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
                                     {{-- <div class="dc-settingscontent dc-tabsinfo dc-sliderimg-holder" v-for="(slide, index) in slides" v-cloak>
                                        <div class="dc-formtheme dc-userform">
                                            <upload-media
                                            :title="''"
                                            :img="'slider_bg_img'+index"
                                            :img_id="'slider_bg_img'+index"
                                            :img_name="'slider_bg_img'+index"
                                            :img_ref="'slider_bg_img'+index"
                                            :img_hidden_name="'slide['+[index]+'][image]'"
                                            :img_hidden_id="'hidden_slider_bg_img'+index"
                                            :url="'{{ url("media/upload-temp-image/sliders/slider_bg_img") }}'+index"
                                            >
                                            </upload-media>
                                            <a href="javascript:void(0);" class="remove-slide-btn" v-on:click="removeSlide(index)"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </div> --}}
                                </div>
                                <div class="dc-btnarea">
                                    {!! Form::submit(trans('lang.add_slider'), ['class' => 'dc-btn']) !!}
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
<script src="{{ asset('js/jquery.basictable.min.js') }}"></script>
<script src="{{ asset('js/vendor/bootstrap.min.js') }}"></script>
<script type="text/javascript">
    jQuery('.dc-table-responsive').basictable({
            breakpoint: 767,
    });
</script>
@endpush