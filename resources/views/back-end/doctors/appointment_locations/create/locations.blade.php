<div class="dc-haslayout">
    <div class="dc-dashboardbox">
        <div class="dc-dashboardboxtitle">
            <h2>{{ trans('lang.appointment_locations') }}</h2>
        </div>
        <div class="dc-dashboardboxcontent dc-clinicloc-holder">
            <div class="dc-tabscontenttitle">
                <h3>{{ trans('lang.avaiable_locations') }}</h3>
            </div>
            <div class="dc-content-holder">
                @if (!empty($doctor_info) && $doctor_info->count() > 0)
                    @foreach ($doctor_info as $key => $info)
                        @php
                            $team = \App\Team::findOrFail($info->id);
                            $slots = unserialize($team->slots);
                            $appointment_days = !empty($slots['days']) ? $slots['days'] : array();
                            if ($team->user_id == $team->doctor_id && !empty($default_location) && !empty($default_location['location_img'])) {
                                $location_image =  Helper::getImage('uploads/users/'.$team->doctor_id, $default_location['location_img'], 'small-', 'user.jpg');
                            } else {
                                $location_image =  Helper::getImage('uploads/users/'.$team->hospital->id, $team->hospital->profile->avatar, 'small-', 'user.jpg');
                            }
                        @endphp
                        <div class="dc-clinics">
                            <div>
                                <figure class="dc-clinicsimg">
                                    <img src="{{ asset($location_image) }}" alt="{{ trans('lang.img_desc') }}">
                                </figure>
                            </div>
                            <div class="dc-clinics-content">
                                <div class="dc-clinics-title">
                                    @if ($team->user_id == $team->doctor_id && !empty($default_location) && !empty($default_location['location_title']))
                                        <a href="javascript:void(0);">{{ trans('lang.private_clinic') }}</a>
                                        <h4>{{ $default_location['location_title'] }}</h4>
                                    @else 
                                        <a href="javascript:void(0);">{{ $team->status }}</a>
                                        <h4>{{ Helper::getUserName($team->hospital->id) }} {{ Helper::verifyUser(intVal(clean($team->hospital->id))) }}</h4>
                                    @endif
                                    <span>
                                        @if (!empty($appointment_days))
                                            <span>
                                                @foreach (Helper::getAppointmentDays() as $key => $day)
                                                    @if (!in_array($key, $appointment_days))
                                                       <em class="dc-dayon">{{ html_entity_decode(clean($day['title'])) }}</em>
                                                    @else
                                                       {{ html_entity_decode(clean($day['title'])) }},
                                                    @endif
                                                @endforeach
                                            </span>
                                        @endif
                                    </span>
                                </div>
                                <div class="dc-btnarea">
                                    <a href="{{ route('editLocation', ['id' => intVal(clean($team->id))]) }}" class="dc-btn dc-btn-sm">
                                        {{ trans('lang.view_details') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if ( method_exists($doctor_info,'links') )
                        <div class="dc-categoriescontentholder">
                            {{ $doctor_info->links('pagination.custom') }}
                        </div>
                    @endif
                @else
                    @include('errors.no-record')
                @endif
            </div>
        </div>
    </div>
</div>
