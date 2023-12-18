<template>
    <div class="element-form-wrapper" v-if="about">
        <div class="amt-dhb-main_content">
            <div class="amt-dhb-heading"><h3>{{ trans('lang.content') }}</h3></div>
        </div>
        <div class="amt-element-title amt-element-titlecontent">
            <h6>{{ trans('lang.about_content_section') }}</h6>
        </div>
        <div class="amt-formcontactus">
            <fieldset>
                <div class="form-group"><input type="text" v-model="about.title" :placeholder="trans('lang.title')" class="form-control"></div>
                <div class="form-group"><input type="text" v-model="about.subtitle" :placeholder="trans('lang.title')" class="form-control"></div>
                <div class="form-group">
                    <tinymce-editor v-model="about.description" :init="{height: 350, plugins: 'paste link code advlist autolink lists link charmap print', toolbar1: 'undo redo code | bold italic underline strikethrough | fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist', menubar:false, statusbar: false, extended_valid_elements:'span[style],i[class]'}"></tinymce-editor>
                </div>
                <div class="form-group form-group-half">
                    <input :placeholder="trans('lang.btn_one_title')" type="text" v-model="about.btntitle1" class="form-control" />
                    <input :placeholder="trans('lang.btn_one_url')" type="text" v-model="about.btnurl1" class="form-control">
                </div>
                <div class="form-group form-group-half">
                    <input :placeholder="trans('lang.btn_two_title')" type="text" v-model="about.btntitle2" class="form-control">
                    <input :placeholder="trans('lang.btn_two_url')" type="text" v-model="about.btnurl2" class="form-control">
                </div>
                <div class="amt-element-title">

                </div>
                <div class="at-profile-setting__upload after_image_section dc-settingscontent" :id="'after_section_image_wrapper'+currentElementID" v-if="aboutImageMounted">
                    <page-media
                        :parent_id="'after_section_image_wrapper'+currentElementID"
                        :id="'after_image_section'+currentElementID"
                        :img_ref="'after_image_section'+currentElementID"
                        :name="'after_image_section'"
                        :url="baseURL+'/media/upload-temp-image/pages/after_image_section'"
                        :preview_class="'after_image_section_preview'+currentElementID"
                        :hidden_input_id="'hidden_input_id_after_image_section'+currentElementID"
                        :list_id="'list_id_after_section'+currentElementID"
                        :upload_title="'upload image'"
                        :btn_text="'select file'"
                        @addedFile="imageAdded('after_section_image_wrapper'+currentElementID, 'afterSection', 'afterSection_hiddenImage'+currentElementID)"
                        @fileRemoved="imageRemoved('afterSection')"
                    >
                    </page-media>
                    <div class="at-profile-setting__imgs">
                        <ul :class="'after_image_section_preview'+currentElementID">
                            <li :id="'afterSection_hiddenImage'+currentElementID" v-if="about.afterSection">
                                <input id="radio1" type="radio" name="radio">
                                <label for="radio1">
                                    <span>
                                        <img :src="baseURL+'/uploads/pages/'+pageID+'/'+about.afterSection" @error="OldImageError('afterSection')" v-if="pageID && !newAboutImage">
                                        <img :src="tempUrl+about.afterSection"  @error="TempImageError('afterSection')" v-else-if="!pageID || newAboutImage">
                                        <span class="at-trash">
                                            <a href="javascript:void(0);" v-on:click="removeImage('afterSection', 'afterSection_hiddenImage'+currentElementID)">
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
                                <input type="hidden" :value="about.afterSection" :id="'afterSection_hiddenImage'+currentElementID">
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="at-profile-setting__upload image_section dc-settingscontent" :id="'section_image_wrapper'+currentElementID" v-if="aboutImageMounted">
                    <div class="amt-element-title amt-element-titlecontent">
                        <h6>{{ trans('lang.about_img_section') }}</h6>
                    </div>
                    <page-media
                        :parent_id="'section_image_wrapper'+currentElementID"
                        :id="'image_section'+currentElementID"
                        :img_ref="'image_section'+currentElementID"
                        :name="'image_section'"
                        :url="baseURL+'/media/upload-temp-image/pages/image_section'"
                        :preview_class="'image_section_preview'+currentElementID"
                        :hidden_input_id="'hidden_input_id_image_section'"
                        :list_id="'list_id_about_image'+currentElementID"
                        :upload_title="'upload image'"
                        :btn_text="'select file'"
                        @addedFile="imageAdded('section_image_wrapper'+currentElementID, 'url', 'about_hiddenImage'+currentElementID)"
                        @fileRemoved="imageRemoved('url')"
                    >
                    </page-media>
                    <div class="at-profile-setting__imgs" id="about_image">
                        <ul :class="'image_section_preview'+currentElementID">
                            <li :id="'about_hiddenImage'+currentElementID" v-if="about.image.url">
                                <input id="radio1" type="radio" name="radio">
                                <label for="radio1">
                                    <span>
                                        <img :src="baseURL+'/uploads/pages/'+pageID+'/'+about.image.url" @error="OldImageError('url')" v-if="pageID && !newAfterImage">
                                        <img :src="tempUrl+about.image.url" @error="TempImageError('url')" v-else-if="!pageID || newAfterImage">
                                        <span class="at-trash">
                                            <a href="javascript:void(0);" v-on:click="removeImage('url', 'about_hiddenImage'+currentElementID)">
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
                                <input type="hidden" :value="about.image.url" name="image" :id="'about_hiddenImage'+currentElementID">
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="form-group"><input type="text" v-model="about.image.title" :placeholder="trans('lang.title')" class="form-control"></div>
                <div class="form-group"><input type="text" v-model="about.image.subtitle" :placeholder="trans('lang.title')" class="form-control"></div>
            </fieldset>
            <div class="amt-dhb-main_content">
                <div class="amt-dhb-heading"><h3>{{ trans('lang.style') }}</h3></div>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                <h6>{{ trans('lang.title_style') }}</h6>
                <verte menuPosition="right" model="hex" v-model="about.titleColor"></verte>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                <h6>{{ trans('lang.subtitle_style') }}</h6>
                <verte menuPosition="right" model="hex" v-model="about.subtitleColor"></verte>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                <h6>{{ trans('lang.img_title_clr') }}</h6>
                <verte menuPosition="right" model="hex" v-model="about.image.titleColor"></verte>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                <h6>{{ trans('lang.img_subtitle_clr') }}</h6>
                <verte menuPosition="right" model="hex" v-model="about.image.subtitleColor"></verte>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                <h6>{{ trans('lang.caption_background') }}</h6>
                <verte menuPosition="right" model="hex" v-model="about.image.captionBackground"></verte>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                <h6>{{ trans('lang.caption_after_color') }}</h6>
                <verte menuPosition="right" model="hex" v-model="about.image.after"></verte>
            </div>
            <div class="at-collapseholder collapse show">
                <div class="form-group">
                    <div class="amt-element-title">
                         <h6>{{ trans('lang.alignment') }}</h6>
                        <div class="amt-guests-radioholder">
                            <span class="amt-radio"><input id="at-width-pixal" type="radio" v-model="about.image.widthUnit" value="px"> <label for="at-width-pixal">px</label></span>   
                            <span class="amt-radio"><input id="at-width-percent" type="radio" v-model="about.image.widthUnit" value="%"> <label for="at-width-percent">%</label></span>   
                        </div>
                    </div>
                    <div class="at-widgetcontent">
                        <a-slider id="imageWidth" :defaultValue="0"  @change="onWidthChange" :reverse="reverse_slider"/>
                    </div>
                </div>
            </div>
            <div class="at-collapseholder collapse show">
                <div class="form-group">
                    <div class="amt-element-title">
                         <h6>{{ trans('lang.alignment') }}</h6>
                        <div class="amt-guests-radioholder">
                            <span class="amt-radio"><input id="at-height-pixal" type="radio" v-model="about.image.heightUnit" value="px"> <label for="at-height-pixal">px</label></span>   
                            <span class="amt-radio"><input id="at-height-percent" type="radio" v-model="about.image.heightUnit" value="%"> <label for="at-height-percent">%</label></span>   
                        </div>
                    </div>
                    <div class="at-widgetcontent">
                        <a-slider id="imageHeight" :defaultValue="0"  :reverse="reverse_slider" @change="onHeightChange" />
                    </div>
                </div>
            </div>
            <div class="at-collapseholder collapse show">
                <div class="form-group">
                    <div class="at-widgetcontent">
                        <a-slider id="imageOpacity" :step="0.1" :min="0" :max="1"  :reverse="reverse_slider" @change="onOpacityChange" />
                    </div>
                </div>
            </div>
            <!-- <div class="form-group"><input type="text" v-model="about.image.elementClass" :placeholder="trans('lang.elem_class')" class="form-control"></div>
            <div class="form-group"><input type="text" v-model="about.image.elementId" :placeholder="trans('lang.elem_id')" class="form-control"></div> -->
            <div class="amt-dhb-main_content">
                <div class="amt-dhb-heading"><h3>{{ trans('lang.sec_style') }}</h3></div>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                 <h6>{{ trans('lang.color') }}</h6>
                <verte menuPosition="right" model="hex" v-model="about.background"></verte>
            </div>
            <fieldset>
                <div class="form-group">
                    <div class="amt-element-title">
                        <h6>{{ trans('lang.padding') }}</h6>
                        <div class="amt-guests-radioholder">
                            <span class="amt-radio"><input id="at-padding-pixal" type="radio" v-model="about.padding.unit" value="px"> <label for="at-padding-pixal">px</label></span>   
                            <span class="amt-radio"><input id="at-padding-percent" type="radio" v-model="about.padding.unit" value="%"> <label for="at-padding-percent">%</label></span>   
                        </div>
                    </div>
                    <div class="amt-spacing">
                        <ul class="amt-guestsinfo">
                            <li> 
                                <div class="amt-guests-radioholder">
                                    <span class="amt-radio"><input type="number" v-model="about.padding.top"> <label for="at-top">{{ trans('lang.top') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="about.padding.right"> <label for="at-right">{{ trans('lang.right') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="about.padding.bottom"> <label for="at-bottom">{{ trans('lang.bottom') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="about.padding.left"> <label for="at-left">{{ trans('lang.left') }}</label></span>   
                                </div>
                            </li>  
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    <div class="amt-element-title">
                        <h6>{{ trans('lang.margin') }}</h6>
                        <div class="amt-guests-radioholder">
                            <span class="amt-radio"><input id="at-margin-pixal" type="radio" v-model="about.margin.unit" value="px"> <label for="at-margin-pixal">px</label></span>   
                            <span class="amt-radio"><input id="at-margin-percent" type="radio" v-model="about.margin.unit" value="%"> <label for="at-margin-percent">%</label></span>   
                        </div>
                    </div>
                    <div class="amt-spacing">
                        <ul class="amt-guestsinfo">
                            <li> 
                                <div class="amt-guests-radioholder">
                                    <span class="amt-radio"><input type="number" v-model="about.margin.top"> <label for="at-top">{{ trans('lang.top') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="about.margin.right"> <label for="at-right">{{ trans('lang.right') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="about.margin.bottom"> <label for="at-bottom">{{ trans('lang.bottom') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="about.margin.left"> <label for="at-left">{{ trans('lang.left') }}</label></span>   
                                </div>
                            </li>  
                        </ul>
                    </div>
                </div>
                <div class="form-group"><input type="text" v-model="about.sectionClass" :placeholder="trans('lang.sec_class')" class="form-control"></div>
                <div class="form-group"><input type="text" v-model="about.sectionId" :placeholder="trans('lang.sec_id')" class="form-control"></div>
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
    props:['about', 'pageID', 'cloneElement', 'currentElementID'],
    data() {
        return {
            baseURL: APP_URL,
            tempUrl:APP_URL+'/uploads/pages/temp/',
            aboutImageMounted:true,
            newAboutImage: false,
            newAfterImage: false,
        }
    },
    mounted: function() {
        var self = this
        Event.$on('section-edit', (data) => {
            self.aboutImageMounted = false
            setTimeout(function(){ 
                self.aboutImageMounted = true
            }, 10);
        })
    },
    methods:{
        removeImage: function(imageType, hiddenID) {
            if (imageType == 'url') {
                if (this.about.image.url) {
                    this.about.image.url = null
                }
            } else if (imageType == 'afterSection') {
                if (this.about.afterSection) {
                    this.about.afterSection = null
                }
            } 
            document.getElementById(hiddenID).remove()
        },
        imageRemoved: function(imageType) {
            if (imageType == 'url') {
                if (this.about.image.url) {
                    this.about.image.url = null
                }
            } else if (imageType == 'afterSection') {
                if (this.about.afterSection) {
                    this.about.afterSection = null
                }
            } 
        },
        onWidthChange(value) {
            this.about.image.width = value
        },
        onHeightChange(value) {
            this.about.image.height = value
        },
        onOpacityChange(value) {
            this.about.image.opacity = value
        },
        imageAdded: function(hiddenID, imageType, hiddenGenerateImage) {
            var self = this
            setTimeout(function(){ 
               if (document.querySelector('#'+hiddenID+ ' .at-profile-setting__imgs ul li [type="hidden"]')) {
                   var hidddenImage = document.querySelector('#'+hiddenID+ ' .at-profile-setting__imgs ul li [type="hidden"]');
                    let image = hidddenImage.value
                    if (imageType == 'url') {
                        if (image) {
                            self.about.image.url = image
                            Event.$emit('new-about-image'+self.currentElementID)
                            self.newAboutImage = true 
                        } else {
                            self.about.image.url = null
                        }
                    } else if (imageType == 'afterSection') {
                        if (image) {
                            self.about.afterSection = image
                            Event.$emit('new-after-image'+self.currentElementID)
                            self.newAfterImage = true
                        } else {
                            self.about.afterSection = null
                        }
                    } 
                } 
            }, 100);
            setTimeout(function(){ 
                if (document.getElementById(hiddenGenerateImage)) {
                    document.getElementById(hiddenGenerateImage).remove()
                }
            }, 110);
        },
        TempImageError (type) {
            if (type == 'url') {
                this.newAfterImage = false
            } else if (type == 'afterSection') {
                this.newAboutImage = false
            }
        },
        OldImageError (type) {
            if (type == 'url') {
                this.newAfterImage = true
            } else if (type == 'afterSection') {
                this.newAboutImage = true
            }
        }
    },
};
</script>
