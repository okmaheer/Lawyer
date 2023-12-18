{!! Form::open(['url' => '', 'class' =>'dc-formtheme dc-userform', 'id' =>'menu-setting-form', '@submit.prevent'=>'submitMenuSettings'])!!}
    <div class="dc-sidebar-ask-query dc-tabsinfo dc-menucolor">
        <div class="dc-tabscontenttitle la-switch-option">
            <h3>{{{ trans('lang.menu_settings') }}}</h3>
        </div>
        <div class="dc-settingscontent">
            <div class="form-group la-color-picker form-group-half" v-if="show_menu_color">
                <h6>{{ trans('lang.profile_menu_color') }}</h6>
                <verte v-model="color" model="hex"></verte>
                <input type="hidden" name="menu[color]" :value="color">
            </div>
            <div class="form-group la-color-picker form-group-half" v-if="show_menu_color">
                <h6>{{ trans('lang.navbar_color') }}</h6>
                <verte v-model="menu_color" model="hex"></verte>
                <input type="hidden" name="menu[menu_color]" :value="menu_color">
            </div>
            <div class="form-group la-color-picker form-group-half" v-if="show_menu_color">
                <h6>{{ trans('lang.navbar_hover_color') }}</h6>
                <verte v-model="menu_hover_color" model="hex"></verte>
                <input type="hidden" name="menu[menu_hover_color]" :value="menu_hover_color">
            </div>
        </div>
        {{-- Pages Order --}}
        <div class="dc-tabscontenttitle">
            <h3>{{{ trans('lang.pages_order') }}}</h3>
        </div>
        <page-order></page-order>
        {{--  Inner Pages Order --}}
        <div class="dc-tabscontenttitle">
            <h3>{{{ trans('lang.inner_pages_order') }}}</h3>
        </div>
        <inner-page-order></inner-page-order>
        {{-- Custom Link --}}
        <div class="dc-tabscontenttitle">
            <h3>{{{ trans('lang.custom_links') }}}</h3>
        </div>
        <custom-link></custom-link>
    </div>
    <div class="dc-experienceaccordion">
        <div class="dc-updatall la-updateall-holder">
            {!! Form::submit(trans('lang.btn_save'), ['class' => 'dc-btn']) !!}
        </div>
    </div>
{!! Form::close() !!}
