<template>
    <div class="element-preview-wrapper" v-on:click="editElement">
        <section :class="app.sectionClass +' dc-haslayout'" :id="app.sectionId" :style="sectionStyle" v-if="Object.entries(app).length != 0">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6" v-if="app.image.url">
                        <div class="dc-appbgimg">
                            <figure class="dc-doccareimg">
                                <img 
                                    :src="tempUrl+app.image.url" 
                                    alt="img description" 
                                    v-bind:style="ImageStyle"
                                    v-if="newAppImage"
                                />
                                <img 
                                    :src="baseUrl+'/uploads/pages/'+pageID+'/'+app.image.url" 
                                    alt="img description" 
                                    v-bind:style="ImageStyle"
                                    v-else-if="pageID"
                                />
                            </figure>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 justify-content-center align-self-center">
                        <div class="dc-appcontent" :style="contentStyle">
                            <div class="dc-sectionhead dc-sectionheadvtwo">
                                <div class="dc-sectiontitle">
                                    <h2 :style="{color:app.titleColor}">{{ app.title }}<span :style="{color:app.subtitleColor}">{{ app.subtitle }}</span></h2>
                                </div>
                                <div class="dc-description" v-html="app.description"></div>
                            </div>
                            <ul class="dc-appicons">
                                <li v-if="app.googlePlay.image">
                                    <a :href="app.googlePlay.url">
                                        <img 
                                            :src="tempUrl+app.googlePlay.image" 
                                            alt="img description" 
                                            v-if="newGoogleImage"
                                        />
                                        <img 
                                            :src="baseUrl+'/uploads/pages/'+pageID+'/'+app.googlePlay.image" 
                                            alt="img description" 
                                            v-else-if="pageID"
                                        />
                                    </a>
                                </li>
                                <li v-if="app.appStore.image">
                                    <a :href="app.appStore.url">
                                        <img 
                                            :src="tempUrl+app.appStore.image" 
                                            alt="img description" 
                                            v-if="newAppStoreImage"
                                        />
                                        <img 
                                            :src="baseUrl+'/uploads/pages/'+pageID+'/'+app.appStore.image" 
                                            alt="img description" 
                                            v-else-if="pageID"
                                        />
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
import Event from '../../../event'
import carousel from 'vue-owl-carousel'
export default {
    props:['parent_index', 'element_id', 'appSection', 'pageID'],
    components: { carousel },
    data() {
        return {
            app:{},
            isActive:false,
            baseUrl: APP_URL,
            tempUrl:APP_URL+'/uploads/pages/temp/',
            newAppImage:false,
            newGoogleImage:false,
            newAppStoreImage:false,
        }
    },
    computed: {
        sectionStyle() {
            return {
                padding: `${this.app.padding.top}${this.app.padding.unit} ${this.app.padding.right}${this.app.padding.unit} ${this.app.padding.bottom}${this.app.padding.unit} ${this.app.padding.left}${this.app.padding.unit}`,
                margin: `${this.app.margin.top}${this.app.margin.unit} ${this.app.margin.right}${this.app.margin.unit} ${this.app.margin.bottom}${this.app.margin.unit} ${this.app.margin.left}${this.app.margin.unit}`,
                'text-align': this.app.alignment,
                background: this.app.background
            }
        },
        contentStyle() {
            return {
                padding: `${this.app.content.padding.top}${this.app.content.padding.unit} ${this.app.content.padding.right}${this.app.content.padding.unit} ${this.app.content.padding.bottom}${this.app.content.padding.unit} ${this.app.content.padding.left}${this.app.content.padding.unit}`,
                margin: `${this.app.content.margin.top}${this.app.content.margin.unit} ${this.app.content.margin.right}${this.app.content.margin.unit} ${this.app.content.margin.bottom}${this.app.content.margin.unit} ${this.app.content.margin.left}${this.app.content.margin.unit}`,
                'text-align': this.app.content.alignment,
                background: this.app.content.background ? this.app.content.backgroundColor : '',
                color: this.app.content.color
            }
        },
        ImageStyle() {
            return {
                width: this.app.image.width+this.app.image.widthUnit,
                height: this.app.image.height+this.app.image.heightUnit,
                opacity: this.app.image.opacity,
            }            
        },
    },
    updated: function() {
        var index = this.getArrayIndex(this.appSection, 'id', this.element_id)
        if (this.app[index]) {
            this.app = this.app[index]
        }
        this.app.id = this.element_id
    },
    mounted: function() {
        this.isActive = false
        var self= this
        Event.$on('app-section-update', (data) => {
            setTimeout(function(){ 
                self.isActive = !self.isActive;
            }, 10);
        })
        Event.$on('new-app-image'+self.element_id, (data) => {
            this.newAppImage = true
        })
        Event.$on('new-google-image'+self.element_id, (data) => {
            this.newGoogleImage = true
        })
        Event.$on('new-app-store-image'+self.element_id, (data) => {
            this.newAppStoreImage = true
        })
    },
    methods:{
        editElement: function() {
            var self = this
            this.$emit("editData");
        },
    },
    created: function() {
        var self = this
        setTimeout(function(){ 
            var index = self.getArrayIndex(self.appSection, 'id', self.element_id)
            if (self.appSection[index]) {
                self.app = self.appSection[index]
            }
        }, 100);
    },
};
</script>
