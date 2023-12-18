<template>
    <div>
        <div 
            class="dc-homesliderholder dc-haslayout" 
            :id="'style-v1-'+slides.id" 
            v-bind:style="{ 'background-image': 'url(' + baseUrl+'/uploads/sliders/'+slides.id+'/'+slides.slides.slider_bg_img + ')' }"
            >
            <div id="dc-homeslider" class="dc-homeslider">
                <div id="dc-bannerslider" class="dc-bannerslider carousel slide" data-ride="false" data-interval="false">
                    <ol class="carousel-indicators dc-bannerdots">
                        <li data-target="#dc-bannerslider" data-slide-to="0" class="active"></li>
                        <li data-target="#dc-bannerslider" data-slide-to="1"></li>
                        <li data-target="#dc-bannerslider" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <carousel :nav='true' :items='1' :dots='false' :autoplay='true' v-if="filtersApplied">
                            <div  v-for="(sliderItem,index) in slides.slides.slide" :key="index">
                                <div class="d-flex justify-content-center dc-craousel-content">
                                    <div class="mx-auto">
                                        <img 
                                            class="d-block dc-bannerimg" 
                                            :src="baseUrl+'/uploads/sliders/'+slides.id+'/'+sliderItem.hidden_slide_inner_image" 
                                            alt="image"
                                        >
                                        <div class="dc-bannercontent dc-bannercotent-craousel" >
                                            <div class="dc-content-carousel">
                                                <div class="dc-num" v-if="index+1 < 10 ">0{{ index +1}}.</div>
                                                <div class="dc-num" v-else>{{ index }}.</div>
                                                <h1>
                                                    <em>{{ sliderItem.slide_title_one }}</em> 
                                                    {{ sliderItem.slide_title_two }}
                                                    <span> {{ sliderItem.slide_title_three }}</span>
                                                </h1>
                                                <div class="dc-btnarea" v-if="sliderItem.slide_btn_title_one || sliderItem.slide_btn_title_two">
                                                    <a :href="sliderItem.slide_btn_url_one" class="dc-btn dc-btnactive" v-if="sliderItem.slide_btn_title_one">
                                                        {{ sliderItem.slide_btn_title_one }}
                                                    </a>
                                                    <a :href="sliderItem.slide_btn_url_two" class="dc-btn" v-if="sliderItem.slide_btn_title_two">
                                                        {{ sliderItem.slide_btn_title_two }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </carousel>
                    </div>
                </div>
            </div>
        </div>
        <skeletonv1  v-if="skeletonApplied" items="1"/>
    </div>
</template>
<script>
import skeletonv1 from '../../skeleton/sliderv1'
import Event from '../../../../../event'
import carousel from 'vue-owl-carousel'
export default {
    props:['parent_index', 'element_id', 'slider_section', 'pageID', 'slides', 'filtersApplied', 'skeletonApplied'],
    components:{carousel, skeletonv1},
    data() {
        return {
            isActive:false,
            baseUrl: APP_URL,
            tempUrl:APP_URL+'/uploads/pages/temp/',
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
        self.skeletonApplied = false
    },
};
</script>
