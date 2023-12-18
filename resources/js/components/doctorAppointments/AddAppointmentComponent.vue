<template>
    <div>
        <div class="pageloader-outer" v-if="loading" v-cloak>
            <div class="dc-preloader-holder">
                <div class="dc-loader"></div>
            </div>
        </div>
        <!-- Booking Modal -->
        <b-modal ref="doc-booking-modal" id="dc-doc-booking-modal" size="lg" hide-footer :title="trans('lang.book_appointment')" no-close-on-backdrop>
            <div class="dc-modalcontent modal-content dc-booking-modal-content">	
            <!-- <div class="dc-formtheme dc-prescription-msg-area"> -->
                <div id="dcModalBody" class="modal-body dc-modal-content-one dc-haslayout">
                <div id="dcModalBody1" class="dc-visitingdoctor">
                    <form class="dc-booking-doctor dc-formfeedback" id='submit_appointment_form'>
                        <div class="dc-title">
                            <span>Add patient details</span>
                        </div>
                        <div class="dc-formtheme dc-modalvistingdocinfo">
                            <p>Please add correct email address to find patient from the database, if not found then you can add new patient into database by typing email address and name</p>
                            <fieldset>
                            <div class="form-group form-group-half" id="single-datasets-h">
                                <input class="typeahead" type="text" placeholder="Email" name='email'>
                            </div>
                            <!-- <div class="form-group form-group-half">
                                <input type="text" name="email" id="dc-booking-email" class="form-control" placeholder="Email" @change="fetchUser()">
                            </div> -->
                            <div class="form-group form-group-half">
                                <input type="text" name="first_name" class="form-control" placeholder="First Name" v-model="first_name">
                            </div>
                            <div class="form-group form-group-half">
                                <input type="text" name="last_name" class="form-control" placeholder="Last Name" v-model="last_name">
                            </div>
                            <div class="form-group">
                                <input type="text" name="phone" id="dc-booking-phone" class="form-control" placeholder="Phone" v-model="phone_num">
                            </div>
                            <!-- <div class="form-group dc-add-new-patient" v-if="if_new_user">
                                <span class="dc-checkbox dc-creat-user">
                                <input type="checkbox" name="create_user" class="dc-user" id="dc-user" value="yes" checked="">
                                <label for="dc-user">Create new user</label>
                                </span>
                            </div> -->
                            </fieldset>
                        </div>
                        <div class="dc-title dc-visitingtitle">
                            <span>{{ trans('lang.who_visiting_doc') }}</span>
                            <div class="dc-tabbtns">
                            <span class="dc-radio next-step">
                                <input type="radio" name="visiting_user" class="myself" value="myself" id="myself" checked="" v-model="visiting_user">
                                <label for="myself">{{ trans('lang.patient_only') }}</label>
                            </span>
                            <span class="dc-radio next-step">
                                <input type="radio" name="visiting_user" class="myself" value="someelse" id="someelse" v-model="visiting_user">
                                <label for="someelse">{{ trans('lang.someone_else') }}</label>
                            </span>
                            </div>
                        </div>
                        
                        <div class="dc-formtheme dc-docinfoform form-group-relation" v-if="visiting_user == 'someelse'">
                            <fieldset>
                            <div class="form-group form-group-half">
                                <input type="text" name="other_patient_name" class="form-control" placeholder="Name" id="other-patient-name">
                            </div>
                            <div class="form-group form-group-half">
                                <span class="dc-select">
                                    <select data-placeholder="Relation with you? *" name="relation" id="relation-name" v-model="relation">
                                        <option value="">{{ trans('lang.relation_with_you') }}</option>
                                        <option value="brother">{{ trans('lang.brother') }}</option>
                                        <option value="wife">{{ trans('lang.wife') }}</option>
                                        <option value="mother">{{ trans('lang.mother') }}</option>
                                        <option value="sister">{{ trans('lang.sister') }}</option>
                                        <option value="other">{{ trans('lang.other') }}</option>
                                    </select>
                                </span>
                            </div>
                                <div class="form-group">
                                <input type="text" id="other_relation" name="other_relation" class="form-control" :placeholder="trans('lang.relation')" v-if="relation == 'other'">
                            </div>
                            </fieldset>
                        </div>
                        <div class="dc-formtheme dc-vistingdocinfo">
                            <fieldset>
                                <div class="form-group">
                                    <span class="dc-select">
                                        <select v-if="doctor_hospitals.length > 0" name="hospital" id="appointment_hospital" v-on:change="getHospitalServices(doc_id)" v-model="selected_hospital" placeholder="select hospital">
                                            <option value="">{{ trans('lang.select_hospital') }}</option>
                                            <option v-for="(hospital, index) in doctor_hospitals" :key="index" :value="hospital.id">{{hospital.name}} {{hospital.type}}</option>
                                        </select>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <transition name="fade">
                                        <div id="dc-appointment-accordion" class="dc-appointment-accordion dc-moreservice" role="tablist" aria-multiselectable="true" v-if="showServices">
                                            <div class="dc-panel" v-for="(speciality, index) in hospital_services.specialities" :key="index">
                                            <div v-bind:class="[index == 0 ? 'active' : '', 'dc-paneltitle']" :id="'service-tab-'+index" v-on:click="toggleCollapse('service-tab-'+index)">
                                                <figure class="dc-titleicon">
                                                    <img :src="speciality.speciality_image" :alt="trans('lang.img_desc')">
                                                </figure>
                                                <span>{{speciality.speciality_title}}</span>
                                                <input type="hidden" :name="'speciality['+index+'][speciality]'" :value="speciality.speciality_id">
                                            </div>
                                            <div class="dc-appointmentpanelcontent">
                                                <div class="dc-subtitle">
                                                    <h4>{{ trans('lang.services') }}:</h4>
                                                </div>
                                                <div class="dc-checkbox-holder">
                                                    <span class="dc-checkbox" v-for="(service, service_index) in speciality.services" :key="service_index">
                                                        <input 
                                                            :id="'dc-mo-'+index+'-'+service_index" type="checkbox" 
                                                            :name="'speciality['+index+']'+'[service]['+service_index+']'" 
                                                            :value="service.service_id" 
                                                            v-on:change="displayServicePrice(service.service_price, service.service_title, 'dc-mo-'+index+'-'+service_index)"
                                                        >
                                                        <label :for="'dc-mo-'+index+'-'+service_index">{{service.service_title}}</label>
                                                    </span>
                                                </div>
                                            </div>
                                            </div>
                                            <div class="dc-hospital_charges la-hospital-charges" v-if="showServices">
                                                <ul class="dc-taxesfees">
                                                <li class="hospital-price">{{ trans('lang.hospital_charges') }} <em>{{ hospital_services.price }}</em></li>
                                                <li class="service-price" v-for="(service, charges_index) in charges" :key="charges_index">{{service.title}}: <em>{{currency}}{{service.price}}</em></li>
                                                <li class="total-price">{{ trans('lang.total_charges') }} <em>{{this.currency}}{{this.total_charges}}</em></li>
                                                </ul>
                                                <input type="hidden" :value="total_charges" name="total_charges">
                                            </div>
                                        </div>
                                    </transition>
                                </div>
                                <div class="form-group" id="booking_service_select"></div>
                                <div class="form-group" id="booking_fee"></div>
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Comments:" name="comments"></textarea>
                                </div>
                            </fieldset>
                        </div>
                        
                        <div class="dc-appointment-holder">
                            <div class="dc-title">
                            <h4>Select best time for appointment with time zone</h4>
                            <em>*These time slots are based on the timezone</em>
                            </div>
                            <div class="dc-appointment-content">
                            <div class="dc-appointment-calendar">
                                <div :style="{ width: '300px', height: '362px', border: '1px solid #d9d9d9', borderRadius: '4px' }">
                                <a-calendar :fullscreen="false" @select="onSelect"  @panelChange="onPanelChange"  v-model="selected_day" />
                                </div>
                                <input type="hidden" :value="day" name="appointment[day]">
                                <input type="hidden" :value="appointment_date" name="appointment[date]">
                            </div>
                            <div class="dc-timeslots dc-update-timeslots">			
                                <span class="dc-radio next-step" v-for="(slot, index) in slots" :key="index" v-if="show_slots">
                                    <input type="radio" :id="'availableslot-'+index" name="appointment[time]" :value="slot.start_time" v-if="slot.space > 0">
                                    <input type="radio" :id="'availableslot-'+index" name="appointment[time]" :value="slot.start_time" disabled v-else>
                                    <label :for="'availableslot-'+index"><span> {{slot.start_time}}</span><em>{{ trans('lang.spaces') }}: {{slot.space}}</em></label>
                                </span>
                                <div class="dc-emptydata-holder" v-else>
                                    <div class="dc-emptydata">
                                        <div class="dc-emptydetails dc-empty-articls dc-emptyholder-sm">
                                        <span></span>
                                            <em>There are no any slot available.</em>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <input type="hidden" value="2021-01-06" name="appointment_date" id="appointment_date"> -->
                            </div>
                        </div>
                    </form>
                </div>
                </div>
            <!-- </div> -->
            <div class="modal-footer dc-modal-footer">
                <input type="submit" :value="trans('lang.continue')" class="btn dc-btn btn-primary" @click='addNewAppoinment()'>
            </div>
            </div>
        </b-modal>
    </div>
