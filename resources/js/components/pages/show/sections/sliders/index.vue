<template>
    <div>
    <div 
        class="element-preview-wrapper" 
        v-on:click="editElement" 
        v-if="slides.slides"
    >
        <sliderv1
            :element_id="element_id"
            :parent_index="parent_index"
            :slider_section="slider"
            :slides="slides"
            :filtersApplied="filtersApplied"
            v-if="style =='style1'"
        />
        <sliderv2
            :element_id="element_id"
            :parent_index="parent_index"
            :slider_section="slider"
            :slides="slides"
            :filtersApplied="filtersApplied"
            :roles="roles"
            :selected_search_form_type="selected_search_form_type"
            v-if="style =='style2'"
        />
    </div>
    <!-- <skeleton  v-if="skeletonApplied" items="1"/> -->
    </div>
</template>
<script> 
import sliderv1 from './sliderV1'
import sliderv2 from './sliderV2'
// import skeleton from '../../skeleton/'
export default {
    props:['element_id', 'parent_index', 'slider_section', 'pageID', 'roles', 'selected_search_form_type'],
    components:{sliderv1, sliderv2},
    data() {
        return {
            style:'',
            slider:{},
            slides:[],
            isActive:false,
            filtersApplied: false,
            skeletonApplied: false,
        }
    },
    updated: function() {
        var index = this.getArrayIndex(this.slider_section, 'id', this.element_id)
        if (this.slider_section[index]) {
            this.slider = this.slider_section[index]
        }
        this.slider.id = this.element_id
    },
    methods:{
        editElement: function() {
            this.$emit("editData");
        }
    },
    created: function() {
        var self = this
        self.skeletonApplied =  true
        setTimeout(function(){ 
            var index = self.getArrayIndex(self.slider_section, 'id', self.element_id)
            if (self.slider_section[index]) {
                self.slider = self.slider_section[index]
            }
            if (self.slider.slider_id) {
                axios.get(APP_URL + '/section/get-sliders/'+self.slider.slider_id)
                .then(function (response) {
                    self.slides = response.data.slides
                    self.filtersApplied = true
                    self.style = self.slides.style
                    self.skeletonApplied =  false
                })
            }
            self.slider.id = self.element_id
        }, 100);
    },
};
</script>
