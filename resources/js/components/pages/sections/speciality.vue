<template>
    <div class="element-preview-wrapper specialty-preview-wrapper" v-on:click="editElement" >
        <section 
            :class="speciality.sectionClass + ' dc-haslayout dc-main-section'" 
            :id="speciality.sectionId" 
            :style="sectionStyle" 
            v-if="Object.entries(speciality).length != 0"
        >
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-3">
                        <div class="row">
                            <div class="dc-ratedecontent dc-bgcolor" :style="contentSectionStyle">
                                <figure class="dc-neurosurgeons-img" v-if="speciality.detail.image">
                                    <img :src="speciality.detail.image" alt="img_desc">
                                </figure>
                                <figure class="dc-neurosurgeons-img" v-else>
                                    <img :src="temImg" alt="img_desc">
                                </figure>
                                <div class="dc-sectionhead dc-sectionheadvtwo dc-text-center">
                                    <div class="dc-sectiontitle">
                                        <h2 :style="{color:speciality.titleColor}">{{ speciality.title }}<span :style="{color:speciality.subtitleColor}">{{ speciality.detail.title }}</span></h2>
                                    </div>
                                    <div class="dc-description"><p>{{speciality.detail.description}}</p></div>
                                </div>
                                <div class="dc-btnarea" v-if="speciality.detail.url">
                                    <a :href="speciality.detail.url" class="dc-btn">{{ trans('lang.view_all') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-9">
                        <div class="row" >
                            <doctor-slider :doctors="speciality.detail.doctors" :speciality_title="speciality.detail.title" />
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
import doctorSlider from './doctor-slider'
export default {
    props:['parent_index', 'element_id', 'speciality_section', 'tabs'],
    components: { carousel, doctorSlider },
    data() {
        return {
            speciality:{},
            isActive:false,
            temImg:APP_URL+'/images/default-speciality.png',
        }
    },
    computed: {
        contentSectionStyle() {
            return {
                background: this.speciality.contentBackground,
                color: this.speciality.contentColor,
            }
        },
        sectionStyle() {
            return {
                padding: `${this.speciality.padding.top}${this.speciality.padding.unit} ${this.speciality.padding.right}${this.speciality.padding.unit} ${this.speciality.padding.bottom}${this.speciality.padding.unit} ${this.speciality.padding.left}${this.speciality.padding.unit}`,
                margin: `${this.speciality.margin.top}${this.speciality.margin.unit} ${this.speciality.margin.right}${this.speciality.margin.unit} ${this.speciality.margin.bottom}${this.speciality.margin.unit} ${this.speciality.margin.left}${this.speciality.margin.unit}`,
                'text-align': this.speciality.alignment,
            }
        },
    },
    updated: function() {
        var index = this.getArrayIndex(this.speciality_section, 'id', this.element_id)
        if (this.speciality_section[index]) {
            this.speciality = this.speciality_section[index]
        }
        this.speciality.id = this.element_id
    },
    mounted: function() {
        this.isActive = false
        var self= this
        Event.$on('speciality-section-update', (data) => {
            setTimeout(function(){ 
                self.isActive = !self.isActive;
            }, 10);
        })
    },
    methods:{
        add_wishlist: function (element_id, id, column) {
            var self = this;
            axios.post(APP_URL + '/user/add-wishlist', {
                id: id,
                column: column,
            })
                .then(function (response) {
                    if (response.data.authentication == true) {
                        if (response.data.type == 'success') {
                            jQuery('#' + element_id).addClass('wt-btndisbaled');
                            jQuery('#' + element_id).addClass('wt-clicksave');
                            jQuery('#' + element_id).addClass('dc-clicksave dc-btndisbaled');
                            self.showMessage(response.data.message);
                        } else {
                            self.showError(response.data.message);
                        }
                    } else {
                        self.showError(response.data.message);
                    }
                })
                .catch(function (error) {
                });
        },
        editElement: function() {
            var self = this
            this.$emit("editData");
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
    },
    created: function() {
        var self = this
        setTimeout(function(){ 
            var index = self.getArrayIndex(self.speciality_section, 'id', self.element_id)
            if (self.speciality_section[index]) {
                self.speciality = self.speciality_section[index]
            }
            self.speciality.id = self.element_id

            var segment_str = window.location.pathname;
            var segment_array = segment_str.split('/');
            var edit_page = segment_array[segment_array.length - 2];
            if (edit_page == 'edit-page') {
                if (self.speciality.specialityID) {
                    self.getServices(self.speciality.specialityID)
                }
            }
        }, 100);

    },
};
</script>
