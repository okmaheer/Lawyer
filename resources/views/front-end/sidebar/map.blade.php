<div class="dc-widget dc-onlineoptions">
    <div class="dc-locationboxsidebar">
        <custom-map :latitude={{$latitude}} :longitude={{$longitude}} ></custom-map>
        <ul class="dc-contactinfo">
            @if (!empty($booking_numbers) && !empty($enable_call_appointment) && $enable_call_appointment == 'true')  
                @foreach ($booking_numbers as $num)     
                    <li>
                        <i class="lnr lnr-phone-handset"></i>
                        <span>
                            <a href="tel:{{$num}}">{{$num}}</a>
                        </span>
                    </li>
                @endforeach
            @endif
            <li>
                <i class="lnr lnr-location"></i>
                <address>{{$user_address}}</address>
            </li>
            <li>
                <i class="lnr lnr-clock"></i>
                <span>
                    @foreach (Helper::getAppointmentDays() as $key => $day)
                        @if (!in_array($key, $available_days))
                            <em class="dc-dayon">{{ html_entity_decode(clean($day['title'])) }}</em>
                        @else
                            {{ html_entity_decode(clean($day['title'])) }},
                        @endif
                    @endforeach
                </span>
            </li>
            <li>
                <i class="ti-clipboard"></i>
                <span>
                    <em class="dc-available">
                        {{ in_array(strtolower(Carbon\Carbon::now()->format('D')), $available_days)
                            ? trans('lang.available_today') : trans('lang.not_available_today') }}
                    </em>
                </span>
            </li>
        </ul>
        <a class="dc-btn dc-btn-lg" href="https://maps.google.com/maps?saddr=&amp;daddr={{$user_address}}" target="_blank">{{ trans('lang.get_directions') }}</a>
    </div>
</div>
