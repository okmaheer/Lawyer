<template>
    <div class="dc-haslayout dc-appointment-detail-wraper" v-if="is_show">
        <div class="dc-preloader-section" v-if="is_loading" v-cloak>
            <div class="dc-preloader-holder">
                <div class="dc-loader"></div>
            </div>
        </div>
        <div class="dc-dashboardbox">
            <div class="dc-user-header">
                <figure class="dc-user-img">
                    <img :src="appointment.user_image" alt="img description">
                </figure>
                <div class="dc-title">
                    <h3>{{appointment.user_name}} <i class="fa fa-check-circle"></i></h3>
                    <span>{{appointment.user_location}}</span>
                </div>
                <div class="dc-rightarea dc-status" v-if="accepted == true">
                    <span>{{this.appointment_status}}</span>
                    <em>{{ trans('lang.status') }}</em>
                </div>
                <div class="dc-rightarea dc-status" v-else>
                    <i class="fas fa-spinner fa-spin" v-if="appointment.status == 'pending'"></i>
                    <span>{{appointment.status}}</span>
                    <em>{{ trans('lang.status') }}</em>
                </div>
            </div>
            <div class="dc-user-details">
                <div class="dc-user-info" v-if="appointment.patient_name">
                    <div class="dc-title">
                        <h4>{{ trans('lang.patient_name') }}</h4>
                        <span>{{appointment.patient_name}}</span>
                    </div>
                </div>
                <div class="dc-user-info" v-if="appointment.relation">
                    <div class="dc-title">
                        <h4>{{ trans('lang.relation_with_user') }}</h4>
                        <span v-if="appointment.relation == 'other'">{{appointment.other_relation}}</span>
                        <span v-else>{{appointment.relation}}</span>
                    </div>
                </div>
                <div class="dc-user-info" v-if="appointment.hospital">
                    <div class="dc-title">
                        <h4>{{ trans('lang.appoint_location') }}</h4>
                        <span>{{appointment.hospital}}</span>
                    </div>
                </div>
                <div class="dc-user-info">
                    <div class="dc-title">
                        <h4>{{ trans('lang.appointment_date') }}</h4>
                        <span>{{appointment.appointment_date}} - {{appointment.appointment_time}}</span>
                    </div>
                </div>
                <div class="dc-user-info"  v-if="user_type == 'admin'">
                    <div class="dc-title">
                        <h4>{{ trans('lang.appointment_doctor') }}</h4>
                        <span>{{appointment.doctor}}</span>
                    </div>
                </div>
                <div class="dc-user-info dc-info-required" v-if="appointment.appointment_services">
                    <div class="dc-title">
                        <h4>{{ trans('lang.services') }}</h4>
                    </div>
                    <ul class="dc-required-details" v-for="(appointment_service, index) in appointment.appointment_services" :key="index">
                        <span>{{appointment_service.speciality}}</span>
                        <li v-for="(service, service_index) in appointment_service.services" :key="service_index"><span>{{service.title}}</span></li>
                    </ul>
                </div>
                <div class="dc-required-info" v-if="appointment.comments">
                    <div class="dc-title">
                        <h4>{{ trans('lang.comment') }}</h4>
                    </div>
                    <div class="dc-description">
                        {{appointment.comments}}
                    </div>
                </div>
                <div class="dc-required-info" v-if="appointment.charges">
                    <div class="dc-title">
                        <h4>{{ trans('lang.total_price') }}</h4>
                    </div>
                    <div class="dc-description">
                        {{appointment.charges}}
                    </div>
                </div>
            </div>
            <div v-if="accepted == false && appointment.status == 'pending'">
                <div class="dc-user-steps" >
                    <div class="dc-btnarea" v-if="user_type != 'patient' && user_type != 'admin'">
                        <a href="javascript:void(0);" class="dc-btn dc-deleteinfo" v-on:click="declineAppointment(appointment)">{{trans('lang.decline')}}</a>
                        <a href="javascript:void(0);" class="dc-btn" v-on:click="acceptAppointment(appointment)">{{trans('lang.accept')}}</a>
                    </div>
                </div>
            </div>
            <div v-else>
                <div class="dc-user-steps">
                <div class="dc-btnarea dc-btnprescriptionarea">
                        <a :href="prescription_route + '/' + exact_appointment_id" class="dc-btn dc-filebtn" v-if="auth_user_role == 'doctor'">
                            <i class="ti-files"></i>
                        </a>
                        <a href="javascript:;" v-b-modal.modal-lg v-on:click="showModal('prescription-msg-modal')" data-toggle="modal" data-target="#send_message" class="dc-btn dc-send-message  dc-msgbtn" v-if="auth_user_role == 'doctor' || auth_user_role == 'regular'"><i class="ti-email"></i></a>
                        <form method="post" name="download_pdf">
                            <input type="hidden" name="pdf_booking_id" value="1025">
                            
                            <a :href="BASE_URL + '/prescription/download/' + exact_appointment_id"  class="dc-btn dc-pdfbtn" v-if="appointment.is_prescription"><i class="ti-download"></i></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Message Modal -->
        <b-modal ref="prescription-msg-modal" id="dc-prescription-msg-popup" size="lg" hide-footer :title="trans('lang.send_message')" no-close-on-backdrop>
            <div class="dc-modalcontent modal-content dc-prescription-modal-content">	
                <div class="dc-formtheme dc-prescription-msg-area">
                    <fieldset>
                        <div class="form-group">
                            <textarea id="dc-booking-msg" class="form-control" placeholder="Message" name="message" v-model="new_message"></textarea>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer dc-modal-footer">
                    <input type="submit" :value="trans('lang.send')" class="btn dc-btn btn-primary" @click="sendMessage">
                </div>
            </div>
        </b-modal>
  </div> 
