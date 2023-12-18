<template>
    <div class="element-preview-wrapper" v-on:click="editElement">
        <section 
            :class="about.sectionClass + ' dc-haslayout dc-main-section'" 
            :id="about.sectionId" 
            :style="sectionStyle" 
            v-if="Object.entries(about).length != 0"
        >
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-6 align-self-center">
                        <div class="dc-bringcarecontent">
                            <div class="dc-sectionhead dc-sectionheadvtwo">
                                <div class="dc-sectiontitle">
                                    <h2 :style="titleStyle">{{ about.title }}<span :style="subtitleStyle">{{ about.subtitle }}</span></h2>
                                </div>
                                <div class="dc-description" v-html="about.description"></div>
                            </div>
                            <div class="dc-btnarea">
                                <a :href="about.btnurl1" class="dc-btn" v-if="about.btntitle1">{{ about.btntitle1 }}</a>
                                <a :href="about.btnurl2" class="dc-btn dc-btnactive" v-if="about.btntitle2">{{ about.btntitle2 }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                        <div class="dc-bringimg-holder">
                            <figure class="dc-doccareimg" v-if="about.image.url">
                                <img 
                                    :src="tempUrl+about.image.url" 
                                    alt="img description" 
                                    v-bind:style="ImageStyle"
                                    v-if="newAboutImage"
                                />
                                <img 
                                    :src="baseUrl+'/uploads/pages/'+pageID+'/'+about.image.url" 
                                    alt="img description" 
                                    v-bind:style="ImageStyle"
                                    v-else-if="pageID">
                                <figcaption>
                                    <div class="dc-doccarecontent">
                                        <h3 :style="{color:about.image.titleColor}"><em :style="{color:about.image.subtitleColor}">{{ about.image.subtitle }}</em>{{ about.image.title }}</h3>
                                    </div>
                                    <span class="dc-content-afetr"></span>
                                </figcaption>
                            </figure>
                            <figure class="dc-doccareimg" v-else>
                                <img 
                                    :src="baseUrl+'/images/img-sec-placeholder.jpg'" 
                                    alt="img description"
                                />
                                <figcaption>
                                    <div class="dc-doccarecontent" :style="{background:about.image.captionBackground}">
                                        <h3 :style="{color:about.image.titleColor}"><em :style="{color:about.image.subtitleColor}">{{ about.image.subtitle }}</em>{{ about.image.title }}</h3>
                                    </div>
                                    <span class="dc-content-afetr" :style="{background:about.image.after}"></span>
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
            <div class="after-about-section">
                <figure v-if="about.afterSection">
                    <img 
                        :src="tempUrl+about.afterSection" 
                        alt="img description" 
                        v-if="newAfterImage"
                    />
                    <img 
                        :src="baseUrl+'/uploads/pages/'+pageID+'/'+about.afterSection" 
                        alt="img description" 
                        v-else-if="pageID"
                    />
                </figure>
            </div>
        </section>
    </div>
</template>
<script>
import Event from '../../../event'
import carousel from 'vue-owl-carousel'
export default {
    props:['parent_index', 'element_id', 'aboutSection', 'pageID'],
    components: { carousel },
    data() {
        return {
            about:{},
            isActive:false,
            baseUrl: APP_URL,
            tempUrl:APP_URL+'/uploads/pages/temp/',
            newAboutImage: false,
            newAfterImage: false,
        }
    },
    computed: {
        sectionStyle() {
            return {
                padding: `${this.about.padding.top}${this.about.padding.unit} ${this.about.padding.right}${this.about.padding.unit} ${this.about.padding.bottom}${this.about.padding.unit} ${this.about.padding.left}${this.about.padding.unit}`,
                margin: `${this.about.margin.top}${this.about.margin.unit} ${this.about.margin.right}${this.about.margin.unit} ${this.about.margin.bottom}${this.about.margin.unit} ${this.about.margin.left}${this.about.margin.unit}`,
                'text-align': this.about.alignment,
                background: this.about.background
            }
        },
        titleStyle() {
            return {
                color : this.about.titleColor,
            }
        },
        subtitleStyle() {
            return {
                color : this.about.subtitleColor,
            }
        },
        ImageStyle() {
            return {
                width: this.about.image.width+this.about.image.widthUnit,
                height: this.about.image.height+this.about.image.heightUnit,
                opacity: this.about.image.opacity,
            }            
        },
    },
    updated: function() {
        var index = this.getArrayIndex(this.aboutSection, 'id', this.element_id)
        if (this.about[index]) {
            this.about = this.about[index]
        }
        this.about.id = this.element_id
    },
    mounted: function() {
        this.isActive = false
        var self= this
        Event.$on('about-section-update', (data) => {
            setTimeout(function(){ 
                self.isActive = !self.isActive;
            }, 10);
        })
        Event.$on('new-about-image'+self.element_id, (data) => {
            this.newAboutImage = true
        })
        Event.$on('new-after-image'+self.element_id, (data) => {
            this.newAfterImage = true
        })
    },
    methods:{
        hoverEffect: function(elementId, color) {
            jQuery("#"+elementId).hover(function(){
                jQuery(this).css("background-color", color);
                }, function(){
                jQuery(this).css("background-color", "#fff");
            });
        },
        editElement: function() {
            var self = this
            this.$emit("editData");
        },
        calculateColor: function (value) {
            return value++
        }
    },
    created: function() {
        var self = this
        setTimeout(function(){ 
            var index = self.getArrayIndex(self.aboutSection, 'id', self.element_id)
            if (self.aboutSection[index]) {
                self.about = self.aboutSection[index]
            }
        }, 100);
    },
};
</script>
