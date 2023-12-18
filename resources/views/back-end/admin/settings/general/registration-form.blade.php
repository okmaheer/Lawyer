{!! Form::open(['url' => '', 'class' =>'dc-formtheme dc-userform', 'id'
    =>'registration-setting-form', '@submit.prevent'=>'submitRegFormSettings']) !!}
    <div class="dc-tabsinfo">
        <div class="dc-tabscontenttitle">
            <h3>{{{ trans('lang.registeration_form_type') }}}</h3>
        </div>
        <div class="dc-sidepadding">
            <div class="dc-formtheme dc-userform">
                <fieldset>
                    <div class="form-group">
                        <span class="dc-select">
                            <select class="form-control" name="registration_type" v-model="reg_form_type">
                                @foreach ($registration_type as $key => $type)
                                    @php $selected = $key == $selected_registration_type ? 'selected' : ''; @endphp
                                    <option value="{{ $key }}" {{ $selected }}> {{ clean($type) }}</option>
                                @endforeach
                            </select>
                        {{-- {{{ Form::select('registration_type', $registration_type, $selected_registration_type, ['class'=>'form-control','placeholder'=>trans('lang.select_form_type')]) }}} --}}
                    </span>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="dc-tabsinfo" v-if="reg_form_type == 'single'">
        <div class="dc-tabscontenttitle">
            <h3>{{{ trans('lang.verification_type') }}}</h3>
        </div>
        <div class="dc-sidepadding">
            <div class="dc-formtheme dc-userform">
                <fieldset>
                    <div class="form-group">
                        <span class="dc-select">
                            <select class="form-control" name="verification_type">
                                <option value="">{{{ trans('lang.select_verification_type') }}}</option>
                                @foreach ($verification_types as $key => $type)
                                    @php $selected = $key == $selected_verification_type ? 'selected' : ''; @endphp
                                    <option value="{{ $key }}" {{ $selected }}> {{ clean($type) }}</option>
                                @endforeach
                            </select>
                        </span>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="dc-registrationsettings-1 dc-tabsinfo">
        <div class="dc-tabscontenttitle">
            <h3>{{{ trans('lang.registration_step1') }}}</h3>
        </div>
        <div class="dc-sidepadding">
            <div class="dc-settingscontent">
                <div class="dc-description"><p>{{ trans('lang.reg_step_1') }}</p></div>
                <div class="dc-formtheme dc-userform">
                    <fieldset>
                        <div class="form-group">
                            {!! Form::text('step1_title', e($reg_one_title), array('class' => 'form-control', 'placeholder' => trans('lang.ph.title'))) !!}
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="dc-settingscontent">
                <div class="dc-formtheme dc-userform">
                    <fieldset>
                        <div class="form-group">
                            {!! Form::textarea('step1_subtitle', e($reg_one_subtitle), array('class' => 'form-control', 'placeholder' => trans('lang.ph.desc'))) !!}
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
    <div class="dc-registrationsettings-2 dc-tabsinfo"  v-if="reg_form_type == 'multiple'">
        <div class="dc-tabscontenttitle">
            <h3>{{{ trans('lang.registration_step2') }}}</h3>
        </div>
        <div class="dc-sidepadding">
            <div class="dc-settingscontent">
                <div class="dc-description"><p>{{ trans('lang.reg_step_2') }}</p></div>
                <div class="dc-formtheme dc-userform">
                    <fieldset>
                        <div class="form-group">
                            {!! Form::text('step2_title', e($reg_two_title), array('class' => 'form-control', 'placeholder' => trans('lang.ph.title'))) !!}
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="dc-settingscontent">
                <div class="dc-formtheme dc-userform">
                    <fieldset>
                        <div class="form-group">
                            {!! Form::textarea('step2_subtitle', e($reg_two_subtitle), array('class' => 'form-control', 'placeholder' => trans('lang.ph.desc'))) !!}
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="dc-settingscontent">
                <div class="dc-formtheme dc-userform">
                    <fieldset>
                        <div class="form-group">
                            {!! Form::textarea('step2_term_note', e($term_note), array('class' => 'form-control', 'placeholder' => trans('lang.ph.term_note'))) !!}
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="dc-sidepadding">
            <div class="dc-formtheme dc-userform">
                <fieldset>
                    <div class="form-group">
                        {!! Form::text('term_page_url', $term_page_url, array('class' => 'form-control', 'placeholder' => trans('lang.ph.term_page_url'))) !!}
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="dc-registrationsettings-3 dc-tabsinfo"  v-if="reg_form_type == 'multiple'">
        <div class="dc-tabscontenttitle">
            <h3>{{{ trans('lang.registration_step3') }}}</h3>
            <div class="float-right">
                <switch_button v-model="enable_verification">{{{ trans('lang.enable_disable_verification') }}}</switch_button>
                <input type="hidden" :value="enable_verification" name="enable_verification">
            </div>
        </div>
        <div v-if="enable_verification">
            <div class="dc-sidepadding">
                <div class="dc-description"><p>{{ trans('lang.reg_step_3') }}</p></div>
                <div class="dc-formtheme dc-userform">
                    <fieldset>
                        <div class="form-group">
                            {!! Form::text('step3_title', e($reg_three_title), array('class' => 'form-control', 'placeholder' => trans('lang.ph.title'))) !!}
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="dc-sidepadding">
                <div class="dc-formtheme dc-userform">
                    <fieldset>
                        <div class="form-group">
                            {!! Form::textarea('step3_subtitle', e($reg_three_subtitle), array('class' => 'form-control', 'placeholder' => trans('lang.ph.desc'))) !!}
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="dc-sidepadding">
                <div class="dc-description"><p>{{ trans('lang.step_3_img') }}</p></div>
                <div class="dc-settingscontent">
                    @if (!empty($register_image))
                        <upload-media
                        :img="'{{ $register_image }}'"
                        :img_id="'register_image'"
                        :img_name="'register_image'"
                        :img_ref="'register_image'"
                        :img_hidden_name="'hidden_register_image'"
                        img_hidden_id="'hidden_register_image'"
                        :existed_img="'{{ $register_image }}'"
                        :url="'{{ url("media/upload-temp-image/settings/register_image") }}'"
                        :existing_img_url="'{{ url("uploads/settings/registration-form/$register_image") }}'"
                        :size = "'{{ Helper::getImageDetail( $register_image, 'size', 'uploads/settings/registration-form') }}'"
                        :existing_img_name = "'{{ Helper::getImageDetail( $register_image, 'name', 'uploads/settings/registration-form') }}'"
                        >
                        </upload-media>
                    @else
                        <upload-media
                            :img="'register_image'"
                            :img_id="'register_image'"
                            :img_name="'register_image'"
                            :img_ref="'register_image'"
                            :img_hidden_name="'hidden_register_image'"
                            img_hidden_id="'hidden_register_image'"
                            :url="'{{ url("media/upload-temp-image/settings/register_image") }}'"
                            >
                        </upload-media>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="dc-registrationsettings-4 dc-tabsinfo"  v-if="reg_form_type == 'multiple'">
        <div class="dc-tabscontenttitle">
            <h3>{{{ trans('lang.registration_step4') }}}</h3>
        </div>
        <div class="dc-sidepadding">
            <div class="dc-settingscontent">
                <div class="dc-description"><p>{{ trans('lang.reg_step_4') }}</p></div>
                <div class="dc-formtheme dc-userform">
                    <fieldset>
                        <div class="form-group">
                            {!! Form::text('step4_title', e($reg_four_title), array('class' => 'form-control', 'placeholder' => trans('lang.ph.title'))) !!}
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="dc-settingscontent">
                <div class="dc-formtheme dc-userform">
                    <fieldset>
                        <div class="form-group">
                            {!! Form::textarea('step4_subtitle', e($reg_four_subtitle), array('class' => 'form-control', 'placeholder' => trans('lang.ph.desc'))) !!}
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
    <div class="dc-experienceaccordion">
        <div class="dc-updatall la-updateall-holder">
            {!! Form::submit(trans('lang.btn_save'), ['class' => 'dc-btn']) !!}
        </div>
    </div>
{!! Form::close() !!}
