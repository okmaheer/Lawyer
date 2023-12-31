{!! Form::open(['url' => '', 'class' =>'dc-formtheme dc-userform', 'id' =>'booking-setting-form', '@submit.prevent'=>'submitBookingSettings'])!!}
    <div class="dc-socialiconsetting dc-tabsinfo dc-haslayout">
        <div class="dc-tabscontenttitle">
            <h3>{{{ trans('lang.online_bookings') }}}</h3>
        </div>
        <div class="dc-sidepadding">
            <div class="dc-settingscontent">
                <div class="dc-description">
                    <p>{{{ trans('lang.enable_disable_online_booking') }}}</p>
                </div>
                <ul class="dc-accountinfo">
                    <li>
                        <switch_button v-model="enable_booking">
                            <span>{{{ trans('lang.enable_online_booking') }}}</span>
                        </switch_button>
                        <input type="hidden" :value="enable_booking" name="enable_booking">
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="dc-socialiconsetting dc-tabsinfo dc-haslayout">
        <div class="dc-tabscontenttitle">
            <h3>{{{ trans('lang.verification') }}}</h3>
        </div>
        <div class="dc-sidepadding">
            <div class="dc-settingscontent">
                <div class="dc-description">
                    <p>{{{ trans('lang.enable_disable_booking_verification') }}}</p>
                </div>
                <ul class="dc-accountinfo">
                    <li>
                        <switch_button v-model="enable_booking_verification">
                            <span>{{{ trans('lang.enable_booking_verification') }}}</span>
                        </switch_button>
                        <input type="hidden" :value="enable_booking_verification" name="enable_booking_verification">
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="dc-socialiconsetting dc-tabsinfo dc-haslayout">
        <div class="dc-tabscontenttitle">
            <h3>{{{ trans('lang.appointment_on_call') }}}</h3>
        </div>
        <div class="dc-sidepadding">
            <div class="dc-settingscontent">
                <div class="dc-description">
                    <p>{{{ trans('lang.appointment_on_call_note') }}}</p>
                </div>
                <ul class="dc-accountinfo">
                    <li>
                        <switch_button v-model="enable_call_appointment">
                            <span>{{{ trans('lang.enable_disable_call_appointment') }}}</span>
                        </switch_button>
                        <input type="hidden" :value="enable_call_appointment" name="enable_call_appointment">
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="dc-socialiconsetting dc-tabsinfo dc-haslayout">
        <div class="dc-tabscontenttitle">
            <h3>{{{ trans('lang.private_clinic') }}}</h3>
        </div>
        <div class="dc-sidepadding">
            <div class="dc-settingscontent">
                <div class="dc-description">
                    <p>{{{ trans('lang.enable_disable_booking_private_clinic') }}}</p>
                </div>
                <ul class="dc-accountinfo">
                    <li>
                        <switch_button v-model="enable_booking_private_clinic">
                            <span>{{{ trans('lang.enable_booking_private_clinic') }}}</span>
                        </switch_button>
                        <input type="hidden" :value="enable_booking_private_clinic" name="enable_booking_private_clinic">
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="dc-experienceaccordion">
        <div class="dc-updatall la-updateall-holder">
            {!! Form::submit(trans('lang.btn_save'), ['class' => 'dc-btn']) !!}
        </div>
    </div>
{!! Form::close() !!}
