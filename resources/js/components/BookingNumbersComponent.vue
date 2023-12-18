<template>
    <div>
        <div class="dc-formtheme dc-skillsform">
            <transition name="fade">
                <div v-if="isShow" class="sj-jump-messeges">{{ trans('lang.no_record') }}</div>
            </transition>
            <fieldset>
                <div class="form-group">
                    <div class="form-group-holder">
                        <input type="text" id="booking-num" name="bookin_numbers" :placeholder="trans('lang.phone_numbers')" class="form-control">
                    </div>
                </div>
                <div class="form-group dc-btnarea">
                    <a href="javascript:void(0);" class="dc-btn" @click="addBookingNumbers()">{{trans('lang.add')}}</a>
                </div>
            </fieldset>
        </div>
        <div class="dc-myskills">
            <ul id="award_list" class="sortable list">
                <li v-for="(num, index) in stored_numbers" :key="'stored_'+index" v-if="stored_numbers" :id="'award-'+index" class="award-element" :ref="'award-'+index">
                    <div class="dc-dragdroptool">
                        <a href="javascript:void(0)" class="lnr lnr-menu"></a>
                    </div>
                    <span class="skill-dynamic-html">{{ num }}</span>
                    <span class="skill-dynamic-field">
                        <input type="text" v-bind:name="'booking_nums[]'" v-model="stored_numbers[index]">
                    </span>
                    <div class="dc-rightarea">
                        <a href="javascript:void(0);" class="dc-addinfo" v-on:click="editInput(index)"><i class="lnr lnr-pencil"></i></a>
                        <a href="javascript:void(0);" class="dc-deleteinfo" @click="deActiveStoredAwards(index)" v-if="stored_edit_class"><i class="lnr lnr-trash"></i></a>
                        <a href="javascript:void(0);" class="dc-deleteinfo delete-award" @click="removeStoredAwards('award-'+index)" v-else><i class="lnr lnr-trash"></i></a>
                    </div>
                </li>
                <li v-for="(number, index) in booking_nums" :key="index+number.count" v-bind:class="{ 'dc-skillsaddinfo': number.edit_class }">
                    <div class="dc-dragdroptool">
                        <a href="javascript:void(0)" class="lnr lnr-menu"></a>
                    </div>
                    <span class="skill-dynamic-html">{{ number.title }}</span>
                    <span class="skill-dynamic-field">
                        <input type="text" v-bind:name="'booking_nums[]'" v-model="number.title">
                    </span>
                    <div class="dc-rightarea">
                        <a href="javascript:void(0);" class="dc-addinfo" v-on:click="number.edit_class = !number.edit_class"><i class="lnr lnr-pencil"></i></a>
                        <a href="javascript:void(0);" class="dc-deleteinfo" v-on:click="number.edit_class = !number.edit_class" v-if="number.edit_class"><i class="lnr lnr-trash"></i></a>
                        <a href="javascript:void(0);" class="dc-deleteinfo" @click="removeAward(index)" v-else><i class="lnr lnr-trash"></i></a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
<script>
 export default{
    data() {
        return {
            dc_awardsactive:false,
            isShow: false,
            stored_numbers:[],
            award_title: '',
            edit_class: [],
            stored_edit_class:false,
            edit_award: '',
            booking: {
                id: '',
                title:'',
                count: 0,
                edit_class: false,
            },
            booking_nums: [],
            counts:0,
            notificationSystem: {
                error: {
                    position: "topRight",
                    timeout: 4000
                }
            },
        }
    },
    methods: {
        showError(error){
            return this.$toast.error(' ', error, this.notificationSystem.error);
        },
        getawards () {
            let self = this;
            axios.get(APP_URL + '/doctor/get-booking-numbers')
            .then(function (response) {
                self.stored_numbers = response.data.booking_numbers;
            });
        },
        addBookingNumbers: function () {
            var bookingNum = document.getElementById("booking-num");
            if (bookingNum.value === "") {
                this.showError('empty field not allow');
            } else {
                var award_list_count = jQuery('.dc-btn').parents('.dc-skillsform').next('.dc-myskills').find('ul#award_list li').length;
                award_list_count = award_list_count - 1;
                this.booking.count = award_list_count;
                this.award_title = document.getElementById("booking-num").value;
                this.booking.count++;
                this.booking_nums.push(Vue.util.extend({}, this.booking, this.booking.count++, this.booking.title = this.award_title, this.booking.id = this.award_title ))
                bookingNum.value = ''
            }
        },
        removeAward: function (index) {
            var self = this;
            self.booking_nums.splice(index, 1);
        },
        removeStoredAwards: function (id) {
            jQuery('#' + id).remove();
        },
        editInput: function (index) {
            this.stored_edit_class = true;
            if (this.$refs['award-'+index][0].classList.contains('dc-skillsaddinfo')) {
                this.$refs['award-'+index][0].classList.remove('dc-skillsaddinfo');
            } else {
                this.$refs['award-'+index][0].classList.add('dc-skillsaddinfo');
            }
        },
        deActiveStoredAwards: function (index) {
            this.stored_edit_class = false;
            if (this.$refs['award-'+index][0].classList.contains('dc-skillsaddinfo')) {
                this.$refs['award-'+index][0].classList.remove('dc-skillsaddinfo');
            }
        }
    },
    mounted: function () {
        
    },
    created: function() {
        this.getawards();
    }
    }
</script>
