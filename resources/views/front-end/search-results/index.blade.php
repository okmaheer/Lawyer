@extends('front-end.master')
@section('title'){{ clean($search_list_meta_title) }} @stop
@section('description', clean($search_list_meta_desc))
@section('content')
@include('includes.pre-loader')
    {!! Helper::displayBreadcrumbs('searchResults') !!}
    <div class="dc-main-section">
        <div class="container" id="user-profile">
            <div class="dc-preloader-section" v-if="loading" v-cloak>
                <div class="dc-preloader-holder">
                    <div class="dc-loader"></div>
                </div>
            </div>
            <div class="row">
                <div id="dc-twocolumns" class="dc-twocolumns dc-haslayout">
                    @php $columns = 'col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12'; @endphp
                    @if ($display_sidebar == 'true')
                        @php $columns = 'col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-9'; @endphp
                    @endif
                    <div class="{{ $columns }} float-left">
                        <div class="dc-searchresult-holder">
                            <div class="dc-searchresult-head">
                                <div class="dc-title"><h4>{{{ clean($total_records) }}} {{trans('lang.matches_found') }} </h4></div>
                                <div class="dc-rightarea">
                                    <div class="dc-select">
                                        <select data-placeholder="{{ trans('lang.sort_by') }}" name="sort_by" v-model="sort_by" v-on:change="resultSortBy('sort_by', sort_by)">
                                            <option value="null">{{ trans('lang.sort_by') }}</option>
                                            <option value="id">{{ trans('lang.id') }}</option>
                                            <option value="name">{{ trans('lang.name') }}</option>
                                            <option value="date">{{ trans('lang.date') }}</option>
                                        </select>
                                    </div>
                                    <div class="dc-select">
                                        <select name="order" class="order" v-model="order" v-on:change="resultSortBy('order_by', order)">
                                            <option value="asc">{{ trans('lang.ascending') }}</option>
                                            <option value="desc">{{ trans('lang.descending') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="dc-searchresult-grid dc-searchresult-list dc-searchvlistvtwo la-searchvlistvtwo">
                                @if (!empty($users) && $users->count() > 0)
                                    @foreach ($users as $key => $user)
                                        @php 
                                            $user_obj = App\User::find($user->id); 
                                            $avg_rating = \App\Feedback::where('user_id', $user_obj->id)->pluck('avg_rating')->first();
                                            $stars  = $avg_rating != 0 ? $avg_rating/5*100 : 0;
                                            $specialities = $user_obj->services->count() > 0 ? DB::table('user_service')->select('speciality')
                                                        ->where('user_id', $user_obj->id)->groupBy('speciality')->get()->pluck('speciality')->random(1)->toArray() : '';
                                            $day_list = Helper::getAppointmentDays();
                                            $current_package = Helper::getCurrentPackage($user_obj);
                                            $featured = !empty($current_package) && !empty($current_package['featured']) ? $current_package['featured'] : 'false';
                                        @endphp
                                        <div class="dc-docpostholder">
                                            <div class="dc-docpostcontent">
                                                <div class="dc-searchvtwo">
                                                    <figure class="dc-docpostimg">
                                                        <img src="{{ asset(Helper::getImage('uploads/users/'.$user_obj->id, $user_obj->profile->avatar, 'small-', 'user.jpg')) }}" alt="{{ trans('lang.img_desc') }}">
                                                        @if ($featured == 'true')
                                                            <figcaption>
                                                                <span class="dc-featuredtag"><i class="fa fa-bolt"></i></span>
                                                            </figcaption>
                                                        @endif
                                                    </figure>
                                                    <div class="dc-title">
                                                        @if (!empty($specialities))
                                                            @foreach ($specialities as $key => $user_speciality)
                                                                @php $speciality = Helper::getSpecialityByID($user_speciality); @endphp
                                                                @if (!empty($speciality))
                                                                    <a href="{{ url('/search-results?speciality='.clean($speciality->slug)) }}" class="dc-docstatus">{{ html_entity_decode(clean($speciality->title)) }}</a>  
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                        <h3>
                                                            <a href="{{ route('userProfile', ['slug' => clean($user_obj->slug)]) }}">
                                                                {{ !empty($user_obj->profile->gender_title) ? Helper::getDoctorArray(html_entity_decode(clean($user_obj->profile->gender_title))) : '' }}
                                                                {{Helper::getUsername($user->id)}} 
                                                            </a>
                                                            {{ Helper::verifyUser(clean($user_obj->id)) }} {{ Helper::verifyMedical(clean($user_obj->id)) }}
                                                        </h3>
                                                        <ul class="dc-docinfo">
                                                            <li><em>{{ html_entity_decode(clean($user_obj->profile->sub_heading)) }}</em></li>
                                                            @if (Helper::getRoleTypeByUserID($user_obj->id) == 'doctor')
                                                                <li>
                                                                    <span class="dc-stars">
                                                                        <span style="width: {{ clean($stars) }}%;"></span>
                                                                    </span>
                                                                    <em>{{ html_entity_decode(clean($user_obj->feedbacks->count())) }} {{ trans('lang.feedbacks') }}</em>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                    @if (!empty($user_obj->services))
                                                        <div class="dc-tags">
                                                            <ul>
                                                                @foreach ($user_obj->services as $key => $service)
                                                                    @if ($key <= 4)
                                                                        <li>
                                                                            <a href="javascript:void(0);">{{{html_entity_decode(clean($service->title))}}}</a>
                                                                        </li> 
                                                                    @else
                                                                        <li style="display:none">
                                                                            <a href="javascript:void(0);">{{{html_entity_decode(clean($service->title))}}}</a>
                                                                        </li>    
                                                                    @endif
                                                                @endforeach
                                                                @if ($user_obj->services->count() >= 6)
                                                                    <li class="dc-viewall-services">
                                                                        <a href="javascript:;" id="view-service-{{clean($user_obj->id)}}" class="dc-tagviewall" v-on:click="displayServices('view-service-{{clean($user_obj->id)}}')">{{ trans('lang.view_all') }}</a>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="dc-doclocation dc-doclocationvtwo">
                                                    @if (!empty($user_obj->location->title))
                                                        <span><i class="ti-direction-alt"></i> {{ html_entity_decode(clean($user_obj->location->title)) ?? '' }}</span>
                                                    @endif
                                                    @if (!empty($user_obj->profile->available_days) && !empty($day_list))
                                                        @php $last_day = end($day_list); @endphp
                                                        <span>
                                                            <i class="ti-calendar"></i>
                                                            @foreach ($day_list as $key => $day)
                                                                @if (!in_array($key, unserialize($user_obj->profile->available_days)))
                                                                    <em class="dc-dayon">{{ html_entity_decode(clean($day['title'])) }}</em> 
                                                                    @if ($day['title'] != $last_day['title']), @endif
                                                                @else
                                                                    {{ html_entity_decode(clean($day['title'])) }}@if ($day['title'] != $last_day['title']), @endif
                                                                @endif
                                                            @endforeach
                                                        </span>
                                                    @endif
                                                    @if (Helper::getRoleTypeByUserID($user_obj->id) == 'doctor')
                                                        <span><i class="ti-thumb-up"></i> {{ empty($user_obj->profile->votes) ? 0 : clean($user_obj->profile->votes) }} {{trans('lang.votes') }}</span>
                                                        <span><i class="ti-wallet"></i> {{ trans('lang.starting_from') }} {{ !empty($symbol['symbol']) ? html_entity_decode(clean($symbol['symbol'])) : '$' }}{{ !empty($user_obj->profile->starting_price) ? html_entity_decode(clean($user_obj->profile->starting_price)) : 0 }}</span>
                                                    @elseif (Helper::getRoleTypeByUserID($user_obj->id) == 'hospital')
                                                        <span><i class="ti-thumb-up"></i>{{ trans('lang.doctors_onboard') }}: {{ clean($user_obj->approvedTeams()->count()) }}</span>
                                                    @endif
                                                    @if (!empty($user_obj->profile->available_days))
                                                        <span>
                                                            <i class="ti-clipboard"></i>
                                                            <em class="dc-available">
                                                                @if (!empty($user_obj->profile->working_time) && $type == 'hospital')
                                                                   {{{ $user_obj->profile->working_time == '24_hours' ? trans('lang.24_hours') : html_entity_decode(clean($user_obj->profile->working_time)) }}}
                                                                @else 
                                                                   {{ in_array(strtolower(Carbon\Carbon::now()->format('D')), unserialize($user_obj->profile->available_days))
                                                                    ? trans('lang.available_today') : trans('lang.not_available_today') }}
                                                                @endif
                                                            </em>
                                                        </span>
                                                    @endif
                                                    @php
                                                        $column = !empty($user_obj->id) && Helper::getRoleTypeByUserID($user_obj->id) == 'doctor' ? 'saved_doctors' : 'saved_hospitals'; 
                                                        $saved_user = Auth::check() && !empty(Auth::user()->profile->$column) ? unserialize(Auth::user()->profile->$column) : array();
                                                    @endphp 
                                                    <div class="dc-btnarea">
                                                        <a href="{{{url('profile/'.clean($user_obj->slug))}}}" class="dc-btn">{{ trans('lang.view_more') }}</a>
                                                        @if (!empty($saved_user) && in_array($user_obj->id, $saved_user))
                                                            <a href="javascrip:void(0);" class="dc-like dc-clicksave dc-btndisbaled">
                                                                <i class="fa fa-heart"></i>
                                                            </a>
                                                        @else
                                                            <a href="javascrip:void(0);" class="dc-like" id="doctor-{{ clean($user_obj->id) }}" @click.prevent="add_wishlist('doctor-{{ clean($user_obj->id) }}', '{{ clean($user_obj->id) }}', '{{ $column }}', '')" v-cloak>
                                                                <i class="fa fa-heart"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if ( method_exists($users,'links') )
                                        <div class="dc-pagination">
                                            {{ $users->links('pagination.custom') }}
                                        </div>
                                    @endif
                                @else
                                    @include('errors.no-record')
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($display_sidebar == 'true')
                        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 col-xl-3 float-left">
                            @include('front-end.sidebar.index')
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
