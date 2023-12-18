<template>
    <div id="dc-docpostslider" class="dc-docpostslider owl-carousel">
        <div class="item" v-for="(doctor, index) in doctors" :key="index">
            <div class="dc-docpostholder">
                <figure class="dc-docpostimg">
                    <img :src="doctor.image" alt="img_desc">
                </figure>
                <div class="dc-docpostcontent">
                    <a href="javascrip:void(0);" class="dc-like dc-clicksave dc-btndisbaled" v-if="doctor.saved != 'false'">
                        <i class="fa fa-heart"></i>
                    </a>
                    <a 
                        href="javascrip:void(0);" 
                        class="dc-like" 
                        :id="'doctor-'+doctor.id" 
                        @click.prevent="add_wishlist('doctor-'+doctor.id, doctor.id, 'saved_doctors', '')" 
                        v-else>
                        <i class="fa fa-heart"></i>
                    </a>
                    <div class="dc-title">
                        <a href="javascript:void(0)" class="dc-docstatus">
                            {{ speciality_title }}
                        </a>
                        <h3>
                            <a :href="doctor.profile_url">
                                {{ doctor.gender_title }} 
                                {{ doctor.name }}
                            </a> 
                            <i class="icon-sheild dc-tipso" :data-tipso="doctor.medical_text" v-if="doctor.verifyMedical == 1"></i>
                            <i class="far fa-check-circle dc-tipso" :data-tipso="doctor.verify_user_text" v-if="doctor.verifyUser == 1"></i>
                        </h3>
                        <ul class="dc-docinfo">
                            <li>{{ doctor.tagline }}</li>
                            <li>
                                <span class="dc-stars"><span :style="{width:doctor.stars}"></span></span><em>{{doctor.total_feedback }} {{ trans('lang.feedbacks') }}</em>
                            </li>
                        </ul>
                    </div>
                    <div class="dc-doclocation">
                        <span v-if="doctor.location"><i class="ti-direction-alt"></i> {{doctor.location}}</span>
                        <span>
                            <i class="ti-calendar"></i>
                            <span v-for="(day, dayIndex) in doctor.days" :key="'day'+dayIndex">
                                <em class="dc-dayon" v-if="day.dayon == 'true'">{{ day.title }}</em>
                                <span v-else>{{ day.title }}</span>
                            </span>
                        </span>
                        <a :href="doctor.profile_url" class="dc-btn">{{ trans('lang.view_more') }}</a>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</template>
<script>
import carousel from 'vue-owl-carousel'
export default {
    props:['doctors', 'speciality_title'],
    components: { carousel },
    data() {
        return {
            filtersApplied: true
        }
    },
    watch: {
        doctors: function () {
            var self = this
            self.filtersApplied = false
            setTimeout(function () {
                self.filtersApplied = true
            }, 10)
        }
    },
    mounted () {
        var _dc_docpostslider = jQuery("#dc-docpostslider")
        _dc_docpostslider.owlCarousel({
            loop:false,
            margin:30,
            navSpeed:1000,
            nav:false,
            rtl:this.reverse_slider,
            items:5,
            autoplayHoverPause:true,
            autoplaySpeed:1000,
            autoplay: false,
            mouseDrag:false,
            navClass: ['dc-prev', 'dc-next'],
            navContainerClass: 'dc-docslidernav',
            navText: ['<span class="ti-arrow-left"></span>', '<span class="ti-arrow-right"></span>'],
            responsiveClass:true,
            responsive:{
                0:{
                    items:1,
                },
                480:{
                    items:2,
                },
                800:{
                    items:3,
                },
                992:{
                    items:2,
                },
                1200:{
                    items:3,
                },
                1366:{
                    items:4,
                },
                1681:{
                    items:5,
                }
            }	
        });
    },
    methods: {
        add_wishlist: function (element_id, id, column) {
            var self = this;
            axios.post(APP_URL + '/user/add-wishlist', {
                id: id,
                column: column,
            })
                .then(function (response) {
                    if (response.data.authentication == true) {
                        if (response.data.type == 'success') {
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
                    console.log(error);
                });
        },
    }
};
</script>
