<template>
    <div class="element-form-wrapper" v-if="image">
        <div class="at-dhb-heading"><h3>{{ trans('lang.content') }}</h3></div>
        <fieldset>
            <div class="form-group at-profile-setting__upload image_section dc-settingscontent dc-tabsinfo" id="section_image_wrapper">
                <page-media
                    :parent_id="'section_image_wrapper'"
                    :id="'image_section'"
                    :img_ref="'image_section'"
                    :name="'image_section'"
                    :url="baseURL+'/media/upload-temp-image/pages/image_section'"
                    :preview_class="'image_section_preview'"
                    :hidden_input_id="'hidden_input_id_image_section'"
                    :list_id="'list_id_image'"
                    :upload_title="'upload image'"
                    :btn_text="'select file'"
                    @addedFile="imageAdded()"
                    @fileRemoved="imageRemoved()"
                >
                </page-media>
                <div class="at-profile-setting__imgs">
                    <ul class="image_section_preview">
                        <li id="imageId" v-if="image.url">
                            <input id="radio1" type="radio" name="radio">
                            <label for="radio1">
                                <span>
                                    <img :src="baseURL+'/uploads/pages/'+pageID+'/'+image.url" v-if="pageID">
                                    <img :src="tempUrl+image.url" v-else>
                                    <span class="at-trash">
                                        <a href="javascript:void(0);" v-on:click="removeImage('image')">
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
                            <input type="hidden" :value="image.url" name="image" id="hidden">
                        </li>
                    </ul>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <span class="at-checkbox at-checkboxvtwo">
                <input id="at-headingLink" type="checkbox" v-model="image.link" value="1"> 
                <label for="at-headingLink">add link</label>
            </span>
            <div class="form-group" v-if="image.link"><input type="text" v-model="image.linkURL" placeholder="type url" class="form-control"></div>
            <span class="at-checkbox at-checkboxvtwo" v-if="image.link">
                <input id="at-headingAction" type="checkbox" v-model="image.action" value="blank"> 
                <label for="at-headingAction">Open in new window</label>
            </span>
            <div class="form-group">
                <ul class="at-guestsinfo">
                    <li> 
                        <div class="at-guests-radioholder">
                            <span class="at-radio"><input id="at-left" type="radio" v-model="image.alignment" value="flex-start"> <label for="at-left">{{ trans('lang.left') }}</label></span>   
                            <span class="at-radio"><input id="at-center" type="radio" v-model="image.alignment" value="center"> <label for="at-center">center</label></span>   
                            <span class="at-radio"><input id="at-right" type="radio" v-model="image.alignment" value="flex-end"> <label for="at-right">{{ trans('lang.right') }}</label></span>   
                        </div>
                    </li>  
                </ul>
            </div>
        </fieldset>
        <div class="at-dhb-heading"><h3>{{ trans('lang.style') }}</h3></div>
            <div class="at-collapseholder collapse show">
                <div class="at-widgetcontent">
                    <a-slider id="imageWidth" :defaultValue="0"  @change="onWidthChange" />
                </div>
                <div class="at-widgetcontent">
                    <div class="at-guests-radioholder">
                        <span class="at-radio"><input id="at-width-pixal" type="radio" v-model="image.widthUnit" value="px"> <label for="at-width-pixal">px</label></span>   
                        <span class="at-radio"><input id="at-width-percent" type="radio" v-model="image.widthUnit" value="%"> <label for="at-width-percent">%</label></span>   
                    </div>
                </div>
            </div>
            <div class="at-collapseholder collapse show">
                <div class="at-widgetcontent">
                    <a-slider id="imageHeight" :defaultValue="0"  @change="onHeightChange" />
                </div>
                <div class="at-widgetcontent">
                    <div class="at-guests-radioholder">
                        <span class="at-radio"><input id="at-height-pixal" type="radio" v-model="image.heightUnit" value="px"> <label for="at-height-pixal">px</label></span>   
                        <span class="at-radio"><input id="at-height-percent" type="radio" v-model="image.heightUnit" value="%"> <label for="at-height-percent">%</label></span>   
                    </div>
                </div>
            </div>
            <div class="at-collapseholder collapse show">
                <div class="at-widgetcontent">
                    <a-slider id="imageOpacity" :step="0.1" :min="0" :max="1"  @change="onOpacityChange" />
                </div>
            </div>
            <div class="form-group"><input type="text" v-model="image.elementClass" :placeholder="trans('lang.elem_class')" class="form-control"></div>
            <div class="form-group"><input type="text" v-model="image.elementId" :placeholder="trans('lang.elem_id')" class="form-control"></div>
        <div class="at-dhb-heading"> <h3>{{ trans('lang.sec_style') }}</h3></div>
        <fieldset>
            <verte display="widget" menuPosition="right" model="hex" v-model="image.background"></verte>
            <div class="form-group">
                <ul class="at-guestsinfo">
                    <li> 
                        <div class="at-guests-radioholder">
                            <span class="at-radio"><input type="number" v-model="image.padding.top"> <label for="at-top">{{ trans('lang.top') }}</label></span>   
                            <span class="at-radio"><input type="number" v-model="image.padding.right"> <label for="at-right">{{ trans('lang.right') }}</label></span>   
                            <span class="at-radio"><input type="number" v-model="image.padding.bottom"> <label for="at-bottom">{{ trans('lang.bottom') }}</label></span>   
                            <span class="at-radio"><input type="number" v-model="image.padding.left"> <label for="at-left">{{ trans('lang.left') }}</label></span>   
                        </div>
                    </li>  
                </ul>
                <div class="at-widgetcontent">
                    <div class="at-guests-radioholder">
                        <span class="at-radio"><input id="at-padding-pixal" type="radio" v-model="image.padding.unit" value="px"> <label for="at-padding-pixal">px</label></span>   
                        <span class="at-radio"><input id="at-padding-percent" type="radio" v-model="image.padding.unit" value="%"> <label for="at-padding-percent">%</label></span>   
                    </div>
                </div>
                
            </div>
            <div class="form-group">
                <ul class="at-guestsinfo">
                    <li> 
                        <div class="at-guests-radioholder">
                            <span class="at-radio"><input type="number" v-model="image.margin.top"> <label for="at-top">{{ trans('lang.top') }}</label></span>   
                            <span class="at-radio"><input type="number" v-model="image.margin.right"> <label for="at-right">{{ trans('lang.right') }}</label></span>   
                            <span class="at-radio"><input type="number" v-model="image.margin.bottom"> <label for="at-bottom">{{ trans('lang.bottom') }}</label></span>   
                            <span class="at-radio"><input type="number" v-model="image.margin.left"> <label for="at-left">{{ trans('lang.left') }}</label></span>   
                        </div>
                    </li>  
                </ul>
            </div>
            <div class="form-group">
                <div class="at-widgetcontent">
                    <div class="at-guests-radioholder">
                        <span class="at-radio"><input id="at-margin-pixal" type="radio" v-model="image.margin.unit" value="px"> <label for="at-margin-pixal">px</label></span>   
                        <span class="at-radio"><input id="at-margin-percent" type="radio" v-model="image.margin.unit" value="%"> <label for="at-margin-percent">%</label></span>   
                    </div>
                </div>
            </div>
            <div class="form-group"><input type="text" v-model="image.sectionClass" :placeholder="trans('lang.sec_class')" class="form-control"></div>
            <div class="form-group"><input type="text" v-model="image.sectionId" :placeholder="trans('lang.sec_id')" class="form-control"></div>
        </fieldset>
    </div>
