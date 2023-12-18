<template>
    <section class="dc-searchholder dc-haslayout" :class="service.sectionClass" v-if="Object.entries(service).length != 0">
        <div class="dc-haslayout">
            <div class="container-fluid">
                <div class="row">
                    <div id="dc-doctorslider" class="dc-doctorslider owl-carousel">
                        <div v-for="(tab, index) in tabs" :key="index" :class="'item dc-doctordetails-holder dc-titlecolor' + calculateColor(index)">
                            <span class="dc-slidercounter">0{{calculateColor(index) + 1}}</span>
                            <h3 v-bind:style="{color:tab.color}"><span>{{tab.title}}</span> {{ tab.subtitle }}</h3>
                            <a :href="tab.btn_url" :id="'link'+index" @mouseover="hoverEffect('link'+index, tab.color)" class="dc-btn" v-bind:style="{'border-color':tab.color}">{{ tab.btn_title }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
import carousel from 'vue-owl-carousel'
export default {
    props:['parent_index', 'element_id', 'service_section', 'tabs'],
    components: { carousel },
    data() {
        return {
            service:{},
            isActive:false
        }
    },
    computed: {
        sectionStyle() {
            return {
                padding: `${this.service.padding.top}${this.service.padding.unit} ${this.service.padding.right}${this.service.padding.unit} ${this.service.padding.bottom}${this.service.padding.unit} ${this.service.padding.left}${this.service.padding.unit}`,
                margin: `${this.service.margin.top}${this.service.margin.unit} ${this.service.margin.right}${this.service.margin.unit} ${this.service.margin.bottom}${this.service.margin.unit} ${this.service.margin.left}${this.service.margin.unit}`,
                'text-align': this.service.alignment,
            }
        },
    },
    updated: function() {
        var index = this.getArrayIndex(this.service_section, 'id', this.element_id)
        if (this.service_section[index]) {
            this.service = this.service_section[index]
        }
        this.service.id = this.element_id
    },
    mounted: function() {
        this.isActive = false
        var self= this
        Event.$on('service-section-update', (data) => {
            setTimeout(function(){ 
                self.isActive = !self.isActive;
            }, 10);
        })
        // Slider code
        setTimeout(() => {
            var _dc_doctorslider = jQuery("#dc-doctorslider")
            _dc_doctorslider.owlCarousel({
                loop:false,
                margin:0,
                navSpeed:500,
                rtl:this.reverse_slider,
                nav:false,
                autoplay: false,
                items:5,
                responsiveClass:true,
                responsive:{
                    0:{
                        items:1,
                    },
                    600:{
                        items:2,
                    },
                    800:{
                        items:3,
                    },
                    1080:{
                        items:4,
                    },
                    1280:{
                        items:5,
                    },
                }	
            });
        }, 100);
    },
    methods:{
        hoverEffect: function(elementId, color) {
            // jQuery("#"+elementId).hover(function(){
            //     jQuery(this).css("background-color", color);
            //     }, function(){
            //     jQuery(this).css("background-color", "#fff");
            // });
        },
        calculateColor: function (value) {
            return value++
        }
    },
    created: function() {
        var self = this
        setTimeout(function(){ 
            var index = self.getArrayIndex(self.service_section, 'id', self.element_id)
            if (self.service_section[index]) {
                self.service = self.service_section[index]
            }
            self.service.id = self.element_id
        }, 100);
    },
};
</script>
