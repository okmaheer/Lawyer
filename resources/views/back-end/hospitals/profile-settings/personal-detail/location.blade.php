<div class="dc-tabscontenttitle">
    <h3>{{ trans('lang.your_loc') }}</h3>
</div>
<div class="dc-sidepadding dc-tabsinfo la-location-holder">
    <div class="dc-formtheme dc-userform">
        <fieldset>
            <div class="form-group form-group-half">
                <span class="dc-select">
                    {!! Form::select('location', $locations, e(Auth::user()->location_id) ,array('class' => '', 'placeholder' => trans('lang.ph.select_locations'))) !!}
                </span>
            </div>
            <div class="form-group form-group-half">
                {!! Form::text( 'address', e($address), ['id'=>"pac-input", 'class' =>'form-control', 'placeholder' => trans('lang.your_address')] ) !!}
            </div>
            @if (!empty($longitude) && !empty($latitude))
                <div class="form-group dc-formmap">
                    @include('includes.map')
                </div>
            @endif
            <div class="form-group form-group-half">
                {!! Form::text( 'longitude', e($longitude), ['id'=>"lng-input", 'class' =>'form-control', 'placeholder' => trans('lang.enter_longitude')]) !!}
            </div>
            <div class="form-group form-group-half">
                {!! Form::text( 'latitude', e($latitude), ['id'=>"lat-input", 'class' =>'form-control', 'placeholder' => trans('lang.enter_latitude')]) !!}
            </div>
        </fieldset>
    </div>
</div>
