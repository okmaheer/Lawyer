<template>
    <div class="element-preview-wrapper" v-on:click="editElement">
        <section 
            :class="search.sectionClass + 'dc-haslayout'" 
            :id="search.sectionId" 
            v-if="Object.entries(search).length != 0"
        >
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="dc-searchform-holder" :style="sectionStyle">
                            <div class="dc-advancedsearch">
                                <div class="dc-title">
                                    <h2>{{ search.title }}</h2>
                                    <a href="javascript:void(0);" class="dc-docsearch" v-on:click="displayFilfer" v-if="selected_search_form_type == 'multiple_steps_searching'">
                                        <span class="dc-advanceicon"><i></i> <i></i> <i></i></span>
                                        <span>{{ trans('lang.advanced') }} <br>{{ trans('lang.search') }}</span>
                                    </a>
                                </div>
                                <div class="dc-formtheme dc-form-advancedsearch dc-advanceserchvtwo" v-if="selected_search_form_type == 'global_searching'">
                                    <fieldset>
                                        <search-component :typeahead_id="'multiple-datasets'"/>
                                    </fieldset>
                                </div>
                                <form method="get" :action="baseUrl+'/search-results'" id="search_form" class="dc-formtheme dc-form-advancedsearch" v-else-if="selected_search_form_type == 'multiple_steps_searching'">
                                    <fieldset>
                                        <div class="form-group">
                                            <input type="text" name="search" value="" class="form-control" :placeholder="trans('lang.ph.hospitals_clinic_etc')">
                                        </div>
                                        <div class="form-group">
                                            <div class="dc-select">
                                                <select 
                                                    class="chosen-select locations" 
                                                    :data-placeholder="locationPlaceHolder" 
                                                    name="locations"
                                                    v-html="locationsList">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="dc-formbtn">
                                            <a href="" class="btn-sm" v-on:click.prevent="submitSearch"><i class="ti-arrow-right"></i></a>
                                        </div>
                                    </fieldset>
                                    <fieldset style="display: none;" class="dc-home-advancedsearch">
                                        <div class="form-group">
                                            <div class="dc-select">
                                                <select class="chosen-select" name="type">
                                                    <option value="both" selected>{{ trans('lang.both') }}</option>
                                                    <option 
                                                        v-for="(role, index) in roles" 
                                                        :key="index" :value="role.role_type" 
                                                        v-if="role.role_type != 'admin' && role.role_type != 'regular'">
                                                        {{ role.name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <speciality-services 
                                        :specialities="specialities" 
                                        v-if="show_speciality"
                                        :speciality_value_type="'slug'"
                                        :service_value_type="'slug'"
                                        v-cloak >
                                        </speciality-services>
                                    </fieldset>
                                </form>
                            </div>
                            <div class="dc-jointeamholder">
                                <div class="dc-jointeam" :style="{background:search.bannerBackground}">
                                    <span class="dc-jointeamnoti"><i class="ti-light-bulb"></i></span>
                                    <figure class="dc-jointeamimg" v-if="search.image">
                                        <img 
                                            :src="tempUrl+search.image" 
                                            alt="img description" 
                                            v-if="newImage">
                                        <img 
                                            :src="baseUrl+'/uploads/pages/'+pageID+'/'+search.image" 
                                            alt="img description" 
                                            v-else-if="pageID">
                                    </figure>
                                    <figure class="dc-jointeamimg" v-else>
                                        <img 
                                            :src="baseUrl+'/uploads/settings/home/small-1569052927-img-04.png'" 
                                            alt="img description"
                                        />
                                    </figure>
                                    <div class="dc-jointeamcontent">
                                        <h3 v-if="search.bannerHeading || search.bannerSubheading" :style="{color:search.bannerHeadingColor}">
                                            <span v-if="search.bannerSubheading" :style="{color:search.bannerSubheadingColor}"> {{ search.bannerSubheading }}</span>
                                            {{ search.bannerHeading }}
                                        </h3>
                                        <a :href="search.bannerUrl" class="dc-btn dc-btnactive" v-if="search.bannerButton">{{ search.bannerButton }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
import Event from '../../../event'
export default {
    props:['parent_index', 'element_id', 'searchForm', 'pageID', 'locations', 'roles', 'selected_search_form_type'],
    data() {
        return {
            search:{},
            isActive:false,
            baseUrl: APP_URL,
            tempUrl:APP_URL+'/uploads/pages/temp/',
            searchPlaceHolder:'',
            locationPlaceHolder:'',
            specialities: [],
            show_speciality: false,
            role_type: 'doctor',
            newImage: false,
            locationsList:'',
        }
    },
    computed: {
        sectionStyle() {
            return {
                padding: `${this.search.padding.top}${this.search.padding.unit} ${this.search.padding.right}${this.search.padding.unit} ${this.search.padding.bottom}${this.search.padding.unit} ${this.search.padding.left}${this.search.padding.unit}`,
                margin: `${this.search.margin.top}${this.search.margin.unit} ${this.search.margin.right}${this.search.margin.unit} ${this.search.margin.bottom}${this.search.margin.unit} ${this.search.margin.left}${this.search.margin.unit}`,
                'text-align': this.search.alignment,
                background: this.search.background
            }
        },
    },
    updated: function() {
        var index = this.getArrayIndex(this.searchForm, 'id', this.element_id)
        if (this.search[index]) {
            this.search = this.search[index]
        }
        this.search.id = this.element_id
    },
    mounted: function() {
        this.isActive = false
        var self= this
        Event.$on('search-section-update', (data) => {
            setTimeout(function(){ 
                self.isActive = !self.isActive;
            }, 10);
        })
        Event.$on('new-search-image-added'+self.element_id, (data) => {
            this.newImage = true
        })
        self.getLocations()
    },
    methods:{
        submitSearch: function() {
            document.getElementById("search_form").submit();
        },
        displayFilfer: function () {
            jQuery('.dc-home-advancedsearch').slideToggle(400);
            this.getSpecialities();
        },
        getSpecialities: function () {
            var self = this;
            axios.get(APP_URL + '/get-specialities')
                .then(function (response) {
                    if (response.data.type == 'success') {
                        self.specialities = response.data.specialities;
                        self.show_speciality = true;
                    }
                })
        },
        getLocations: function () {
            var self = this;
            axios.get(APP_URL + '/search/location-list')
            .then(function (response) {
                self.locationsList = response.data;
                setTimeout(function(){ 
                    jQuery("body select.locations").msDropDown();
                }, 50);
            })
        },
        editElement: function() {
            var self = this
            this.$emit("editData");
        },
    },
    created: function() {
        var self = this
        setTimeout(function(){ 
            var index = self.getArrayIndex(self.searchForm, 'id', self.element_id)
            if (self.searchForm[index]) {
                self.search = self.searchForm[index]
            }
        }, 100);
    },
};
</script>
