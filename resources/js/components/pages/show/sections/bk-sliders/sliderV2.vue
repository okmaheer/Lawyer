<template>
    <div class="dc-homeslidervtwo dc-haslayout">
        <carousel :nav='false' :items='1' :dots='false' :autoplay='true' v-if="filtersApplied && slides.slides.slide.slides" class="dc-bannervtwo">
            <div class="item" v-for="(sliderItem,index) in slides.slides.slide.slides" :key="index">
                <figure class="dc-silderimg">
                    <img :src="baseUrl+'/uploads/sliders/'+slides.id+'/'+sliderItem.image"  alt="image">
                </figure>
            </div>
        </carousel>
        <div class="dc-bannervtwocontent">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                        <div class="dc-medicalfacility">
                            <div class="dc-title">
                                <h2>
                                    <em>{{slides.slides.slide.search_subtitle1}}</em>
                                    <span> {{slides.slides.slide.search_subtitle2}} </span>
                                    {{slides.slides.slide.search_title}}
                                </h2>
                            </div>
                            <form method="get" :action="baseUrl+'/search-results'" id="search_form" class="dc-formtheme dc-medicalform">
                                <fieldset>
                                    <div class="form-group">
                                        <input type="text" name="search" value="" class="form-control" :placeholder="trans('lang.ph.hospitals_clinic_etc')">
                                    </div>
                                    <div class="form-group">
                                        <span class="dc-select">
                                            <select 
                                                class="locations" 
                                                :data-placeholder="locationPlaceHolder" 
                                                name="locations"
                                                v-html="locationsList">
                                            </select>
                                        </span>
                                    </div>
                                    <div class="form-group dc-btnarea">
                                        <!-- <a href="javascript:void(0);" class="dc-docsearch">
                                            <span class="dc-advanceicon"> <i></i> <i></i> <i></i></span>
                                            <span>Advanced <br>Search</span>
                                        </a> -->
                                        <a href="javascript:void(0);" class="dc-btn" v-on:click.prevent="submitSearch">search Now</a>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                    <div class="d-none col-md-6 d-lg-block">
                        <figure class="dc-slidercontentimg">
                            <img  :src="baseUrl+'/uploads/sliders/'+slides.id+'/'+slides.slides.slide.slider_inner_image"  alt="image">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
        <!-- <skeletonv2  v-if="skeleton" items="1"/> -->
    </div>
</template>
<script>
import Event from '../../../../../event'
import skeletonv2 from '../../skeleton/sliderv2'
import carousel from 'vue-owl-carousel'
export default {
    props:['parent_index', 'element_id', 'slider_section', 'pageID', 'slides', 'filtersApplied', 'roles', 'skeletonApplied'],
    components:{carousel, skeletonv2},
    data() {
        return {
            isActive:false,
            baseUrl: APP_URL,
            tempUrl:APP_URL+'/uploads/pages/temp/',
            locationsList:'',
            searchPlaceHolder:'',
            locationPlaceHolder:'',
            specialities: [],
            show_speciality: false,
            role_type: 'doctor',
            skeleton:this.skeletonApplied,
        }
    },
    computed: {
        sectionStyle() {
            return {
                padding: `${this.slider.padding.top}${this.slider.padding.unit} ${this.slider.padding.right}${this.slider.padding.unit} ${this.slider.padding.bottom}${this.slider.padding.unit} ${this.slider.padding.left}${this.slider.padding.unit}`,
                margin: `${this.slider.margin.top}${this.slider.margin.unit} ${this.slider.margin.right}${this.slider.margin.unit} ${this.slider.margin.bottom}${this.slider.margin.unit} ${this.slider.margin.left}${this.slider.margin.unit}`,
                'text-align': this.slider.alignment,
            }
        },
        linkStyle() {
            return {
                color : this.slider.color,
            }
        },
        sliderStyle() {
            return {
                'text-align': this.slider.alignment,
                color : this.slider.color,
            }
        },
    },
    mounted: function() {
        this.isActive = false
        var self= this
        Event.$on('slider-section-update', (data) => {
            setTimeout(function(){ 
                self.isActive = !self.isActive;
            }, 10);
        })
        self.getLocations()
        self.skeleton = false
    },
    methods:{
        submitSearch: function() {
            document.getElementById("search_form").submit();
        },
        getLocations: function () {
            var self = this;
            axios.get(APP_URL + '/search/location-list')
            .then(function (response) {
                self.locationsList = response.data;
                setTimeout(function(){ 
                    jQuery("body select.locations").msDropDown();
                }, 100);
            })
        },
    }
};
</script>