</template>

<script>
import Event from '../../event.js'
export default {
    props: ['user_type', 'prescription_route'],
    data: function () {
      return {
        BASE_URL:APP_URL,
        auth_user_role:Laravel.user.role,
        is_show: false,
        is_loading: false,
        accepted: false,
        patient_id:'',
        msg_receiver_id:'',
        appointment_status:'',
        appointment_id:'',
        exact_appointment_id:'',
        new_message:'',
        notificationSystem: {
            success: {
                position: 'topRight',
                timeout: 3000
            },
            error: {
                position: 'topRight',
                timeout: 3000
            },
        }
      }
    },
  methods: {
    showMessage(message) {
        return this.$toast.success(' ', message, this.notificationSystem.success)
    },
    showError(error) {
        return this.$toast.error(' ', error, this.notificationSystem.error)
    },
    showModal: function (reference) {
        this.$refs[reference].show();
    },
    sendMessage () {
        var self = this;
        if (self.new_message === null || self.new_message.match(/^ *$/) !== null) {
            self.showError('Message is required');
            return false;
        }
        axios.post(APP_URL + '/message/send-private-message',{
            author_id : Laravel.user.id,
            receiver_id: self.msg_receiver_id,
            message: self.new_message,
            status: 0
        })
        .then(function (response) {
            if (response.data.author) {
                self.$refs['prescription-msg-modal'].hide();
                self.new_message=""
                self.showMessage('Message sent');
            }
        })
        .catch(function (error) {});
    },
    acceptAppointment(appointment) {
        if (appointment) {
            var self = this;
            self.is_loading = true;
            axios.post(APP_URL + '/doctor/accept-appointment', {appointment:appointment, patient_id:self.patient_id})
            .then(function (response) {
                if (response.data.type === 'success') {
                    self.is_loading = false;
                    self.appointment_status = response.data.status
                    self.appointment.status = response.data.status
                    self.accepted = true;
                    jQuery('#'+self.appointment_id).text(response.data.status)
                    self.showMessage(response.data.message);
                } else {
                    self.is_loading = false;
                    self.showError(response.data.message);
                }
            })
            .catch(function (error) {
                self.is_loading = false;
            })
        }
    },
    declineAppointment(appointment) {
        if (appointment) {
            var self = this;
            self.is_loading = true;
            axios.post(APP_URL + '/doctor/decline-appointment', {appointment:appointment, patient_id:self.patient_id})
            .then(function (response) {
                if (response.data.type === 'success') {
                    self.is_loading = false;
                    self.accepted = true;
                    self.appointment_status = response.data.status;
                    self.appointment.status = response.data.status
                    jQuery('#'+self.appointment_id).text(response.data.status);
                    self.showMessage(response.data.message);
                } else {
                    self.is_loading = false;
                    self.showError(response.data.message);
                }
            })
            .catch(function (error) {
                self.is_loading = false;
            })
        }
    },
  },
  mounted: function() {
    let self = this;
    Event.$on('display-detail', (data) => {
        self.is_show = false;
        self.appointment = data.appointments;
        self.is_show = true;
        self.accepted = false;
        self.patient_id = data.patient_id;
        self.appointment_id = data.appointment_id;
        self.exact_appointment_id = data.exact_appointment_id;
        if (self.auth_user_role == 'doctor') {
            self.msg_receiver_id =  data.patient_id;
        } else {
            self.msg_receiver_id =  data.appointments.doctor_id;
        }
        jQuery('.dc-appointment-detail-wraper').css('display','block');
    })
  }
}
</script>