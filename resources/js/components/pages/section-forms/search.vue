<template>
    <div class="element-form-wrapper" v-if="search">
        <div class="amt-dhb-main_content">
            <div class="amt-dhb-heading"><h3>{{ trans('lang.content') }}</h3></div>
        </div>
        <div class="amt-element-title amt-element-titlecontent">
            <h6>{{ trans('lang.search_cntnt_sec') }}</h6>
        </div>
        <div class="amt-formcontactus">
            <fieldset>
                <div class="form-group"><input type="text" v-model="search.title" :placeholder="trans('lang.title')" class="form-control"></div>
                <div 
                    class="at-profile-setting__upload search_image_section dc-settingscontent" 
                    :id="'section_image_wrapper'+currentElementID"
                    v-if="searchImageMounted"
                >
                    <div class="amt-element-title amt-element-titlecontent">
                        <h6>{{ trans('lang.search_bnr_sec') }}</h6>
                    </div>
                    <page-media
                        :parent_id="'section_image_wrapper'+currentElementID"
                        :id="'search_image_section'+currentElementID"
                        :img_ref="'search_image_section'+currentElementID"
                        :name="'search_image_section'"
                        :url="baseURL+'/media/upload-temp-image/pages/search_image_section'"
                        :preview_class="'search_image_section_preview'+currentElementID"
                        :hidden_input_id="'hidden_input_id_search_image_section'+currentElementID"
                        :list_id="'list_id_search_image'+currentElementID"
                        :upload_title="'upload image'"
                        :btn_text="'select file'"
                        @addedFile="imageAdded('section_image_wrapper'+currentElementID, 'image', 'search_hiddenImage'+currentElementID)"
                        @fileRemoved="imageRemoved()"
                    >
                    </page-media>
                    <div class="at-profile-setting__imgs" id="search_image">
                        <ul :class="'search_image_section_preview'+currentElementID">
                            <li :id="'search_hiddenImage'+currentElementID" v-if="search.image">
                                <input id="radio1" type="radio" name="radio">
                                <label for="radio1">
                                    <span>
                                        <img :src="baseURL+'/uploads/pages/'+pageID+'/'+search.image" @error="OldImageError()" v-if="pageID && !newImage">
                                        <img :src="tempUrl+search.image" @error="TempImageError()" v-else-if="!pageID || newImage">
                                        <span class="at-trash">
                                            <a href="javascript:void(0);" v-on:click="removeImage()">
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
                                <input type="hidden" :value="search.image" name="image" :id="'search_hiddenImage'+currentElementID">
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="form-group"><input type="text" v-model="search.bannerSubheading" :placeholder="trans('lang.title')" class="form-control"></div>
                <div class="form-group"><input type="text" v-model="search.bannerHeading" :placeholder="trans('lang.title')" class="form-control"></div>
                <div class="form-group"><input type="text" v-model="search.bannerButton" :placeholder="trans('lang.title')" class="form-control"></div>
                <div class="form-group"><input type="text" v-model="search.bannerUrl" :placeholder="trans('lang.title')" class="form-control"></div>
            </fieldset>
            <div class="amt-dhb-main_content">
                <div class="amt-dhb-heading"><h3>{{ trans('lang.style') }}</h3></div>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                <h6>{{ trans('lang.bnr_title_style') }}</h6>
                <verte menuPosition="right" model="hex" v-model="search.bannerHeadingColor"></verte>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                <h6>{{ trans('lang.bnr_sub_style') }}</h6>
                <verte menuPosition="right" model="hex" v-model="search.bannerSubheadingColor"></verte>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                <h6>{{ trans('lang.bnr_clr') }}</h6>
                <verte menuPosition="right" model="hex" v-model="search.bannerBackground"></verte>
            </div>
            <div class="amt-dhb-main_content">
                <div class="amt-dhb-heading"> <h3>{{ trans('lang.sec_style') }}</h3></div>
            </div>
            <fieldset>
                <div class="form-group">
                    <div class="amt-element-title">
                         <h6>{{ trans('lang.padding') }}</h6>
                        <div class="amt-guests-radioholder">
                            <span class="amt-radio"><input id="at-padding-pixal" type="radio" v-model="search.padding.unit" value="px"> <label for="at-padding-pixal">px</label></span>   
                            <span class="amt-radio"><input id="at-padding-percent" type="radio" v-model="search.padding.unit" value="%"> <label for="at-padding-percent">%</label></span>   
                        </div>
                    </div>
                    <div class="amt-spacing">
                        <ul class="amt-guestsinfo">
                            <li> 
                                <div class="amt-guests-radioholder">
                                    <span class="amt-radio"><input type="number" v-model="search.padding.top"> <label for="at-top">{{ trans('lang.top') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="search.padding.right"> <label for="at-right">{{ trans('lang.right') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="search.padding.bottom"> <label for="at-bottom">{{ trans('lang.bottom') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="search.padding.left"> <label for="at-left">{{ trans('lang.left') }}</label></span>   
                                </div>
                            </li>  
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    <div class="amt-element-title">
                        <h6>{{ trans('lang.margin') }}</h6>
                        <div class="amt-guests-radioholder">
                            <span class="amt-radio"><input id="at-margin-pixal" type="radio" v-model="search.margin.unit" value="px"> <label for="at-margin-pixal">px</label></span>   
                            <span class="amt-radio"><input id="at-margin-percent" type="radio" v-model="search.margin.unit" value="%"> <label for="at-margin-percent">%</label></span>   
                        </div>
                    </div>
                    <div class="amt-spacing">
                        <ul class="amt-guestsinfo">
                            <li> 
                                <div class="amt-guests-radioholder">
                                    <span class="amt-radio"><input type="number" v-model="search.margin.top"> <label for="at-top">{{ trans('lang.top') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="search.margin.right"> <label for="at-right">{{ trans('lang.right') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="search.margin.bottom"> <label for="at-bottom">{{ trans('lang.bottom') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="search.margin.left"> <label for="at-left">{{ trans('lang.left') }}</label></span>   
                                </div>
                            </li>  
                        </ul>
                    </div>
                </div>
                <div class="form-group"><input type="text" v-model="search.sectionClass" :placeholder="trans('lang.sec_class')" class="form-control"></div>
                <div class="form-group"><input type="text" v-model="search.sectionId" :placeholder="trans('lang.sec_id')" class="form-control"></div>
            </fieldset>
        </div>
    </div>
</template>
<script>
import Event from '../../../event'
export default {
    props:['search', 'pageID', 'cloneElement', 'currentElementID'],
    data() {
        return {
            baseURL: APP_URL,
            tempUrl:APP_URL+'/uploads/pages/temp/',
            searchImageMounted:true,
            newImage:false,
        }
    },
    mounted: function() {
        var self = this
        Event.$on('section-edit', (data) => {
            self.searchImageMounted = false
            setTimeout(function(){ 
                self.searchImageMounted = true
            }, 10);
        })
    },
    methods:{
        removeImage: function() {
            this.search.image = null
            document.getElementById('search_hiddenImage'+this.currentElementID).remove()
        },
        imageRemoved: function() {
            if (this.cloneElement == false) {
                if (this.search.image) {
                    this.search.image = null
                } 
            }
        },
        imageAdded: function(hiddenID, imageType, hiddenGenerateImage) {
            var self = this
            setTimeout(function(){ 
               if (document.querySelector('#'+hiddenID+ ' .at-profile-setting__imgs ul li [type="hidden"]')) {
                    var hidddenImage = document.querySelector('#'+hiddenID+ ' .at-profile-setting__imgs ul li [type="hidden"]');
                    let image = hidddenImage.value
                    if (image) {
                        self.search.image = image
                        Event.$emit('new-search-image-added'+self.currentElementID)
                        self.newImage = true
                    } else {
                        self.search.image = null
                    }
                } 
            }, 100);
            setTimeout(function(){ 
                if (document.getElementById(hiddenGenerateImage)) {
                    document.getElementById(hiddenGenerateImage).remove()
                }
            }, 110);
        },
        TempImageError () {
            this.newImage = false
        },
        OldImageError () {
            this.newImage = true
        }
    },
};
</script>
