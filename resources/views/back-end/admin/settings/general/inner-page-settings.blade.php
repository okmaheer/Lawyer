<div class="la-inner-pages dc-haslayout">
    {!! Form::open(['class' =>'dc-formtheme dc-userform', 'id' =>'inner-page-form', '@submit.prevent'=>'submitInnerPage'])!!}
        <div class="dc-breadcrumbs dc-tabsinfo">
            <div class="dc-tabscontenttitle">
                <h3>{{{ trans('lang.breadcrumbs_option') }}}</h3>
            </div>
            <div class="dc-sidepadding">
                <div class="dc-description"><p>{{ trans('lang.breadcrumbs_option_note') }}</p></div>
                <switch_button v-model="enable_breadcrumbs">{{{ trans('lang.enable_disable') }}}</switch_button>
                <input type="hidden" :value="enable_breadcrumbs" name="inner_page[enable_breadcrumbs]">
            </div>
        </div>
        <div class="dc-breadcrumbs dc-tabsinfo">
            <div class="dc-tabscontenttitle">
                <h3>{{{ trans('lang.search_form') }}}</h3>
            </div>
            <div class="dc-sidepadding">
                <div class="dc-description"><p>{{ trans('lang.show_hide_search_form') }}</p></div>
                <switch_button v-model="show_search_form">{{{ trans('lang.show_hide_search_form') }}}</switch_button>
                <input type="hidden" :value="show_search_form" name="inner_page[show_search_form]">
            </div>
        </div>
        <div class="dc-doctorlisting dc-tabsinfo">
            <div class="dc-tabscontenttitle">
                <h3>{{{ trans('lang.search_listing') }}}</h3>
            </div>
            <div class="dc-sidepadding">
                <div class="dc-settingscontent">
                    <div class="dc-description"><p>{{ trans('lang.seo_meta_title') }}</p></div>
                    <div class="dc-formtheme dc-userform">
                        <fieldset>
                            <div class="form-group">
                                {!! Form::text('inner_page[search_list_meta_title]', e($search_list_meta_title), array('class' => 'form-control')) !!}
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="dc-settingscontent">
                    <div class="dc-description"><p>{{ trans('lang.seo_meta_desc') }}</p></div>
                    <div class="dc-formtheme dc-userform">
                        <fieldset>
                            <div class="form-group">
                                {!! Form::textarea('inner_page[search_list_meta_desc]', e($search_list_meta_desc), array('class' => 'form-control')) !!}
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="dc-settingscontent">
                    <switch_button v-model="show_search_menu">{{{ trans('lang.add_menu_to_navbar') }}}</switch_button>
                    <input type="hidden" :value="show_search_menu" name="inner_page[show_search_menu]">
                </div>
                <div class="dc-settingscontent">
                    <div class="dc-tabscontenttitle dc-tabscontenttitle2">
                        <h3>{{{ trans('lang.header_style') }}}</h3>
                    </div>
                    <div class="amt-section-select amt-profile-settings">
                        <ul class="at-profile-setting__imgs">
                            <li v-for="(style, index) in getHeaderList()" :key="index">
                                <input 
                                    type="radio" 
                                    name="inner_page[search_list_header_style]"  
                                    :id="'s_image1'+index"  
                                    :value="style.value" 
                                    :checked="style.value == '{{$search_list_selected_header}}'"
                                >
                                <label :for="'s_image1'+index">
                                    <span>
                                        <img :src="style.image" alt="Image Description">
                                        <span class="at-tick"><span><i class="fas fa-check"></i></span></span>
                                    </span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Search List Header Styling -->
                <div class="dc-settingscontent">
                    <div class="dc-tabscontenttitle dc-tabscontenttitle2">
                        <h3>{{{ trans('lang.header_styling') }}}</h3>
                    </div>
                    <switch_button v-model="show_search_list_header_styling">{{{ trans('lang.header_styling_note') }}}</switch_button>
                    <input type="hidden" :value="show_search_list_header_styling" name="inner_page[show_search_list_header_styling]">
                </div>
                <div class="dc-settingscontent2 dc-sidepadding" v-if="show_search_list_header_styling  && colorSettings">
                    <div class="amt-element-title amt-element-titlecontent">
                        <h6>{{ trans('lang.navbar_color') }}</h6>
                        <verte model="hex" v-model="searchHeaderStyling.menuColor"></verte>
                        <input type="hidden" :value="searchHeaderStyling.menuColor" name="inner_page[search_menu_color]">
                    </div>
                    <div class="amt-element-title amt-element-titlecontent">
                        <h6>{{ trans('lang.navbar_hover_color') }}</h6>
                        <verte model="hex" v-model="searchHeaderStyling.HoverColor" ></verte>
                        <input type="hidden" :value="searchHeaderStyling.HoverColor" name="inner_page[search_hover_color]">
                    </div>
                    <div class="amt-element-title amt-element-titlecontent">
                        <h6>{{ trans('lang.profile_menu_color') }}</h6>
                        <verte model="hex"  v-model="searchHeaderStyling.textColor"></verte>
                        <input type="hidden" :value="searchHeaderStyling.textColor" name="inner_page[search_menu_text_color]">
                    </div>
                    <div class="dc-settingscontent dc-tabsinfo">
                        @if (!empty($search_logo))
                            <upload-media
                            :title="'{{ trans('lang.search_logo') }}'"
                            :img="'{{ $search_logo }}'"
                            :img_id="'search_logo'"
                            :img_name="'inner_page[search_logo]'"
                            :img_ref="'search_logo'"
                            :img_hidden_name="'inner_page[search_logo]'"
                            img_hidden_id="'hidden_search_logo'"
                            :existed_img="'{{ $search_logo }}'"
                            :url="'{{ url("media/upload-temp-image/settings/inner_page.search_logo") }}'"
                            :existing_img_url="'{{ url("uploads/settings/inner-page/$search_logo") }}'"
                            :size = "'{{ Helper::getImageDetail( $search_logo, 'size', 'uploads/settings/inner-page') }}'"
                            :existing_img_name = "'{{ Helper::getImageDetail( $search_logo, 'name', 'uploads/settings/inner-page') }}'"
                            >
                            </upload-media>
                        @else
                            <upload-media
                                :title="'{{ trans('lang.search_logo') }}'"
                                :img="'inner_page[search_logo]'"
                                :img_id="'search_logo'"
                                :img_name="'inner_page[search_logo]'"
                                :img_ref="'search_logo'"
                                :img_hidden_name="'inner_page[search_logo]'"
                                img_hidden_id="'hidden_search_logo'"
                                :url="'{{ url("media/upload-temp-image/settings/inner_page.search_logo") }}'"
                                >
                            </upload-media>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="dc-doctorlisting dc-tabsinfo">
            <div class="dc-tabscontenttitle">
                <h3>{{{ trans('lang.article_listing') }}}</h3>
            </div>
            <div class="dc-sidepadding">
                <div class="dc-settingscontent">
                    <div class="dc-description"><p>{{ trans('lang.seo_meta_title') }}</p></div>
                    <div class="dc-formtheme dc-userform">
                        <fieldset>
                            <div class="form-group">
                                {!! Form::text('inner_page[article_meta_title]', e($article_meta_title), array('class' => 'form-control')) !!}
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="dc-settingscontent">
                    <div class="dc-description"><p>{{ trans('lang.seo_meta_desc') }}</p></div>
                    <div class="dc-formtheme dc-userform">
                        <fieldset>
                            <div class="form-group">
                                {!! Form::textarea('inner_page[article_meta_desc]', e($article_meta_desc), array('class' => 'form-control')) !!}
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="dc-settingscontent">
                    <switch_button v-model="show_article_menu">{{{ trans('lang.add_menu_to_navbar') }}}</switch_button>
                    <input type="hidden" :value="show_article_menu" name="inner_page[show_article_menu]">
                </div>
                <div class="dc-settingscontent">
                    <div class="dc-tabscontenttitle dc-tabscontenttitle2">
                        <h3>{{{ trans('lang.header_style') }}}</h3>
                    </div>
                    <div class="amt-section-select amt-profile-settings">
                        <ul class="at-profile-setting__imgs">
                            <li v-for="(style, index) in getHeaderList()" :key="index">
                                <input 
                                    type="radio" 
                                    name="inner_page[article_header_style]"  
                                    :id="'a_image1'+index"  
                                    :value="style.value" 
                                    :checked="style.value == '{{$article_selected_header}}'"
                                >
                                <label :for="'a_image1'+index">
                                    <span>
                                        <img :src="style.image" alt="Image Description">
                                        <span class="at-tick"><span><i class="fas fa-check"></i></span></span>
                                    </span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Article Header Styling -->
                <div class="dc-settingscontent">
                    <div class="dc-tabscontenttitle dc-tabscontenttitle2">
                        <h3>{{{ trans('lang.header_styling') }}}</h3>
                    </div>
                    <switch_button v-model="show_article_header_styling">{{{ trans('lang.header_styling_note') }}}</switch_button>
                    <input type="hidden" :value="show_article_header_styling" name="inner_page[show_article_header_styling]">
                </div>
                <div class="dc-settingscontent2 dc-sidepadding" v-if="show_article_header_styling && colorSettings">
                    <div class="amt-element-title amt-element-titlecontent">
                        <h6>{{ trans('lang.navbar_color') }}</h6>
                        <verte model="hex" v-model="articleHeaderStyling.menuColor"></verte>
                        <input type="hidden" :value="articleHeaderStyling.menuColor" name="inner_page[article_menu_color]">
                    </div>
                    <div class="amt-element-title amt-element-titlecontent">
                        <h6>{{ trans('lang.navbar_hover_color') }}</h6>
                        <verte model="hex" v-model="articleHeaderStyling.HoverColor"></verte>
                        <input type="hidden" :value="articleHeaderStyling.HoverColor" name="inner_page[article_hover_color]">
                    </div>
                    <div class="amt-element-title amt-element-titlecontent">
                        <h6>{{ trans('lang.profile_menu_color') }}</h6>
                        <verte model="hex" v-model="articleHeaderStyling.textColor"></verte>
                        <input type="hidden" :value="articleHeaderStyling.textColor" name="inner_page[article_menu_text_color]">
                    </div>
                    <div class="dc-settingscontent dc-tabsinfo">
                        @if (!empty($article_logo))
                            <upload-media
                            :title="trans('lang.article_logo')"
                            :img="'{{ $article_logo }}'"
                            :img_id="'article_logo'"
                            :img_name="'article_logo'"
                            :img_ref="'article_logo'"
                            :img_hidden_name="'inner_page[article_logo]'"
                            img_hidden_id="'hidden_article_logo'"
                            :existed_img="'{{ $article_logo }}'"
                            :url="'{{ url("media/upload-temp-image/settings/article_logo") }}'"
                            :existing_img_url="'{{ url("uploads/settings/inner-page/$article_logo") }}'"
                            :size = "'{{ Helper::getImageDetail( $article_logo, 'size', 'uploads/settings/inner-page') }}'"
                            :existing_img_name = "'{{ Helper::getImageDetail( $article_logo, 'name', 'uploads/settings/inner-page') }}'"
                            >
                            </upload-media>
                        @else
                            <upload-media
                                :title="trans('lang.article_logo')"
                                :img="'inner_page[article_logo]'"
                                :img_id="'article_logo'"
                                :img_name="'inner_page[article_logo]'"
                                :img_ref="'article_logo'"
                                :img_hidden_name="'inner_page[article_logo]'"
                                img_hidden_id="'hidden_article_logo'"
                                :url="'{{ url("media/upload-temp-image/settings/inner_page.article_logo") }}'"
                                >
                            </upload-media>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="dc-doctorlisting dc-tabsinfo">
            <div class="dc-tabscontenttitle">
                <h3>{{{ trans('lang.health_forum') }}}</h3>
            </div>
            <div class="dc-sidepadding">
                <div class="dc-settingscontent">
                    <div class="dc-description"><p>{{ trans('lang.seo_meta_title') }}</p></div>
                    <div class="dc-formtheme dc-userform">
                        <fieldset>
                            <div class="form-group">
                                {!! Form::text('inner_page[health_meta_title]', e($health_meta_title), array('class' => 'form-control')) !!}
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="dc-settingscontent">
                    <div class="dc-description"><p>{{ trans('lang.seo_meta_desc') }}</p></div>
                    <div class="dc-formtheme dc-userform">
                        <fieldset>
                            <div class="form-group">
                                {!! Form::textarea('inner_page[health_meta_desc]', e($health_meta_desc), array('class' => 'form-control')) !!}
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="dc-settingscontent">
                    <switch_button v-model="show_forum_menu">{{{ trans('lang.add_menu_to_navbar') }}}</switch_button>
                    <input type="hidden" :value="show_forum_menu" name="inner_page[show_forum_menu]">
                </div>
                <div class="dc-settingscontent">
                    <div class="dc-tabscontenttitle dc-tabscontenttitle2">
                        <h3>{{{ trans('lang.header_style') }}}</h3>
                    </div>
                    <div class="amt-section-select amt-profile-settings">
                        <ul class="at-profile-setting__imgs">
                            <li v-for="(style, index) in getHeaderList()" :key="index">
                                <input 
                                    type="radio" 
                                    name="inner_page[forum_header_style]"  
                                    :id="'f_image1'+index"  
                                    :value="style.value" 
                                    :checked="style.value == '{{$forum_selected_header}}'"
                                >
                                <label :for="'f_image1'+index">
                                    <span>
                                        <img :src="style.image" alt="Image Description">
                                        <span class="at-tick"><span><i class="fas fa-check"></i></span></span>
                                    </span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Forum Header Styling -->
                <div class="dc-settingscontent">
                    <div class="dc-tabscontenttitle dc-tabscontenttitle2">
                        <h3>{{{ trans('lang.header_styling') }}}</h3>
                    </div>
                    <switch_button v-model="show_forum_header_styling">{{{ trans('lang.header_styling_note') }}}</switch_button>
                    <input type="hidden" :value="show_forum_header_styling" name="inner_page[show_forum_header_styling]">
                </div>
                <div class="dc-settingscontent2 dc-sidepadding" v-if="show_forum_header_styling && colorSettings">
                    <div class="amt-element-title amt-element-titlecontent">
                        <h6>{{ trans('lang.navbar_color') }}</h6>
                        <verte model="hex" v-model="forumHeaderStyling.menuColor"></verte>
                        <input type="hidden" :value="forumHeaderStyling.menuColor" name="inner_page[forum_menu_color]">
                    </div>
                    <div class="amt-element-title amt-element-titlecontent">
                        <h6>{{ trans('lang.navbar_hover_color') }}</h6>
                        <verte model="hex" v-model="forumHeaderStyling.HoverColor"></verte>
                        <input type="hidden" :value="forumHeaderStyling.HoverColor" name="inner_page[forum_hover_color]">
                    </div>
                    <div class="amt-element-title amt-element-titlecontent">
                        <h6>{{ trans('lang.profile_menu_color') }}</h6>
                        <verte model="hex" v-model="forumHeaderStyling.textColor"></verte>
                        <input type="hidden" :value="forumHeaderStyling.textColor" name="inner_page[forum_menu_text_color]">
                    </div>
                    <div class="dc-settingscontent dc-tabsinfo">
                        @if (!empty($forum_logo))
                            <upload-media
                            :title="'{{ trans('lang.forum_logo') }}'"
                            :img="'{{ $forum_logo }}'"
                            :img_id="'forum_logo'"
                            :img_name="'forum_logo'"
                            :img_ref="'forum_logo'"
                            :img_hidden_name="'inner_page[forum_logo]'"
                            img_hidden_id="'hidden_forum_logo'"
                            :existed_img="'{{ $forum_logo }}'"
                            :url="'{{ url("media/upload-temp-image/settings/forum_logo") }}'"
                            :existing_img_url="'{{ url("uploads/settings/inner-page/$forum_logo") }}'"
                            :size = "'{{ Helper::getImageDetail( $forum_logo, 'size', 'uploads/settings/inner-page') }}'"
                            :existing_img_name = "'{{ Helper::getImageDetail( $forum_logo, 'name', 'uploads/settings/inner-page') }}'"
                            >
                            </upload-media>
                        @else
                            <upload-media
                                :title="'{{ trans('lang.forum_logo') }}'"
                                :img="'inner_page[forum_logo]'"
                                :img_id="'forum_logo'"
                                :img_name="'inner_page[forum_logo]'"
                                :img_ref="'forum_logo'"
                                :img_hidden_name="'inner_page[forum_logo]'"
                                img_hidden_id="'hidden_forum_logo'"
                                :url="'{{ url("media/upload-temp-image/settings/inner_page.forum_logo") }}'"
                                >
                            </upload-media>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="dc-experienceaccordion">
            <div class="dc-updatall la-updateall-holder mb-5 mt-0">
                {!! Form::submit(trans('lang.btn_save'),['class' => 'dc-btn']) !!}
            </div>
        </div>
    {!! Form::close() !!}
</div>
