@extends('back-end.master')
@push('backend_stylesheets')
    <link rel="stylesheet" href="{{ asset('css/chosen.css') }}">
@endpush
@section('content')
<div class="row" id="prescription">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">		
        <div class="dc-haslayout dc-prescription-wrap dc-dashboardbox dc-dashboardtabsholder">
            <div class="dc-dashboardboxtitle">
                <h2>{{ trans('lang.generate_patient_prescription') }}</h2>
            </div>
            <div class="dc-dashboardboxcontent">
                <form class="dc-prescription-form" method="post" id='dc-prescription-form' @submit.prevent='submitPrescriptionForm({{$appointment_id}})'>
                    <div class="dc-dashboardbox dc-prescriptionbox">
                        <div class="dc-title">
                            <h4>{{ trans('lang.patient_info') }}:</h4>
                        </div>
                        <div class="dc-formtheme dc-userform">
                            <fieldset>
                                <div class="form-group form-group-half">
                                    @php $saved_patient_name = $update_mode == true ? $saved_patient_detail['patient_name'] : $patient_name @endphp
                                    <input type="text" name="patient_detail[patient_name]" value="{{$saved_patient_name}}" class="form-control" placeholder="{{ trans('lang.ph_patient_name') }}">
                                </div>
                                <div class="form-group form-group-half">
                                    @php $saved_patient_phone = $update_mode == true ? $saved_patient_detail['phone'] : $appointment_mob_num @endphp
                                    <input type="text" name="patient_detail[phone]" class="form-control" placeholder="{{ trans('lang.patient_phone') }}" value="{{$saved_patient_phone}}">
                                </div>
                                <div class="form-group form-group-half">
                                    <input type="text" name="patient_detail[age]" class="form-control" placeholder="{{ trans('lang.age') }}" value={{!empty($saved_patient_detail['age']) ? $saved_patient_detail['age'] : ''}}>
                                </div>
                                <div class="form-group form-group-half">
                                    @php $saved_patient_address = $update_mode == true ? $saved_patient_detail['address'] : $appointment_user_address @endphp
                                    <input type="text" name="patient_detail[address]" class="form-control" value="{{$saved_patient_address}}" placeholder="{{ trans('lang.address') }}">
                                </div>
                                <div class="form-group form-group-half">
                                    <div class="dc-select">
                                        <select class="locations" data-placeholder="{{ trans('lang.select_country') }}" name="patient_detail[location]">
                                            <option value="">{{ trans('lang.select_country') }}</option>
                                            @php Helper::displaySearchLocationList(0, '', !empty($saved_patient_detail['location']) ? $saved_patient_detail['location'] : ''); @endphp
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-group-half">
                                    <div class="dc-radio-holder">
                                        <span class="dc-radio">
                                            <input id="dc-mo-male" type="radio" name="patient_detail[gender]" value="male" {{ !empty($saved_patient_detail['gender']) && $saved_patient_detail['gender'] == 'male' ? 'checked' : ''}}>
                                            <label for="dc-mo-male">{{ trans('lang.male') }}</label>
                                        </span>
                                        <span class="dc-radio">
                                            <input id="dc-mo-female" type="radio" name="patient_detail[gender]" value="female" {{ !empty($saved_patient_detail['gender']) && $saved_patient_detail['gender'] == 'female' ? 'checked' : ''}}>
                                            <label for="dc-mo-female">{{ trans('lang.female') }}</label>
                                        </span>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="dc-dashboardbox dc-prescriptionbox">
                        <div class="dc-title">
                            <h4>{{ trans('lang.martial_status') }}:</h4>
                        </div>
                        <div class="dc-formtheme dc-userform">
                            <div class="dc-radio-holder">
                                @foreach ($martial_statuses as $item)
                                    <span class="dc-radio">
                                        <input id="dc-mo-{{ $item['id'] }}" type="radio" name="marital_status"  value="{{ $item['id'] }}" {{ !empty($saved_prescription['martial_status_id']) && $saved_prescription['martial_status_id'] == $item['id'] ? 'checked' : ''}}>
                                        <label for="dc-mo-{{ $item['id'] }}">{{ $item['title'] }}</label>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="dc-dashboardbox dc-prescriptionbox">
                        <div class="dc-title">
                            <h4>{{ trans('lang.childhood_illness') }}:</h4>
                        </div>
                        <div class="dc-formtheme dc-userform">
                            <div class="dc-checkbox-holder">
                                @foreach ($childhood_illnesses as $item)
                                    <span class="dc-checkbox">
                                        <input id="dc-ill-{{ $item['id'] }}" type="checkbox" name="childhood_illness[]"  value="{{ $item['id'] }}" {{in_array($item['id'], $saved_childhood_illness) ? 'checked' : ''}}>
                                        <label for="dc-ill-{{ $item['id'] }}">{{ $item['title'] }}</label>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="dc-dashboardbox dc-prescriptionbox">
                        <div class="dc-title">
                            <h4>{{ trans('lang.diseases') }}:</h4>
                        </div>
                        <div class="dc-formtheme dc-userform">
                            <div class="dc-checkbox-holder">
                                @foreach ($diseases as $item)
                                    <span class="dc-checkbox">
                                        <input id="dc-dis-{{ $item['id'] }}" type="checkbox" name="diseases[]" value="{{ $item['id'] }}"  {{in_array($item['id'], $saved_diseases) ? 'checked' : ''}}>
                                        <label for="dc-dis-{{ $item['id'] }}">{{ $item['title'] }}</label>
                                    </span>
                                 @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="dc-dashboardbox dc-prescriptionbox">
                        <div class="dc-title">
                            <h4>{{ trans('lang.select_laboratory_tests') }}</h4>
                        </div>
                        <div class="dc-settingscontent">
                            <div class="dc-formtheme dc-userform">
                                <fieldset>
                                    <div class="form-group">
                                        <span class="dc-select">
                                            <select name="laboratary_tests[]" class="chosen-select" multiple data-placeholder = "{{trans('lang.select_laboratory_test')}}" >
                                                @foreach ($laboratory_tests as $item)
                                                    <option value="{{ $item['id'] }}" {{in_array($item['id'], $saved_laboratory_test) ? 'selected' : ''}}>{{ $item['title'] }}</option>
                                                @endforeach
                                            </select>
                                        </span>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <common-issue vital_signs_array='{{ json_encode($vital_signs) }}' saved_vital_sign='{{ json_encode($saved_vital_sign) }}'></common-issue>
                    <div class="dc-dashboardbox dc-prescriptionbox">
                        <div class="dc-title">
                            <h4>{{ trans('lang.medical_history') }}:</h4>
                        </div>
                        <div class="dc-formtheme dc-userform">
                            <fieldset>
                                <div class="form-group">
                                    <textarea name="medical_history" class="form-control" placeholder="{{ trans('lang.your_patient_medical_hostory') }}">{{ !empty($saved_prescription['history']) ? $saved_prescription['history'] : '' }}</textarea>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <medication-component 
                        medicine_types_array = '{{ json_encode($medicine_types) }}'
                        medicine_durations_array = '{{ json_encode($medicine_durations) }}'
                        medicine_usages_array = '{{ json_encode($medicine_usages) }}'
                        saved_medications='{{ json_encode($saved_medications) }}'
                    ></medication-component>
                    <div class="dc-updatall">
                        <i class="ti-announcement"></i>
                        <span >{{trans('lang.save_update_btn_note')}}</span>
                        <input class="dc-btn dc-update-prescription" type="submit" value="Save & Update">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('backend_scripts')
    <script src="{{ asset('js/chosen.jquery.js') }}"></script>
    <script>
        jQuery(".chosen-select").chosen();
    </script>
@endpush
