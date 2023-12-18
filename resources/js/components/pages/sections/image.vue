<template>
    <div class="element-preview-wrapper" v-on:click="editElement">
        <section class="image-section-wrapper" :class="image.sectionClass" :id="image.sectionId" v-bind:style="Style" v-if="Object.entries(image).length != 0">
            <div class="container">
                <figure class="at-img-section" v-bind:style="figureStyle" v-if="image.url">
                    <img 
                        :src="baseUrl+'/uploads/pages/'+pageID+'/'+image.url" 
                        alt="img description" 
                        v-bind:class="[isActive ? 'activeClass' : '', image.elementClass]" 
                        :id="image.elementId"
                        v-bind:style="ImageStyle"
                        v-if="pageID">
                    <img 
                        :src="tempUrl+image.url" 
                        alt="img description" 
                        v-bind:class="[isActive ? 'activeClass' : '', image.elementClass]" 
                        :id="image.elementId"
                        v-bind:style="ImageStyle"
                        v-else>
                </figure>
                <figure class="at-img-section" v-else>
                    <img 
                        :src="baseUrl+'/images/img-sec-placeholder.jpg'" 
                        alt="img description" 
                        v-bind:class="[isActive ? 'activeClass' : '', image.elementClass]" 
                        :id="image.elementId">
                </figure>
            </div>
        </section>
    </div>
</template>
<script>
import Event from '../../../event'
export default {
    props:['parent_index', 'element_id', 'image_section', 'pageID'],
    data() {
        return {
            image:{},
            isActive:false,
            baseUrl: APP_URL,
            tempUrl:APP_URL+'/uploads/pages/temp/',
        }
    },
    computed: {
        Style() {
            return {
                padding: `${this.image.padding.top}${this.image.padding.unit} ${this.image.padding.right}${this.image.padding.unit} ${this.image.padding.bottom}${this.image.padding.unit} ${this.image.padding.left}${this.image.padding.unit}`,
                margin: `${this.image.margin.top}${this.image.margin.unit} ${this.image.margin.right}${this.image.margin.unit} ${this.image.margin.bottom}${this.image.margin.unit} ${this.image.margin.left}${this.image.margin.unit}`,
                background : this.image.background,
            }            
        },
        ImageStyle() {
            return {
                width: this.image.width+this.image.widthUnit,
                height: this.image.height+this.image.heightUnit,
                opacity: this.image.opacity,
            }            
        },
        figureStyle() {
            return {
                 'justify-content': this.image.alignment,
            }            
        },
    },
    updated: function() {
        var index = this.getArrayIndex(this.image_section, 'id', this.element_id)
        if (this.image_section[index]) {
            this.image = this.image_section[index]
        }
        this.image.id = this.element_id
    },
    mounted: function() {
        this.isActive = false
        var self= this
        Event.$on('image-section-update', (data) => {
            setTimeout(function(){ 
                self.isActive = !self.isActive;
            }, 10);
        })
    },
    methods:{
        editElement: function() {
            this.$emit("editData");
        }
    },
    created: function() {
        var self = this
        setTimeout(function(){ 
            var index = self.getArrayIndex(self.image_section, 'id', self.element_id)
            if (self.image_section[index]) {
                self.image = self.image_section[index]
            }
            self.image.id = self.element_id
        }, 50);
    },
};
</script>
