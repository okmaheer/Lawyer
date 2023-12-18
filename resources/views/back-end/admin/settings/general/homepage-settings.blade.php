{!! Form::open(['url' => '', 'class' =>'dc-formtheme dc-userform', 'id' =>'homepaeg-setting-form', '@submit.prevent'=>'submitHomepageSettings'])!!}
    <div class="dc-sidebar-ask-query dc-tabsinfo dc-location">
        <div class="dc-tabscontenttitle la-switch-option">
            <h3>{{{ trans('lang.homepage_settings') }}}</h3>
        </div>
        <div class="dc-settingscontent">
            <div class="dc-formtheme dc-userform">
                <fieldset>                    
                    <div class="form-group">
                        <span class="dc-select">
                        {{{ Form::select('homepage', $pages,  e($selected_homepage), ['class'=>'form-control','placeholder'=>trans('lang.select_homepage')]) }}}
                        </span>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="dc-experienceaccordion">
        <div class="dc-updatall la-updateall-holder">
            {!! Form::submit(trans('lang.btn_save'), ['class' => 'dc-btn']) !!}
        </div>
    </div>
{!! Form::close() !!}
