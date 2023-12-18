<div class="dc-haslayout">
    <div class="dc-dashboardbox">
        <div class="dc-dashboardboxtitle">
            <h2>{{ trans('lang.appointment_setting') }}</h2>
        </div>
        <div class="dc-dashboardboxcontent dc-doc-appointment la-doc-appointment">
            <div class="dc-tabscontenttitle">
                <h3>{{ trans('lang.add_new_location') }}</h3>
            </div>
            {!! Form::open(['url' => '', 'class' =>'dc-formtheme dc-userform dc-form-appointment', 'id'=>'appointment-location-form', 
                '@submit.prevent'=>'submitAppointmentLocation'])!!}
            <fieldset>
                @if (!empty($location_type) && array_key_exists('private_clinic', $location_type))
                    <div class="form-group">
                        <span class="dc-select">
                            <select name="location_type" v-model="location_type">
                                @foreach ($location_type as $key => $value)
                                    <option value="{{{$key}}}">{{{$value}}}</option>
                                @endforeach
                            </select>
                        </span>
                    </div>
                @endif
                <div class="form-group dc-inputwithicon" v-if="location_type == 'hospital'">
                    <search-hospital 
                        :placeholder="'{{ trans('lang.ph.search_hospital') }}'"
                        :no_record_message="'{{trans("lang.no_record_found")}}'">
                    </search-hospital>
                </div>
                <div class="form-group dc-datepicker form-group-half">
                    <a-time-picker use12Hours @change="onChangeStartTime" format="h:mm a" placeholder="{{trans('lang.select_time')}}"/>
                </div>
                <input type="hidden" id="location_start_time" name="slots[start_time]" value="">
                <input type="hidden" id="start_time_obj" value="">
                <div class="form-group form-group-half">
                    <span class="dc-select">
                        <select name="slots[duration]" v-model="duration" @change="onChangeDuration">
                            <option value="{{ trans('lang.select_duration') }}" disabled hidden>{{ trans('lang.select_duration') }}</option>
                            @foreach ($durations as $key => $value)
                                <option value="{{{$key}}}">{{{$value}}}</option>
                            @endforeach
                        </select>
                    </span>
                </div>
                <div class="form-group form-group-half">
                    <span class="dc-select">
                        <select id="number_of_slots" name="slots[number_of_slots]" class="form-control" v-model="no_of_slot"
                            @change="onChangeDuration">
                            @for ($i = 1; $i <= 30; $i++) 
                                <option value="{{ $i }}">{{ $i }} {{$i > 1 ? 'slots' : 'slot'}}</option>
                            @endfor
                        </select>
                    </span>
                </div>
                <div class="form-group form-group-half">
                    <span class="dc-select">
                        <select name="slots[intervals]" v-model="intervals" @change="onChangeDuration">
                            <option value="{{ trans('lang.select_interval') }}">{{ trans('lang.select_interval') }}</option>
                            @foreach ($intervals as $key => $value)
                                <option value="{{{$key}}}">{{{$value}}}</option>
                            @endforeach
                        </select>
                    </span>
                </div>
                <div class="form-group dc-datepicker form-group-half">
                    <input type="text" :value="end_time" name="slots[end_time]" readonly
                        placeholder="{{ trans('lang.end_time') }}">
                </div>
            </fieldset>
            @if (!empty($doctor_specialities))
                <fieldset>
                    <div id="dc-accordion" class="dc-accordion dc-accordion dc-moreservice" role="tablist" aria-multiselectable="true">
                        @foreach ($doctor_specialities as $key => $doctor_speciality)
                            @php $speciality = Helper::getSpecialityByID($doctor_speciality['speciality_id']); @endphp
                            @if (!empty($speciality))
                                <div class="dc-panel">
                                    <input type="hidden" name="services[{{$key}}][speciality_id]" value="{{{$speciality->id}}}">
                                    <div class="dc-paneltitle">
                                        <figure class="dc-titleicon">
                                            <img src="{{{asset(Helper::getImage('uploads/specialities', $speciality->image, '', 'default-speciality.png'))}}}"
                                                alt="img description">
                                        </figure>
                                        <span>{{{ html_entity_decode(clean($speciality->title)) }}}<em>{{ $speciality->services()->count() }} {{ trans('lang.services') }}</em></span>
                                    </div>
                                    <div class="dc-panelcontent dc-moreservice-content">
                                        <div class="dc-subtitle">
                                            <h4>{{ trans('lang.services') }}:</h4>
                                        </div>
                                        @if (!empty($doctor_speciality['services']))
                                            <div class="dc-checkbox-holder">
                                                @foreach ($doctor_speciality['services'] as $service_key => $services)
                                                    @php $service = Helper::getServiceByID($services['service']); @endphp
                                                    @if (!empty($service))
                                                        <span class="dc-checkbox">
                                                            <input id="dc-mo-{{{ intVal(clean($service->id)) }}}" type="checkbox" name='services[{{$key}}][service][{{$service_key}}][service]' value="{{{ intVal(clean($service->id)) }}}">
                                                            <label for="dc-mo-{{{ intVal(clean($service->id)) }}}">{{{ html_entity_decode(clean($service->title)) }}}</label>
                                                        </span>
                                                    @endif
                                                    <input type="hidden" name="services[{{$key}}][service][{{$service_key}}][price]" value="{{{$services['price']}}}">
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </fieldset>
            @endif
            <fieldset class="dc-spacesholder">
                <legend>{{ trans('lang.assign_apointment_spaces') }}</legend>
                <div class="form-group form-group-half dc-radio-holder">
                    @foreach ($spaces as $key => $value)
                        <span class="dc-radio">
                            <input id="dc-spaces{{$key}}" type="radio" name="slots[appointment_spaces]"
                                value="{{{$value['value']}}}" checked="" v-model="appointment_space"
                                v-on:change="selectedSpace(appointment_space)">
                            <label for="dc-spaces{{$key}}">{{{$value['value']}}}</label>
                        </span>
                    @endforeach
                    <span class="dc-radio">
                        <input id="dc-spaces" type="radio" value="other" checked="" v-model="appointment_space"
                            v-on:change="selectedSpace(appointment_space)">
                        <label for="dc-spaces">{{ trans('lang.other') }}</label>
                    </span>
                </div>
                <div class="form-group form-group-half" v-if="is_show">
                    <input type="text" id="custom_appt_spaces" name="slots[appointment_spaces]" class="form-control"
                        placeholder="{{ trans('lang.ph.appointment_spaces') }}">
                </div>
            </fieldset>
            <fieldset class="dc-offer-holder">
                <legend>{{ trans('lang.days_offer_services') }}</legend>
                <div class="form-group dc-checkbox-holder">
                    @foreach ($days as $day_key => $day)
                        <span class="dc-checkbox">
                            <input id="day-mo-{{$day_key}}" type="checkbox" name="slots[appointment_days][]"
                                v-model="appointment_days" value="{{$day_key}}">
                            <label for="day-mo-{{$day_key}}">{{$day['title']}}</label>
                        </span>
                    @endforeach
                </div>
                <div class="form-group">
                    {!! Form::number('slots[consultation_fee]', null, ['min' => 0, 'class' => 'form-control', 'id' => 'consultation_fee','placeholder'=>trans('lang.consultancy_fee')]) !!}
                </div>
                <div class="form-group dc-btnarea">
                    {!! Form::submit(trans('lang.btn_save'), ['class' => 'dc-btn']) !!}
                </div>
            </fieldset>
            {!! Form::close() !!}
        </div>
    </div>
</div>