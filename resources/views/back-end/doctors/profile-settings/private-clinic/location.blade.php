{!! Form::open(['url' => '', 'class' =>'dc-formtheme', 'id' =>'submit-default-location', '@submit.prevent'=>'submitDefaultLocation()'])!!}
<div class="dc-tabscontenttitle">
    <h3>{{ trans('lang.default_loc') }}</h3>
</div>
<div class="dc-sidepadding dc-tabsinfo">
    <div class="dc-formtheme dc-userform">
        <fieldset>
            @if (!empty($booking_settings) && !empty($booking_settings['enable_booking_private_clinic']) && $booking_settings['enable_booking_private_clinic'] == 'true') 
                <div class="form-group">
                    {!! Form::text( 'location_title', e($location_title), ['class' =>'form-control', 'placeholder' => trans('lang.location_title')] ) !!}
                </div>
            @endif
            <div class="dc-tabsinfo">
                <div class="form-group form-group-half">
                    <span class="dc-select">
                        {!! Form::select('location', $locations, e(Auth::user()->location_id) , array('class' => '', 'placeholder' => trans('lang.select_locations'))) !!}
                    </span>
                </div>
                <div class="form-group form-group-half">
                    {!! Form::text( 'address', e($address), ['id'=>"pac-input", 'class' =>'form-control', 'placeholder' => trans('lang.your_address')] ) !!}
                </div>
                <div class="form-group dc-formmap">
                    @include('includes.map')
                </div>
                <div class="form-group form-group-half">
                    {!! Form::text( 'longitude', e($longitude), ['id'=>"lng-input", 'class' =>'form-control', 'placeholder' => trans('lang.enter_longitude')]) !!}
                </div>
                <div class="form-group form-group-half">
                    {!! Form::text( 'latitude', e($latitude), ['id'=>"lat-input", 'class' =>'form-control', 'placeholder' => trans('lang.enter_latitude')]) !!}
                </div>
            </div>
        </fieldset>
        @if (!empty($booking_settings) && $booking_settings['enable_booking_private_clinic'] == 'true') 
            @include('back-end.doctors.profile-settings.private-clinic.media')
        @endif
    </div>
</div>
<div class="dc-experienceaccordion">
    <div class="dc-updatall la-updateall-holder">
        {!! Form::submit(trans('lang.btn_save'), ['class' => 'dc-btn']) !!}
    </div>
</div>
{!! Form::close() !!}