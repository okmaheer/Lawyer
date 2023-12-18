<div class="dc-settingscontent dc-tabsinfo la-profilephoto">
    @if (!empty($default_location['location_img']))
        <upload-media
        :title="'{{ trans('lang.location_img') }}'"
        :img="'{{ $default_location['location_img'] }}'"
        :img_id="'location_img'"
        :img_name="'location_img'"
        :img_ref="'location_img'"
        :img_hidden_name="'location_img'"
        :img_hidden_id="'hidden_location_img'"
        :existed_img="'{{$default_location['location_img']}}'"
        :url="'{{ url("media/upload-temp-image/users/location_img/profile_img") }}'"
        :existing_img_url="'{{ url('uploads/users/'.Auth::user()->id.'/'.$default_location['location_img'].'') }}'"
        :size = "'{{ Helper::getImageDetail( $default_location['location_img'], 'size', 'uploads/users/'.Auth::user()->id) }}'"
        :existing_img_name = "'{{ Helper::getImageDetail( $default_location['location_img'], 'name', 'uploads/users/'.Auth::user()->id) }}'"
        >
        </upload-media>
    @else
        <div class = "dc-formtheme dc-userform">
            <upload-media
            :title="'{{ trans('lang.location_img') }}'"
            :img="'location_img'"
            :img_id="'location_img'"
            :img_name="'location_img'"
            :img_ref="'location_img'"
            :img_hidden_name="'location_img'"
            :img_hidden_id="'hidden_location_img'"
            :url="'{{ url("media/upload-temp-image/users/location_img/profile_img") }}'"
            >
            </upload-media>
        </div>
    @endif
</div>

