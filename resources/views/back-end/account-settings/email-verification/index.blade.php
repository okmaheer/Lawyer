<div class="dc-emailnoti">
    <div class="dc-tabscontenttitle">
        <h3>{{ trans('lang.resend_email_verification') }}</h3>
    </div>
    <div class="dc-settingscontent dc-sidepadding">
        <div class="form-group dc-btnarea" v-if="code_send">
            <a href="#" class="dc-btn" v-on:click="reSendCode">{{trans('lang.verify_me')}}</a>
        </div>
        <form method="POST" action="" class="dc-formtheme dc-formregister" v-else>
            <fieldset>
                <div class="form-group">
                    <label>
                        {{{ trans('lang.verify_code_note') }}}
                        @if (!empty($reg_page))
                            <a target="_blank" href="{{{url($reg_page)}}}">
                                {{{ trans('lang.why_need_code') }}}
                            </a>
                        @else
                            <a href="javascript:void(0)">
                                {{{ trans('lang.why_need_code') }}}
                            </a>
                        @endif
                    </label>
                    <input type="text" v-model="verify_code" class="form-control" placeholder="{{{ trans('lang.enter_code') }}}">
                </div>
                <div class="form-group dc-btnarea">
                    <a href="#" v-on:click.prevent="verifyCode()" class="dc-btn">{{{ trans('lang.submit') }}}</a>
                </div>
            </fieldset>
        </form>
    </div>
</div>