</template>
<script>
import Event from '../../../event'
export default {
    props:['parent_index', 'element_id', 'image', 'pageID'],
    data() {
        return {
            baseURL: APP_URL,
            tempUrl:APP_URL+'/uploads/pages/temp/',
        }
    },
    mounted: function() {
        
    },
    methods:{
        removeImage: function() {
            this.image.url = null
            document.getElementById('imageId').remove()
        },
        imageRemoved: function() {
            if (this.image.url) {
                this.image.url = null
            }
        },
        onWidthChange(value) {
            this.image.width = value
        },
        onHeightChange(value) {
            this.image.height = value
        },
        onOpacityChange(value) {
            this.image.opacity = value
        },
        imageAdded: function() {
            var self = this
            setTimeout(function(){ 
               if (document.querySelector('#section_image_wrapper .at-profile-setting__imgs ul li input[type=hidden]')) {
                    var hidddenIcon = document.querySelector('#section_image_wrapper .at-profile-setting__imgs ul li input[type=hidden]');
                    let image = document.getElementById(hidddenIcon.id).value
                    self.image.url = image
                } else {
                    self.image.url = null
                }
            }, 50);
            setTimeout(function(){ 
                if (document.getElementById('imageId')) {
                    document.getElementById('imageId').remove()
                }
            }, 60);
        }
    },
};
</script>
