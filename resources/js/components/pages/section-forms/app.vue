<template>
    <div class="element-form-wrapper" v-if="app">
        <div class="amt-dhb-main_content">
            <div class="amt-dhb-heading"><h3>{{ trans('lang.content') }}</h3></div>
        </div>
        <div class="amt-element-title amt-element-titlecontent"><h6>{{ trans('lang.app_content_sec') }}</h6></div>
        <div class="amt-formcontactus">
            <fieldset>
                <div class="form-group"><input type="text" v-model="app.title" :placeholder="trans('lang.title')" class="form-control"></div>
                <div class="form-group"><input type="text" v-model="app.subtitle" :placeholder="trans('lang.title')" class="form-control"></div>
                <div class="form-group">
                    <tinymce-editor v-model="app.description" :init="{height: 350, plugins: 'paste link code advlist autolink lists link charmap print', toolbar1: 'undo redo code | bold italic underline strikethrough | fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist', menubar:false, statusbar: false, extended_valid_elements:'span[style],i[class]'}"></tinymce-editor>
                </div>
                <div 
                    class="at-profile-setting__upload google_store_img dc-settingscontent" 
                    :id="'google_store_img_wrapper'+currentElementID"
                    v-if="appImageMounted"
                >
                    <page-media
                        :parent_id="'google_store_img_wrapper'+currentElementID"
                        :id="'google_store_img'+currentElementID"
                        :img_ref="'google_store_img'+currentElementID"
                        :name="'google_store_img'"
                        :url="baseURL+'/media/upload-temp-image/pages/google_store_img'"
                        :preview_class="'google_store_img_preview'+currentElementID"
                        :hidden_input_id="'hidden_input_id_google_store_img'+currentElementID"
                        :list_id="'list_id_google_store_img'+currentElementID"
                        :upload_title="'upload image'"
                        :btn_text="'select file'"
                        :img_label="trans('lang.android_img')"
                        @addedFile="imageAdded('google_store_img_wrapper'+currentElementID, 'googlePlay', 'googlePlay_hiddenImage'+currentElementID)"
                        @fileRemoved="imageRemoved('googlePlay')"
                    >
                    </page-media>
                    <div class="at-profile-setting__imgs">
                        <ul :class="'google_store_img_preview'+currentElementID">
                            <li :id="'googlePlay_hiddenImage'+currentElementID" v-if="app.googlePlay.image">
                                <input id="radio1" type="radio" name="radio">
                                <label for="radio1">
                                    <span>
                                        <img :src="baseURL+'/uploads/pages/'+pageID+'/'+app.googlePlay.image" @error="OldImageError('googlePlay')" v-if="pageID && !newGoogleImage">
                                        <img :src="tempUrl+app.googlePlay.image" @error="TempImageError('googlePlay')" v-else-if="!pageID || newGoogleImage">
                                        <span class="at-trash">
                                            <a href="javascript:void(0);" v-on:click="removeImage('googlePlay', 'googlePlay_hiddenImage'+currentElementID)">
                                                <i class="ti-trash"></i>
                                            </a>
                                        </span>
                                        <span class="at-tick">
                                            <span>
                                                <i class="fas fa-check"></i>
                                            </span>
                                        </span>
                                    </span>
                                </label>
                                <input type="hidden" :value="app.googlePlay.image" name="image" :id="'googlePlay_hiddenImage'+currentElementID">
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    <input type="text" v-model="app.googlePlay.url" placeholder="Google play url" class="form-control">
                </div>
                <div 
                    class="at-profile-setting__upload app_store_img dc-settingscontent" 
                    :id="'app_store_img_wrapper'+currentElementID"
                    v-if="appImageMounted"
                >
                    <page-media
                        :parent_id="'app_store_img_wrapper'+currentElementID"
                        :id="'app_store_img'+currentElementID"
                        :img_ref="'app_store_img'+currentElementID"
                        :name="'app_store_img'"
                        :url="baseURL+'/media/upload-temp-image/pages/app_store_img'"
                        :preview_class="'app_store_img_preview'+currentElementID"
                        :hidden_input_id="'hidden_input_id_app_store_img'+currentElementID"
                        :list_id="'list_id_app_store_img'+currentElementID"
                        :upload_title="'upload image'"
                        :btn_text="'select file'"
                        :img_label="trans('lang.ios_img')"
                        @addedFile="imageAdded('app_store_img_wrapper'+currentElementID, 'appStore', 'appStore_hiddenImage'+currentElementID)"
                        @fileRemoved="imageRemoved('appStore')"
                    >
                    </page-media>
                    <div class="at-profile-setting__imgs">
                        <ul :class="'app_store_img_preview'+currentElementID">
                            <li :id="'appStore_hiddenImage'+currentElementID" v-if="app.appStore.image">
                                <input id="radio1" type="radio" name="radio">
                                <label for="radio1">
                                    <span>
                                        <img :src="baseURL+'/uploads/pages/'+pageID+'/'+app.appStore.image" @error="OldImageError('appStore')" v-if="pageID && !newAppStoreImage">
                                        <img :src="tempUrl+app.appStore.image" @error="TempImageError('appStore')" v-else-if="!pageID || newAppStoreImage">
                                        <span class="at-trash">
                                            <a href="javascript:void(0);" v-on:click="removeImage('appStore', 'appStore_hiddenImage'+currentElementID)">
                                                <i class="ti-trash"></i>
                                            </a>
                                        </span>
                                        <span class="at-tick">
                                            <span>
                                                <i class="fas fa-check"></i>
                                            </span>
                                        </span>
                                    </span>
                                </label>
                                <input type="hidden" :value="app.appStore.image" name="image" :id="'appStore_hiddenImage'+currentElementID">
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    <input type="text" v-model="app.appStore.url" placeholder="App strore url" class="form-control">
                </div>
                <div 
                    class="at-profile-setting__upload image_section dc-settingscontent" 
                    :id="'section_image_wrapper'+currentElementID"
                    v-if="appImageMounted"
                >
                    <page-media
                        :parent_id="'section_image_wrapper'+currentElementID"
                        :id="'image_section'+currentElementID"
                        :img_ref="'image_section'+currentElementID"
                        :name="'image_section'"
                        :url="baseURL+'/media/upload-temp-image/pages/image_section'"
                        :preview_class="'image_section_preview'+currentElementID"
                        :hidden_input_id="'hidden_input_id_image_section'+currentElementID"
                        :list_id="'list_id_image'+currentElementID"
                        :upload_title="'upload image'"
                        :btn_text="'select file'"
                        :img_label="trans('lang.app_img')"
                        @addedFile="imageAdded('section_image_wrapper'+currentElementID, 'url', 'app_hiddenImage'+currentElementID)"
                        @fileRemoved="imageRemoved('url')"
                    >
                    </page-media>
                    <div class="at-profile-setting__imgs" id="app_image">
                        <ul :class="'image_section_preview'+currentElementID">
                            <li :id="'app_hiddenImage'+currentElementID" v-if="app.image.url">
                                <input id="radio1" type="radio" name="radio">
                                <label for="radio1">
                                    <span>
                                        <img :src="baseURL+'/uploads/pages/'+pageID+'/'+app.image.url" @error="OldImageError('url')" v-if="pageID && !newAppImage">
                                        <img :src="tempUrl+app.image.url" @error="TempImageError('url')" v-else-if="!pageID || newAppImage">
                                        <span class="at-trash">
                                            <a href="javascript:void(0);" v-on:click="removeImage('url', 'app_hiddenImage'+currentElementID)">
                                                <i class="ti-trash"></i>
                                            </a>
                                        </span>
                                        <span class="at-tick">
                                            <span>
                                                <i class="fas fa-check"></i>
                                            </span>
                                        </span>
                                    </span>
                                </label>
                                <input type="hidden" :value="app.image.url" name="image" :id="'app_hiddenImage'+currentElementID">
                            </li>
                        </ul>
                    </div>
                </div>
            </fieldset>
            <div class="amt-dhb-main_content">
                <div class="amt-dhb-heading"><h3>{{ trans('lang.content_style') }}</h3></div>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                <h6>{{ trans('lang.title_color') }}</h6>
                <verte menuPosition="right" model="hex" v-model="app.titleColor"></verte>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                <h6>{{ trans('lang.subtitle_color') }}</h6>
                <verte menuPosition="right" model="hex" v-model="app.subtitleColor"></verte>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                <h6>{{ trans('lang.content_color') }}</h6>
                <verte menuPosition="right" model="hex" v-model="app.content.color"></verte>
            </div>
            <div class="amt-dhb-main_content">
                <div class="amt-dhb-heading"><h3>{{ trans('lang.content_section_style') }}</h3></div>
            </div>
            <div class="form-group border-top-0">
                <span class="amt-checkbox">
                    <input id="at-headingLink" type="checkbox" v-model="app.content.background" value="color"> 
                    <label for="at-headingLink">{{ trans('lang.add_background_clr') }}</label>
                </span>
                <verte  menuPosition="right" model="hex" v-model="app.content.backgroundColor" v-if="app.content.background"></verte>
            </div>
            <div class="form-group">
                <div class="amt-element-title">
                     <h6>{{ trans('lang.padding') }}</h6>
                    <div class="amt-guests-radioholder">
                        <span class="amt-radio"><input id="at-padding-pixal" type="radio" v-model="app.content.padding.unit" value="px"> <label for="at-padding-pixal">px</label></span>   
                        <span class="amt-radio"><input id="at-padding-percent" type="radio" v-model="app.content.padding.unit" value="%"> <label for="at-padding-percent">%</label></span>   
                    </div>
                </div>
                <div class="amt-spacing">
                    <ul class="amt-guestsinfo">
                        <li> 
                            <div class="amt-guests-radioholder">
                                <span class="amt-radio"><input type="number" v-model="app.content.padding.top"> <label for="at-top">{{ trans('lang.top') }}</label></span>   
                                <span class="amt-radio"><input type="number" v-model="app.content.padding.right"> <label for="at-right">{{ trans('lang.right') }}</label></span>   
                                <span class="amt-radio"><input type="number" v-model="app.content.padding.bottom"> <label for="at-bottom">{{ trans('lang.bottom') }}</label></span>   
                                <span class="amt-radio"><input type="number" v-model="app.content.padding.left"> <label for="at-left">{{ trans('lang.left') }}</label></span>   
                            </div>
                        </li>  
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <div class="amt-element-title">
                    <h6>{{ trans('lang.margin') }}</h6>
                    <div class="amt-guests-radioholder">
                        <span class="amt-radio"><input id="at-margin-pixal" type="radio" v-model="app.content.margin.unit" value="px"> <label for="at-margin-pixal">px</label></span>   
                        <span class="amt-radio"><input id="at-margin-percent" type="radio" v-model="app.content.margin.unit" value="%"> <label for="at-margin-percent">%</label></span>   
                    </div>
                </div>
                <div class="amt-spacing">
                    <ul class="amt-guestsinfo">
                        <li> 
                            <div class="amt-guests-radioholder">
                                <span class="amt-radio"><input type="number" v-model="app.content.margin.top"> <label for="at-top">{{ trans('lang.top') }}</label></span>   
                                <span class="amt-radio"><input type="number" v-model="app.content.margin.right"> <label for="at-right">{{ trans('lang.right') }}</label></span>   
                                <span class="amt-radio"><input type="number" v-model="app.content.margin.bottom"> <label for="at-bottom">{{ trans('lang.bottom') }}</label></span>   
                                <span class="amt-radio"><input type="number" v-model="app.content.margin.left"> <label for="at-left">{{ trans('lang.left') }}</label></span>   
                            </div>
                        </li>  
                    </ul>
                </div>
            </div>
            <div class="amt-dhb-main_content dc-title">
                <div class="amt-dhb-heading"><h3>{{ trans('lang.image_style') }}</h3></div>
            </div>
            <div class="at-collapseholder collapse show dc-title">
                <div class="form-group">
                    <div class="amt-element-title">
                        <h6>{{ trans('lang.width') }}</h6>
                        <div class="amt-guests-radioholder">
                            <span class="amt-radio"><input id="at-width-pixal" type="radio" v-model="app.image.widthUnit" value="px"> <label for="at-width-pixal">px</label></span>   
                            <span class="amt-radio"><input id="at-width-percent" type="radio" v-model="app.image.widthUnit" value="%"> <label for="at-width-percent">%</label></span>   
                        </div>
                    </div>
                    <div class="at-widgetcontent">
                        <a-slider id="imageWidth" :defaultValue="0" :max="1000" @change="onWidthChange" :reverse="reverse_slider" v-if="app.image.widthUnit == 'px'"/>
                        <a-slider id="imageWidth" :defaultValue="0" :reverse="reverse_slider" v-else @change="onWidthChange" />
                    </div>
                </div>
            </div>
            <div class="at-collapseholder collapse show dc-title">
                <div class="form-group">
                    <div class="amt-element-title">
                        <h6>{{ trans('lang.opacity') }}</h6>
                    </div>
                <div class="at-widgetcontent">
                    <a-slider id="imageOpacity" :defaultValue="1" :step="0.1" :min="0" :max="1" :reverse="reverse_slider" @change="onOpacityChange" />
                </div>
                </div>
            </div>
             <div class="amt-dhb-main_content dc-title">
                <div class="amt-dhb-heading"> <h3>{{ trans('lang.sec_style') }}</h3></div>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                 <h6>{{ trans('lang.background_color') }}</h6>
                <verte menuPosition="right" model="hex" v-model="app.background"></verte>
            </div>
            <fieldset>
                <div class="form-group">
                    <div class="amt-element-title">
                         <h6>{{ trans('lang.padding') }}</h6>
                        <div class="amt-guests-radioholder">
                            <span class="amt-radio"><input id="at-padding-pixal1" type="radio" v-model="app.padding.unit" value="px"> <label for="at-padding-pixal1">px</label></span>   
                            <span class="amt-radio"><input id="at-padding-percent1" type="radio" v-model="app.padding.unit" value="%"> <label for="at-padding-percent1">%</label></span>   
                        </div>
                    </div>
                    <div class="amt-spacing">
                        <ul class="amt-guestsinfo">
                            <li> 
                                <div class="amt-guests-radioholder">
                                    <span class="amt-radio"><input type="number" v-model="app.padding.top"> <label for="at-top">{{ trans('lang.top') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="app.padding.right"> <label for="at-right">{{ trans('lang.right') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="app.padding.bottom"> <label for="at-bottom">{{ trans('lang.bottom') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="app.padding.left"> <label for="at-left">{{ trans('lang.left') }}</label></span>   
                                </div>
                            </li>  
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    <div class="amt-element-title">
                        <h6>{{ trans('lang.margin') }}</h6>
                        <div class="amt-guests-radioholder">
                            <span class="amt-radio"><input id="at-margin-pixal1" type="radio" v-model="app.margin.unit" value="px"> <label for="at-margin-pixal1">px</label></span>   
                            <span class="amt-radio"><input id="at-margin-percent1" type="radio" v-model="app.margin.unit" value="%"> <label for="at-margin-percent1">%</label></span>   
                        </div>
                    </div>
                    <div class="amt-spacing">
                        <ul class="amt-guestsinfo">
                            <li> 
                                <div class="amt-guests-radioholder">
                                    <span class="amt-radio"><input type="number" v-model="app.margin.top"> <label for="at-top">{{ trans('lang.top') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="app.margin.right"> <label for="at-right">{{ trans('lang.right') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="app.margin.bottom"> <label for="at-bottom">{{ trans('lang.bottom') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="app.margin.left"> <label for="at-left">{{ trans('lang.left') }}</label></span>   
                                </div>
                            </li>  
                        </ul>
                    </div>
                </div>
                <div class="form-group"><input type="text" v-model="app.sectionClass" :placeholder="trans('lang.sec_class')" class="form-control"></div>
                <div class="form-group"><input type="text" v-model="app.sectionId" :placeholder="trans('lang.sec_id')" class="form-control"></div>
            </fieldset>
        </div>
    </div>
</template>
<script>
import Event from '../../../event'
import Editor from '@tinymce/tinymce-vue'
export default {
    components: {
        'tinymce-editor': Editor
    },
    props:['app', 'pageID', 'cloneElement', 'currentElementID'],
    data() {
        return {
            baseURL: APP_URL,
            tempUrl:APP_URL+'/uploads/pages/temp/',
            appImageMounted:true,
            newAppImage:false,
            newGoogleImage:false,
            newAppStoreImage:false,
        }
    },
    mounted: function() {
        var self = this
        Event.$on('section-edit', (data) => {
            self.appImageMounted = false
            setTimeout(function(){ 
                self.appImageMounted = true
            }, 10);
        })
    },
    methods:{
        removeImage: function(imageType, hiddenID) {
            if (imageType == 'url') {
                if (this.app.image.url) {
                    this.app.image.url = null
                }
            } else if (imageType == 'googlePlay') {
                if (this.app.googlePlay.image ) {
                    this.app.googlePlay.image = null
                } 
            } else if (imageType == 'appStore') {
                if (this.app.appStore.image) {
                    this.app.appStore.image = null
                } 
            } 
            document.getElementById(hiddenID).remove()
        },
        imageRemoved: function(imageType) {
            if (imageType == 'url') {
                if (this.app.image.url) {
                    this.app.image.url = null
                }
            } else if (imageType == 'googlePlay') {
                if (this.app.googlePlay.image ) {
                    this.app.googlePlay.image = null
                } 
            } else if (imageType == 'appStore') {
                if (this.app.appStore.image) {
                    this.app.appStore.image = null
                } 
            } 
        },
        onWidthChange(value) {
            this.app.image.width = value
        },
        onHeightChange(value) {
            this.app.image.height = value
        },
        onOpacityChange(value) {
            this.app.image.opacity = value
        },
        imageAdded: function(hiddenID, imageType, hiddenGenerateImage) {
            var self = this
            setTimeout(function(){ 
               if (document.querySelector('#'+hiddenID+ ' .at-profile-setting__imgs ul li [type="hidden"]')) {
                    var hidddenImage = document.querySelector('#'+hiddenID+ ' .at-profile-setting__imgs ul li [type="hidden"]');
                    let image = hidddenImage.value
                    if (imageType == 'url') {
                        if (image) {
                            self.app.image.url = image
                            Event.$emit('new-app-image'+self.currentElementID)
                            self.newAppImage = true
                        } else {
                            self.app.image.url = null
                        }
                    } else if (imageType == 'googlePlay') {
                        if (image) {
                            self.app.googlePlay.image = image
                            Event.$emit('new-google-image'+self.currentElementID)
                            self.newGoogleImage = true
                        } else {
                            self.app.googlePlay.image = null
                        }
                    } else if (imageType == 'appStore') {
                        if (image) {
                            self.app.appStore.image = image
                            Event.$emit('new-app-store-image'+self.currentElementID)
                            self.newAppStoreImage = true
                        } else {
                            self.app.appStore.image = null
                        }
                    } 
                } 
            }, 120);
            setTimeout(function(){ 
                if (document.getElementById(hiddenGenerateImage)) {
                    document.getElementById(hiddenGenerateImage).remove()
                }
            }, 130);
        },
        TempImageError (type) {
            if (type == 'url') {
                this.newAppImage = false
            } else if (type == 'googlePlay') {
                this.newGoogleImage = false
            } else if (type == 'appStore') {
                this.newAppStoreImage = false
            }
        },
        OldImageError (type) {
            if (type == 'url') {
                this.newAppImage = true
            } else if (type == 'googlePlay') {
                this.newGoogleImage = true
            } else if (type == 'appStore') {
                this.newAppStoreImage = false
            }
        }
    },
};
</script>
