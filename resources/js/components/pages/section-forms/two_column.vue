<template>
    <div class="element-form-wrapper" v-if="two_column">
        <div class="amt-dhb-main_content">
            <div class="amt-dhb-heading"><h3>{{ trans('lang.content') }}</h3></div>
        </div>
        <div class="amt-element-title amt-element-titlecontent">
            <h6>{{ trans('lang.content_section') }}</h6>
        </div>
        <div class="amt-formcontactus">
            <fieldset>
                <div class="form-group"><input type="text" v-model="two_column.title" :placeholder="trans('lang.title')" class="form-control"></div>
                <div class="form-group"><input type="text" v-model="two_column.subtitle" :placeholder="trans('lang.title')" class="form-control"></div>
                <div class="form-group">
                    <tinymce-editor v-model="two_column.description" :init="{height: 350, plugins: 'paste link code advlist autolink lists link charmap print', toolbar1: 'undo redo code | bold italic underline strikethrough | fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist', menubar:false, statusbar: false, extended_valid_elements:'span[style],i[class]'}"></tinymce-editor>
                </div>
                <div class="form-group form-group-half">
                    <input :placeholder="trans('lang.btn_one_title')" type="text" v-model="two_column.btn_text" class="form-control" />
                    <input :placeholder="trans('lang.btn_one_url')" type="text" v-model="two_column.url" class="form-control">
                </div>
                <div class="form-group form-group-half">
                    <input 
                        type="text" 
                        v-model="two_column.contentSectionClass" 
                        :placeholder="trans('lang.class')" 
                        class="form-control"
                    >
                    <input 
                        type="text" 
                        v-model="two_column.contentSectionID" 
                        :placeholder="trans('lang.id')" 
                        class="form-control"
                    >
                </div>
                <div class="at-profile-setting__upload image_section dc-settingscontent" :id="'section_image_wrapper'+currentElementIndex" v-if="imageMounted">
                    <div class="amt-element-title amt-element-titlecontent">
                        <h6>{{ trans('lang.image_section') }}</h6>
                    </div>
                    <page-media
                        :parent_id="'section_image_wrapper'+currentElementIndex"
                        :id="'image_section'+currentElementIndex"
                        :img_ref="'image_section'+currentElementIndex"
                        :name="'image_section'+currentElementIndex"
                        :url="baseURL+'/media/upload-temp-image/pages/image_section'+currentElementIndex"
                        :preview_class="'image_section_preview'+currentElementIndex"
                        :hidden_input_id="'hidden_input_id_image_section'+currentElementIndex"
                        :list_id="'list_id_image'+currentElementID"
                        :upload_title="'upload image'"
                        :btn_text="'select file'"
                        @addedFile="imageAdded('section_image_wrapper'+currentElementIndex, 'image', 'two_column_hiddenImage'+currentElementIndex)"
                        @fileRemoved="imageRemoved('image')"
                    >
                    </page-media>
                    <div class="at-profile-setting__imgs" :id="'two_column_image'+currentElementIndex">
                        <ul :class="'image_section_preview'+currentElementIndex">
                            <li :id="'two_column_hiddenImage'+currentElementIndex" v-if="two_column.image">
                                <input id="radio1" type="radio" name="radio">
                                <label for="radio1">
                                    <span>
                                        <img :src="baseURL+'/uploads/pages/'+pageID+'/'+two_column.image" @error="OldImageError('image')" v-if="pageID && !newTwoColumnImage">
                                        <img :src="tempUrl+two_column.image" @error="TempImageError('image')" v-else-if="!pageID || newTwoColumnImage">
                                        <span class="at-trash">
                                            <a href="javascript:void(0);" v-on:click="removeImage('image', 'two_column_hiddenImage'+currentElementIndex)">
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
                                <input type="hidden" :value="two_column.image" name="image" :id="'two_column_hiddenImage'+currentElementIndex">
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    <span class="dc-select">
                        <select class="form-control" v-model="two_column.imageOrder">
                            <option v-for="(order, index) in orders" :key="index+'-order'" :value="index"> 
                                {{order}} 
                            </option>
                        </select>
                    </span>
                </div>
                <div class="form-group form-group-half">
                    <input 
                        type="text" 
                        v-model="two_column.imageSectionClass" 
                        :placeholder="trans('lang.class')" 
                        class="form-control"
                    >
                    <input 
                        type="text" 
                        v-model="two_column.imageSectionID" 
                        :placeholder="trans('lang.id')" 
                        class="form-control"
                    >
                </div>
            </fieldset>
            <div class="amt-dhb-main_content">
                <div class="amt-dhb-heading"><h3>{{ trans('lang.style') }}</h3></div>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                <h6>{{ trans('lang.title_clr') }}</h6>
                <verte menuPosition="right" model="hex" v-model="two_column.titleColor"></verte>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                <h6>{{ trans('lang.sutitle_clr') }}</h6>
                <verte menuPosition="right" model="hex" v-model="two_column.subtitleColor"></verte>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                <h6>{{ trans('lang.content_clr') }}</h6>
                <verte menuPosition="right" model="hex" v-model="two_column.contentColor"></verte>
            </div>
            <div class="at-collapseholder collapse show">
                <div class="form-group">
                    <div class="amt-element-title">
                         <h6>{{ trans('lang.image_width') }}</h6>
                        <div class="amt-guests-radioholder">
                            <span class="amt-radio">
                                <input id="at-width-pixal" type="radio" v-model="two_column.imageWidthUnit" value="px"> 
                                <label for="at-width-pixal">px</label>
                            </span>   
                            <span class="amt-radio">
                                <input id="at-width-percent" type="radio" v-model="two_column.imageWidthUnit" value="%"> 
                                <label for="at-width-percent">%</label>
                            </span>   
                        </div>
                    </div>
                    <div class="at-widgetcontent">
                        <a-slider id="imageWidth" :min="0" :max="100" :defaultValue="0" :reverse="reverse_slider" @change="onWidthChange" v-if="two_column.imageWidthUnit == '%'" />
                        <a-slider id="imageWidth" :min="0" :max="1000" :defaultValue="0" :reverse="reverse_slider" @change="onWidthChange" v-else />
                    </div>
                </div>
            </div>
            <div class="at-collapseholder collapse show">
                <div class="form-group">
                    <div class="amt-element-title">
                        <h6>{{ trans('lang.image_opacity') }}</h6>
                    </div>
                    <div class="at-widgetcontent">
                        <a-slider id="imageOpacity" :step="0.1" :min="0" :max="1" :reverse="reverse_slider" @change="onOpacityChange" />
                    </div>
                </div>
            </div>
            <div class="form-group border-top-0">
                <div class="amt-element-title">
                    <h6>{{ trans('lang.padding') }}</h6>
                    <div class="amt-guests-radioholder">
                        <span class="amt-radio"><input id="at-padding-pixal1" type="radio" v-model="two_column.row.padding.unit" value="px"> <label for="at-padding-pixal1">px</label></span>   
                        <span class="amt-radio"><input id="at-padding-percent1" type="radio" v-model="two_column.row.padding.unit" value="%"> <label for="at-padding-percent1">%</label></span>   
                    </div>
                </div>
                <div class="amt-spacing">
                    <ul class="amt-guestsinfo">
                        <li> 
                            <div class="amt-guests-radioholder">
                                <span class="amt-radio"><input type="number" v-model="two_column.row.padding.top"> <label for="at-top">{{ trans('lang.top') }}</label></span>   
                                <span class="amt-radio"><input type="number" v-model="two_column.row.padding.right"> <label for="at-right">{{ trans('lang.right') }}</label></span>   
                                <span class="amt-radio"><input type="number" v-model="two_column.row.padding.bottom"> <label for="at-bottom">{{ trans('lang.bottom') }}</label></span>   
                                <span class="amt-radio"><input type="number" v-model="two_column.row.padding.left"> <label for="at-left">{{ trans('lang.left') }}</label></span>   
                            </div>
                        </li>  
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <div class="amt-element-title">
                    <h6>{{ trans('lang.margin') }}</h6>
                    <div class="amt-guests-radioholder">
                        <span class="amt-radio"><input id="at-margin-pixal1" type="radio" v-model="two_column.row.margin.unit" value="px"> <label for="at-margin-pixal1">px</label></span>   
                        <span class="amt-radio"><input id="at-margin-percent1" type="radio" v-model="two_column.row.margin.unit" value="%"> <label for="at-margin-percent1">%</label></span>   
                    </div>
                </div>
                <div class="amt-spacing">
                    <ul class="amt-guestsinfo">
                        <li> 
                            <div class="amt-guests-radioholder">
                                <span class="amt-radio"><input type="number" v-model="two_column.row.margin.top"> <label for="at-top">{{ trans('lang.top') }}</label></span>   
                                <span class="amt-radio"><input type="number" v-model="two_column.row.margin.right"> <label for="at-right">{{ trans('lang.right') }}</label></span>   
                                <span class="amt-radio"><input type="number" v-model="two_column.row.margin.bottom"> <label for="at-bottom">{{ trans('lang.bottom') }}</label></span>   
                                <span class="amt-radio"><input type="number" v-model="two_column.row.margin.left"> <label for="at-left">{{ trans('lang.left') }}</label></span>   
                            </div>
                        </li>  
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <span class="amt-checkbox">
                    <input id="at-border" type="checkbox" v-model="two_column.row.border" value="1"> 
                    <label for="at-border">{{ trans('lang.add_border') }}</label>
                </span>
            </div>
            <div class="dc-title" v-if="two_column.row.border">
                <div class="form-group">
                    <div class="at-collapseholder collapse show">
                        <div class="amt-element-title">
                            <h6>{{ trans('lang.border_width') }}</h6>
                        </div>
                        <div class="at-widgetcontent">
                            <a-slider id="borderWidth" :defaultValue="1" :min="1" :max="10" :reverse="reverse_slider" @change="onBorderWidthChange" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="amt-element-title">
                        <h6>{{ trans('lang.border_clr') }}</h6>
                        <verte menuPosition="right" model="hex" v-model="two_column.row.borderColor"></verte>
                    </div>
                </div>
            </div>
            <div class="amt-dhb-main_content dc-title">
                <div class="amt-dhb-heading"><h3>{{ trans('lang.sec_style') }}</h3></div>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                 <h6>{{ trans('lang.color') }}</h6>
                <verte menuPosition="right" model="hex" v-model="two_column.sectionBackground"></verte>
            </div>
            <fieldset>
                <div class="form-group">
                    <div class="amt-element-title">
                        <h6>{{ trans('lang.padding') }}</h6>
                        <div class="amt-guests-radioholder">
                            <span class="amt-radio"><input id="at-padding-pixal" type="radio" v-model="two_column.padding.unit" value="px"> <label for="at-padding-pixal">px</label></span>   
                            <span class="amt-radio"><input id="at-padding-percent" type="radio" v-model="two_column.padding.unit" value="%"> <label for="at-padding-percent">%</label></span>   
                        </div>
                    </div>
                    <div class="amt-spacing">
                        <ul class="amt-guestsinfo">
                            <li> 
                                <div class="amt-guests-radioholder">
                                    <span class="amt-radio"><input type="number" v-model="two_column.padding.top"> <label for="at-top">{{ trans('lang.top') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="two_column.padding.right"> <label for="at-right">{{ trans('lang.right') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="two_column.padding.bottom"> <label for="at-bottom">{{ trans('lang.bottom') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="two_column.padding.left"> <label for="at-left">{{ trans('lang.left') }}</label></span>   
                                </div>
                            </li>  
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    <div class="amt-element-title">
                        <h6>{{ trans('lang.margin') }}</h6>
                        <div class="amt-guests-radioholder">
                            <span class="amt-radio"><input id="at-margin-pixal" type="radio" v-model="two_column.margin.unit" value="px"> <label for="at-margin-pixal">px</label></span>   
                            <span class="amt-radio"><input id="at-margin-percent" type="radio" v-model="two_column.margin.unit" value="%"> <label for="at-margin-percent">%</label></span>   
                        </div>
                    </div>
                    <div class="amt-spacing">
                        <ul class="amt-guestsinfo">
                            <li> 
                                <div class="amt-guests-radioholder">
                                    <span class="amt-radio"><input type="number" v-model="two_column.margin.top"> <label for="at-top">{{ trans('lang.top') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="two_column.margin.right"> <label for="at-right">{{ trans('lang.right') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="two_column.margin.bottom"> <label for="at-bottom">{{ trans('lang.bottom') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="two_column.margin.left"> <label for="at-left">{{ trans('lang.left') }}</label></span>   
                                </div>
                            </li>  
                        </ul>
                    </div>
                </div>
                <div class="form-group"><input type="text" v-model="two_column.sectionClass" :placeholder="trans('lang.sec_class')" class="form-control"></div>
                <div class="form-group"><input type="text" v-model="two_column.sectionId" :placeholder="trans('lang.sec_id')" class="form-control"></div>
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
    props:['parent_index', 'element_id', 'two_column', 'pageID', 'cloneElement', 'currentElementIndex', 'currentElementID'],
    data() {
        return {
            baseURL: APP_URL,
            tempUrl:APP_URL+'/uploads/pages/temp/',
            orders:this.getOrderList(),
            imageMounted:true,
            newTwoColumnImage: false,
        }
    },
    mounted: function() {
        if (this.two_column.imageOrder == null || !this.two_column.imageOrder) {
            this.two_column.imageOrder = 'right'
        }
        var self = this
        Event.$on('section-edit', (data) => {
            if (self.two_column.imageOrder == null || !self.two_column.imageOrder) {
                self.two_column.imageOrder = 'right'
            }
            self.imageMounted = false
            setTimeout(function() { 
                self.imageMounted = true
            }, 10);
        })
    },
    methods:{
        removeImage: function(imageType, hiddenID) {
            if (imageType == 'image') {
                if (this.two_column.image) {
                    this.two_column.image = null
                }
            } 
            document.getElementById(hiddenID).remove()
        },
        imageRemoved: function(imageType) {
            if (imageType == 'image') {
                if (this.two_column.image) {
                    this.two_column.image = null
                }
            } 
        },
        onBorderWidthChange(value) {
            this.two_column.row.borderWidth = value
        },
        onWidthChange(value) {
            this.two_column.imageWidth = value
        },
        onOpacityChange(value) {
            this.two_column.imageOpacity = value
        },
        imageAdded: function(hiddenID, imageType, hiddenGenerateImage) {
            var self = this
            setTimeout(function(){ 
                if (document.querySelector('#'+hiddenID+ ' .at-profile-setting__imgs ul li [type="hidden"]')) {
                   var hidddenImage = document.querySelector('#'+hiddenID+ ' .at-profile-setting__imgs ul li [type="hidden"]');
                    let image = hidddenImage.value
                    if (imageType == 'image') {
                        if (image) {
                            self.two_column.image = image
                            Event.$emit('new-twoColumn-image'+self.currentElementID)
                            self.newTwoColumnImage = true
                        } else {
                            self.two_column.image = null
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
            if (type == 'image') {
                this.newTwoColumnImage = false
            }
        },
        OldImageError (type) {
            if (type == 'image') {
                this.newTwoColumnImage = true
            }
        }
    },
};
</script>
