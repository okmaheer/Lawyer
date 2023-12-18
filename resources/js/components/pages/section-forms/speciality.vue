<template>
    <div class="element-form-wrapper" v-if="speciality">
        <div class="amt-dhb-main_content">
            <div class="amt-dhb-heading"><h3>{{ trans('lang.content') }}</h3></div>
        </div>
        <div class="amt-formcontactus">
            <fieldset>
                <div class="form-group"><input type="text" v-model="speciality.title" :placeholder="trans('lang.section_title')" class="form-control"></div>
                <div class="form-group">
                    <span class="dc-select">
                        <select class="form-control" v-model="speciality.specialityID" v-on:change="getServices(speciality.specialityID)">
                            <option value="" selected>{{ trans('lang.select_speciality') }}</option>
                            <option v-for="(specialityItem, index) in specialities" :key="index" :value="specialityItem.id"> 
                                {{specialityItem.title}} 
                            </option>
                        </select>
                    </span>
                </div>
            </fieldset>
            <div class="amt-dhb-main_content">
                <div class="amt-dhb-heading"><h3>{{ trans('lang.style') }}</h3></div>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                <h6>{{ trans('lang.title_clr') }}</h6>
                <verte menuPosition="right" model="hex" v-model="speciality.titleColor"></verte>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                <h6>{{ trans('lang.sutitle_clr') }}</h6>
                <verte menuPosition="right" model="hex" v-model="speciality.subtitleColor"></verte>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                <h6>{{ trans('lang.content_clr') }}</h6>
                <verte menuPosition="right" model="hex" v-model="speciality.contentColor"></verte>
            </div>
                <div class="amt-dhb-main_content">
                <div class="amt-dhb-heading"> <h3>{{ trans('lang.sec_style') }}</h3></div>
            </div>
            <div class="amt-element-title amt-element-titlecontent">
                <h6>{{ trans('lang.content_scn_bckgrnd') }}</h6>
                <verte menuPosition="right" model="hex" v-model="speciality.contentBackground"></verte>
            </div>
            <fieldset>
                <div class="form-group">
                    <div class="amt-element-title">
                         <h6>{{ trans('lang.padding') }}</h6>
                        <div class="amt-guests-radioholder">
                            <span class="amt-radio"><input id="at-padding-pixal" type="radio" v-model="speciality.padding.unit" value="px"> <label for="at-padding-pixal">px</label></span>   
                            <span class="amt-radio"><input id="at-padding-percent" type="radio" v-model="speciality.padding.unit" value="%"> <label for="at-padding-percent">%</label></span>   
                        </div>
                    </div>
                    <div class="amt-spacing">
                        <ul class="amt-guestsinfo">
                            <li> 
                                <div class="amt-guests-radioholder">
                                    <span class="amt-radio"><input type="number" v-model="speciality.padding.top"> <label for="at-top">{{ trans('lang.top') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="speciality.padding.right"> <label for="at-right">{{ trans('lang.right') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="speciality.padding.bottom"> <label for="at-bottom">{{ trans('lang.bottom') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="speciality.padding.left"> <label for="at-left">{{ trans('lang.left') }}</label></span>   
                                </div>
                            </li>  
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    <div class="amt-element-title">
                        <h6>{{ trans('lang.margin') }}</h6>
                        <div class="amt-guests-radioholder">
                            <span class="amt-radio"><input id="at-margin-pixal" type="radio" v-model="speciality.margin.unit" value="px"> <label for="at-margin-pixal">px</label></span>   
                            <span class="amt-radio"><input id="at-margin-percent" type="radio" v-model="speciality.margin.unit" value="%"> <label for="at-margin-percent">%</label></span>   
                        </div>
                    </div>
                    <div class="amt-spacing">
                        <ul class="amt-guestsinfo">
                            <li> 
                                <div class="amt-guests-radioholder">
                                    <span class="amt-radio"><input type="number" v-model="speciality.margin.top"> <label for="at-top">{{ trans('lang.top') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="speciality.margin.right"> <label for="at-right">{{ trans('lang.right') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="speciality.margin.bottom"> <label for="at-bottom">{{ trans('lang.bottom') }}</label></span>   
                                    <span class="amt-radio"><input type="number" v-model="speciality.margin.left"> <label for="at-left">{{ trans('lang.left') }}</label></span>   
                                </div>
                            </li>  
                        </ul>
                    </div>
                </div>
                <div class="form-group"><input type="text" v-model="speciality.sectionClass" :placeholder="trans('lang.sec_class')" class="form-control"></div>
                <div class="form-group"><input type="text" v-model="speciality.sectionId" :placeholder="trans('lang.sec_id')" class="form-control"></div>
            </fieldset>
        </div>
    </div>
</template>
<script>
import Event from '../../../event'

export default {
    props:['parent_index', 'element_id', 'speciality', 'pageID'],
    data() {
        return {
            specialities:{},
            isActive:false
        }
    },
    created: function() {
        this.getSpecialities()
    },
    methods:{
        getSpecialities() {
            var self = this;
            axios.get(APP_URL + '/get-specialities')
            .then(function (response) {
                if (response.data.type === 'success') {
                    self.specialities = response.data.specialities
                } 
            })
            .catch(function (error) {

            })
        },
        getServices(id) {
            var self = this;
            axios.get(APP_URL + '/section/get-speciality-data/'+id)
            .then(function (response) {
                if (response.data.type === 'success') {
                    self.speciality.detail = response.data.detail
                } 
            })
            .catch(function (error) {

            })
        }
    }
};
</script>