</template>
<script>
import moment from 'moment'

export default {
    data () {
        return {
            loading: false,
            relation:'',
            first_name:'',
            last_name:'',
            phone_num:'',
            if_new_user:true,
            patient_id:'',
            patient_email:'',
            query:'',
            selected_day: moment(),
            appointment_date:'',
            day:'',
            slots:[],
            selected_hospital:'',
            doctor_hospitals:[],
            doc_id:'',
            showServices : false,
            show_slots:false,
            currency:'$',
            total_charges:'',
            charges:[],
            visiting_user: 'myself',
            selected_services:{
                title:'',
                price:'',
            },
        }
    },
    mounted () {
        setTimeout(() => {
            var emails = new Bloodhound({
                
                remote: {
                    url: APP_URL +'/search/appointment-users?query=%QUERY%',
                    wildcard: '%QUERY%'
                },
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
            
            });

            var self = this
            $('#single-datasets-h' + ' .typeahead').typeahead({
                highlight: true,
                minLength: 1
                },
                {
                    name: 'emails',
                    display: 'email',
                    source: emails,
                    templates: {
                        suggestion: function (data) {
                            return '<a id="search-list-item-id" class="search-list-group-item" data-id='+data.id+'>' + data.email + '</a>'
                        }
                    }
                }
            );

            $('#single-datasets-h').bind('typeahead:selected', function(obj, datum) {      
                if (datum.id) {
                    self.patient_id = datum.id
                    self.patient_email = datum.email
                    // self.if_new_user =  false
                    self.fetcUserDetail(datum.id)
                }
                  
            });
        }, 500);
    },
    created () {
        this.getRequiredAppointmentData();
        setTimeout(() => {
            this.showModal('doc-booking-modal')
        }, 200);
    },
    methods:{    
        showModal: function (reference) {
            this.$refs[reference].show();
        },
        fetcUserDetail (user_id) {
            var self = this;
            axios.post(APP_URL + '/doctor/fetch-user-detail', {user_id:user_id})
            .then(function (response) {
                if (response.data.type === 'success') {
                    self.first_name = response.data.first_name
                    self.last_name = response.data.last_name
                    self.phone_num = response.data.phone_num
                } else {
                
                }
            })
            .catch(function (error) {

            })
        },
        displayServicePrice: function (price, title, element_id) {
            if (document.getElementById(element_id).checked == true) {
            this.total_charges = parseInt(this.total_charges) + parseInt(price);
            this.charges.push(Vue.util.extend (
                {}, this.selected_services, this.selected_services.title = title, this.selected_services.price = price)
            )
            } else {
                this.total_charges = parseInt(this.total_charges) - parseInt(price);
                this.charges = this.charges.filter(function( obj ) {
                    return obj.title !== title;
                });
            }
        },
        toggleCollapse: function(element_id) {
            jQuery('.active.dc-paneltitle').siblings().hide('slow');
            jQuery('.active.dc-paneltitle').removeClass('active');
            jQuery('#'+element_id).addClass('active');
            jQuery('#'+element_id).siblings().show('slow');   
        },
        onSelect(value) {
            var day = this.getDay(value);
            this.displaySlots(day);
        },
        onPanelChange(value, mode) {
            var day = this.getDay(value);
            this.displaySlots(day);
        },
        getDay: function(value) {
            var weekday = new Array(7);
            weekday[0] = "sun";
            weekday[1] = "mon";
            weekday[2] = "tue";
            weekday[3] = "wed";
            weekday[4] = "thu";
            weekday[5] = "fri";
            weekday[6] = "sat";
            var n = weekday[value.day()];
            return n;
        },
        displaySlots (day) {
            this.appointment_date = this.selected_day.year() +'-'+this.selected_day.format('M') + '-' + this.selected_day.date();
            this.day = day;
            var self = this;
            axios.post(APP_URL + '/doctor/get-appointment-slots', {doctor_id:self.doc_id, hospital_id:self.selected_hospital, day:day, date:this.appointment_date})
            .then(function (response) {
                if (response.data.type === 'success') {
                    self.slots = response.data.slots;
                } else {
                
                }
            })
            .catch(function (error) {

            })
        
        },
        getRequiredAppointmentData () {
            var self = this;
            axios.post(APP_URL + '/doctor/get-required-appointment-data')
            .then(function (response) {
                if (response.data.type === 'success') {
                    self.doctor_hospitals = response.data.doctor_hospitals
                    self.doc_id = response.data.doc_id
                    self.currency = response.data.symbol
                } else {
                    console.log('no data found')
                }
            })
            .catch(function (error) {
                console.log(error)
            })
        },
        getHospitalServices: function (doctor_id) {
            if (doctor_id) {
                this.show_slots = false
                var self = this;
                self.showServices = false;
                self.charges = [];
                setTimeout(() => {
                    self.show_slots = true
                }, 50);
                axios.post(APP_URL + '/doctor/get-hospital-services', {hospital:self.selected_hospital, doctor:doctor_id})
                .then(function (response) {
                    if (response.data.type == 'success') {
                        self.hospital_services = response.data.services;
                    if (self.hospital_services.price) {
                        self.total_charges = self.hospital_services.price;
                    } else {
                        self.total_charges = 0;
                    }
                    Event.$emit('display-slots', { doctor: doctor_id, hospital:self.selected_hospital });
                    self.showServices = true;
                    } else if (response.data.type == 'error') {
                        self.showServices = false;
                    }
                })
                .catch(error => {

                });
            } else {
                this.showServices = false;
            }
        },
        addNewAppoinment: function (doctorID) {
            if (this.visiting_user == 'someelse') {
                var patient_name = document.getElementById('other-patient-name').value;
                var form_errors = [];
                if (!patient_name) form_errors.push(Vue.prototype.trans('lang.patient_name_req'));
                var relation = document.getElementById('relation-name').value ? document.getElementById('relation-name').value : ''
                if (!relation || relation == 'null') form_errors.push(Vue.prototype.trans('lang.select_relation_req'));
                if (relation == 'other') {
                    if (!document.getElementById('other_relation').value) form_errors.push(Vue.prototype.trans('lang.relation_req'));
                }
                form_errors.forEach(element => {
                    this.showError(element)
                });
                if (form_errors.length > 0) {
                    return false;
                }
                form_errors = [];
            }
            this.loading = true;
            if (document.getElementById('appointment_hospital').selectedIndex > 0) {
                var timeSlot = document.getElementsByName('appointment[time]');
                var isTimeSlot = false;
                for (var i = 0; i < timeSlot.length; i++) {
                    if (timeSlot[i].checked) {
                        isTimeSlot = true;
                        break;
                    }
                }
                if (isTimeSlot == true) {
                    let submitAppointmentForm = document.getElementById('submit_appointment_form')
                    let formData = new FormData(submitAppointmentForm)
                    // formData.append('patient_id', this.patient_id)
                    var self = this;
                    axios.post(APP_URL + '/submit-appointment-by-doc', formData)
                        .then(function (response) {
                            if (response.data.type == 'success') {
                                self.loading = false;
                                self.showMessage('Appointment created successfully')
                                window.location.reload()
                            }
                        })
                        .catch(function (error) {
                            self.loading = false;
                            if (error.response.data.errors.first_name) {
                                self.showError(error.response.data.errors.first_name[0]);
                            }
                            if (error.response.data.errors.last_name) {
                                self.showError(error.response.data.errors.last_name[0]);
                            }
                            if (error.response.data.errors.email) {
                                self.showError(error.response.data.errors.email[0]);
                            }
                        })
                } else {
                    this.showError(Vue.prototype.trans('lang.select_appointment_time'))
                    return false;
                }
            } else {
                if (document.getElementById('appointment_hospital').selectedIndex <= 0) {
                    this.showError(Vue.prototype.trans('lang.hospital_req'))
                    return false;
                }
            }
        },
    }
}
</script>