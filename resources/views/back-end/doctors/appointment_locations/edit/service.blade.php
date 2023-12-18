<div class="dc-haslayout">
    {!! Form::open(['url' => '', 'class' =>'', 'id' =>'update_location_service', '@submit.prevent'=>'updateLocationServices("'.intVal(clean($id)).'")'])!!}
        <div class="dc-dashboardbox">
            <div class="dc-dashboardboxtitle">
                <h2>{{ trans('lang.providing_services') }}</h2>
            </div>
            <div class="dc-dashboardboxcontent dc-appsetting la-providing-services">
                <div class="dc-tabscontenttitle">
                    <h3>{{ trans('lang.update_services') }}</h3>
                </div>
                <div class="dc-providingservices">
                    <div id="dc-accordion" class="dc-accordion dc-accordion dc-moreservice" role="tablist" aria-multiselectable="true">
                        @foreach ($doctor_specialities as $key => $doctor_speciality)
                            @php $speciality = Helper::getSpecialityByID($doctor_speciality['speciality_id']); @endphp
                            @if (!empty($speciality))
                                <div class="dc-panel">
                                    <input type="hidden" name="services[{{$key}}][speciality_id]" value="{{{$speciality->id}}}">
                                    <div class="dc-paneltitle">
                                        <figure class="dc-titleicon">
                                            <img src="{{{asset(Helper::getImage('uploads/specialities', $speciality->image, '', 'default-speciality.png'))}}}"
                                                alt="{{ trans('lang.img_desc') }}">
                                        </figure>
                                        <span>{{{$speciality->title}}}<em>{{ $speciality->services()->count() }} {{ trans('lang.services') }}</em></span>
                                    </div>
                                    @if (!empty($doctor_speciality['services']))
                                        <div class="dc-panelcontent dc-moreservice-content">
                                            <div class="dc-subtitle">
                                                <h4>{{ trans('lang.services') }}:</h4>
                                            </div>
                                            <div class="dc-checkbox-holder">
                                                @foreach ($doctor_speciality['services'] as $service_key => $services)
                                                    @php 
                                                        $service = Helper::getServiceByID($services['service']); 
                                                        $checked = !empty($location_services) && !empty($service->id) && in_array($service->id, $location_services) ? 'checked' : '';
                                                    @endphp
                                                    @if (!empty($service->id))
                                                        <span class="dc-checkbox">
                                                            <input id="dc-mo-{{{$service->id}}}" type="checkbox" name='services[{{$key}}][service][{{$service_key}}][service]' 
                                                                value="{{{$service->id}}}" {{$checked}}>
                                                            <label for="dc-mo-{{{$service->id}}}">{{{$service->title}}}</label>
                                                        </span>
                                                        <input type="hidden" name="services[{{$key}}][service][{{$service_key}}][price]" value="{{{$services['price']}}}">
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                        <div class="form-group dc-btnarea">
                            {!! Form::submit(trans('lang.btn_save'), ['class' => 'dc-btn']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
</div>