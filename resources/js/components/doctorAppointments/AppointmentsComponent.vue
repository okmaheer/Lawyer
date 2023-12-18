<template>
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
      <div class="dc-haslayout">
        <div class="dc-dashboardbox dc-apointments-wrap">	
          <div class="dc-apointments-holder">
            <div class="dc-appointment-calendar">
                <div :style="{ width: '300px', border: '1px solid #d9d9d9', borderRadius: '4px' }">
                  <a-calendar :fullscreen="false" @select="onSelect" @panelChange="onPanelChange" v-model="selected_day" />
                </div>
            </div>
            <div class="dc-recentapointdate-holder">
              <div class="dc-recentapointdate">
                  <h2>{{this.appointments.count}}</h2>
                  <span>{{ trans('lang.total_appoint') }} <em>{{this.selected_date}}</em></span>
              </div>
            </div>
          </div>
          <div class="dc-searchresult-head">
            <div class="dc-title"><h3>{{ trans('lang.recent_appoint') }}</h3></div>
            <div class="dc-rightarea">
              <a href="javascript:;" class="dc-btn dc-add-booking dc-btn-tab" v-on:click="showComponent('doc-booking-modal')" v-if="auth_user_role == 'doctor'">Add booking</a>
              <add-appointment-component v-if="auth_user_role == 'doctor' && enableComponent"/>
            </div>
          </div>
          <div class="dc-recentapoint-holder" v-if="this.appointments.count > 0">
            <appointment-list :appointment_list="appointments.appointment" :paginated_index="commentsToShow"></appointment-list>
            <div class="dc-btnarea appointment-load-more" v-if="commentsToShow < appointments.count">
              <a href="javascript:void(0);" class="dc-btn"  @click="commentsToShow += 6">
                  {{ trans('lang.load_more') }}
              </a>
            </div>
          </div>
          <div class="dc-emptydata-holder" v-else>
            <div class="dc-emptydata">
              <div class="dc-emptydetails">
                <img :src="url+'/images/empty-images/no-record.png'">
                <em> {{ trans('lang.no_record') }} </em>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 dc-responsive-mt mt-xl-0">
      <appointment-detail :user_type="type" :prescription_route="prescription_route"></appointment-detail>
    </div>
  </div>
</template>
<script>
import moment from 'moment'
// import 'ant-design-vue/dist/antd.css'
export default {
  props: ['type', 'selected_appointment_date', 'appointment_type', 'prescription_route'],
  data: function () {
      return {
        url:APP_URL,
        auth_user_role:Laravel.user.role,
        pageCount: 0,
        has_appoinrments:false,
        date_change: false,
        selected_day:moment(),
        appointments:[],
        selected_date:'',
        commentsToShow: 6,
        enableComponent: false,
      }
    },
  methods: {
    showComponent: function (reference) {
      this.enableComponent = false
      setTimeout(() => {
        
        this.enableComponent = true
      }, 100);
      // this.showModal(reference)
    },
    showModal: function (reference) {
      this.$refs[reference].show();
    },
    onSelect(value) {
      jQuery('.dc-appointment-detail-wraper').css('display','none');
      var date = value.year() +'-'+value.format('M')+'-'+value.date();
      this.selected_date = this.getMonthName(value)+' '+value.date()+', '+value.year();
      this.getAppointments(date);
    },
    onPanelChange(value, mode) {
      jQuery('.dc-appointment-detail-wraper').css('display','none');
      var date = value.year() +'-'+value.format('M')+'-'+value.date();
      this.getAppointments(date);
    },
    getAppointments(date) {
      var self = this;
      axios.post(APP_URL + '/'+self.type+'/get-appointments', {date:date})
      .then(function (response) {
        if (response.data.type === 'success') {
            self.appointments = response.data.appointments;
            self.has_appoinrments = true;
            self.pageCount = self.appointments.count;
        } else {
            self.appointments = response.data.appointments;
            self.has_appoinrments = false;
        }
      })
      .catch(function (error) {

      })
    },
    getMonthName: function(value) {
      var weekday = new Array(7);
      weekday[1] = "Jan";
      weekday[2] = "Feb";
      weekday[3] = "Mar";
      weekday[4] = "Apr";
      weekday[5] = "May";
      weekday[6] = "June";
      weekday[7] = "July";
      weekday[8] = "Aug";
      weekday[9] = "Sep";
      weekday[10] = "Oct";
      weekday[11] = "Nov";
      weekday[12] = "Dec";
      var n = weekday[value.format('M')];
      return n;
    },
  },
  mounted: function() {
    jQuery('.ant-fullcalendar-cell.ant-fullcalendar-today.ant-fullcalendar-selected-day').removeClass('ant-fullcalendar-today ant-fullcalendar-selected-day');
    this.getAppointments();
  }
}
</script>