<div class="dc-yourdetails dc-tabsinfo">
    <div class="dc-tabscontenttitle">
        <h3>{{ trans('lang.your_details') }} </h3>
    </div>
    <div class="dc-formtheme dc-userform">
        <fieldset>
            <div class="form-group form-group-half">
                <span class="dc-select">
                    {!! Form::select('base_name', Helper::getDoctorArray(), $gender_title, array()) !!}
                </span>
            </div>
            <div class="form-group form-group-half">
                {!! Form::text( 'subheading', e($sub_heading), ['class' =>'form-control', 'placeholder' => trans('lang.ph.sub_heading_optional')] ) !!}
            </div>
            <div class="form-group form-group-half">
                {!! Form::text( 'first_name', e(Auth::user()->first_name), ['class' =>'form-control', 'placeholder' => trans('lang.ph.first_name')] ) !!}
            </div>
            <div class="form-group form-group-half">
                {!! Form::text( 'last_name', e(Auth::user()->last_name), ['class' =>'form-control', 'placeholder' => trans('lang.ph.last_name')] ) !!}
            </div>
            <div class="form-group form-group-half">
                {!! Form::number( 'starting_price', e($starting_price) ?? '', ['min' => 0, 'class' =>'form-control', 'placeholder' => trans('lang.starting_price')] ) !!}
            </div>
            <div class="form-group form-group-half">
                {!! Form::number( 'personal_mob_num', e($personal_mob_num) ?? '', ['class' =>'form-control', 'placeholder' => trans('lang.personal_mob_num')] ) !!}
            </div>
            <div class="form-group">
                {!! Form::text( 'short_desc', e($short_desc), ['class' =>'form-control', 'placeholder' => trans('lang.ph.short_description')] ) !!}
            </div>
        </fieldset>
    </div>
</div>
@if (!empty($booking_settings) && !empty($booking_settings['enable_call_appointment']) && $booking_settings['enable_call_appointment'] == 'true' )
    <div class="dc-yourdetails dc-tabsinfo">
        <div class="dc-tabscontenttitle">
            <h3>{{ trans('lang.booking_phone_numbers') }} </h3>
        </div>
        <booking-num></booking-num>
    </div>
@endif