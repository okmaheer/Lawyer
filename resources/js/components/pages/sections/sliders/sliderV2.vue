<template>
    <div class="dc-homeslidervtwo dc-haslayout">
            <div id="dc-bannervtwo" class="dc-bannervtwo owl-carousel">
                <div class="item" v-for="(sliderItem,index) in slides.slides.slide.slides" :key="index">
                    <figure class="dc-silderimg">
                        <img :src="baseUrl+'/uploads/sliders/'+slides.id+'/'+sliderItem.image"  alt="image">
                    </figure>
                </div>
            </div>
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
                                <div class="dc-formtheme dc-medicalform dc-advanceserchvtwo" v-if="selected_search_form_type == 'global_searching'">
                                    <fieldset>
                                        <search-component :typeahead_id="'multiple-datasets-s'"/>
                                    </fieldset>
                                </div>
                                <form method="get" :action="baseUrl+'/search-results'" id="search_form" class="dc-formtheme dc-medicalform" v-else-if="selected_search_form_type == 'multiple_steps_searching'">
									<fieldset>
										<div class="form-group">
											<input type="text" name="search" value="" class="form-control" :placeholder="trans('lang.ph.hospitals_clinic_etc')">
										</div>
										<div class="form-group">
											<span class="dc-select">
												<select 
                                                    class="chosen-select locations" 
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
		</div>
</template>
<script>
import Event from '../../../../event'
import carousel from 'vue-owl-carousel'
export default {
    props:['parent_index', 'element_id', 'slider_section', 'pageID', 'slides', 'filtersApplied', 'roles', 'selected_search_form_type'],
    components:{carousel},
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
    updated () {
        /* BANNER SLIDER V TWO	*/
        console.log('dc-bannervtwo')
        var _dc_bannervtwo = jQuery("#dc-bannervtwo")
        _dc_bannervtwo.owlCarousel({
            items:1,
            loop:true,
            nav:false,
            rtl:true,
            autoplay:true,
            smartSpeed:450,
            mouseDrag: false,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            autoplayHoverPause:false
        });
    },
    mounted: function() {
        this.isActive = false
        var self= this
        Event.$on('slider-section-update', (data) => {
            setTimeout(function(){ 
                self.isActive = !self.isActive;
            }, 10);
        })
        // Slider Mounted 
         var _dc_bannervtwo = jQuery("#dc-bannervtwo")
        _dc_bannervtwo.owlCarousel({
            items:1,
            loop:true,
            nav:false,
            rtl:true,
            autoplay:true,
            smartSpeed:450,
            mouseDrag: false,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            autoplayHoverPause:false
        });
        // End slider 
        self.getLocations()
    },
    methods:{
        submitSearch: function() {
            document.getElementById("search_form").submit();
        },
        displayFilfer: function (id) {
            jQuery('#advancedsearchv2'+id).slideToggle(400);
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
    }
};
</script>
