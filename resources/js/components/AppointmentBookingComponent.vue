<template>
  <div>
    <div class="dc-title">
        <span>{{ trans('lang.who_visiting_doc') }}</span>
        <div class="dc-tabbtns">
            <input id="dc-myself" type="radio" name="patient" value="myself" checked="" v-model="patient_type" v-on:change="selectedPatient(patient_type)">
            <label for="dc-myself">
                {{ trans('lang.myself') }}
            </label>
            <input id="dc-someone" type="radio" name="patient" value="someone" v-model="patient_type" v-on:change="selectedPatient(patient_type)">
            <label for="dc-someone">
                {{ trans('lang.else') }}
            </label>
        </div>
    </div>
    <div class="dc-reason-holder">
      <transition name="fade">
        <fieldset v-if="show_myself">
          <div class="form-group form-group-half">
              <input type="text" id="patient_name" name="patient_name" class="form-control" :placeholder="trans('lang.patient_name')">
          </div>
          <div class="form-group form-group-half">
            <span class="dc-select">
                <select :data-placeholder="trans('lang.relation_with_you')" name="relation" id="relation" v-model="relation" v-on:change="relationSelected(relation)">
                  <option value="null">{{ trans('lang.relation_with_you') }}</option>
                  <option value="brother">{{ trans('lang.brother') }}</option>
                  <option value="wife">{{ trans('lang.wife') }}</option>
                  <option value="mother">{{ trans('lang.mother') }}</option>
                  <option value="sister">{{ trans('lang.sister') }}</option>
                  <option value="other">{{ trans('lang.other') }}</option>
                </select>
            </span>
          </div>
          <div class="form-group">
            <input type="text" id="other_relation" name="other_relation" class="form-control" :placeholder="trans('lang.relation')" v-if="selectRelation">
          </div>
        </fieldset>
      </transition>
      <fieldset>
          <div class="form-group form-group-half">
              <span class="dc-select">
                  <select v-if="doctor_hospitals.length > 0" name="hospital" id="appointment_hospital" v-on:change="getHospitalServices(user_id)" v-model="selected_hospital" placeholder="select hospital">
                      <option value="">{{ trans('lang.select_hospital') }}</option>
                      <option 
                        v-for="(hospital, index) in doctor_hospitals" 
                        :key="index" 
                        :value="hospital.id"
                      >
                      {{hospital.name}} {{hospital.type}}
                      </option>
                  </select>
              </span>
          </div>
      </fieldset>
    </div>
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
              <li class="hospital-price">{{ chargesLabel }} <em>{{ hospital_services.price }}</em></li>
              <li class="service-price" v-for="(service, charges_index) in charges" :key="charges_index">{{service.title}}: <em>{{currency}}{{service.price}}</em></li>
              <li class="total-price">{{ trans('lang.total_charges') }} <em>{{this.currency}}{{this.total_charges}}</em></li>
            </ul>
            <input type="hidden" :value="total_charges" name="total_charges">
        </div>
      </div>
    </transition>
    <textarea name="comments" class="form-control" :placeholder="trans('lang.enter_appoint_desc')"></textarea>
    <appointment-slots :doctor_id="user_id" :hospital_id="selected_hospital" v-if="show_slots"></appointment-slots>
  </div>
</template>

<script>
import Event from '../event.js'
export default {
  props: ['user_id', 'speciality', 'hospitals', 'currency'],
  data: function () {
      return {
        doctor_hospitals: JSON.parse(this.hospitals),
        hospital_services:[],
        base_url:APP_URL,
        selected_hospital:'',
        showServices : false,
        charges:[],
        selected_services:{
          title:'',
          price:'',
        },
        relation:'null',
        selectRelation:false,
        patient_type:'myself',
        total_charges:'',
        show_myself:false,
        show_slots:false,
        chargesLabel: Vue.prototype.trans('lang.hospital_charges'),
      }
    },
  methods: {
    relationSelected: function(relation) {
      if (relation == 'other') {
        this.selectRelation = true
      }
    },
    selectedPatient: function(type) {
      if (type == 'someone') {
        this.show_myself = true;
      } else {
        this.show_myself = false;
      }
    },
    getHospitalServices: function (doctor_id) {
      if(doctor_id) {
        this.show_slots = false
        var selectedLocationIndex = this.getArrayIndex(this.doctor_hospitals, 'id', this.selected_hospital)
        var type = this.doctor_hospitals[selectedLocationIndex].type
        if (type == Vue.prototype.trans('lang.doctor_private_clinic')) {
          this.chargesLabel = Vue.prototype.trans('lang.clinic_charges') 
        } else {
          this.chargesLabel = Vue.prototype.trans('lang.hospital_charges') 
        }
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
    toggleCollapse: function(element_id) {
      jQuery('.active.dc-paneltitle').siblings().hide('slow');
      jQuery('.active.dc-paneltitle').removeClass('active');
      jQuery('#'+element_id).addClass('active');
      jQuery('#'+element_id).siblings().show('slow');
    
    },
    displayServicePrice: function (price, title, element_id) {
      if (document.getElementById(element_id).checked == true) {
        this.total_charges = parseInt(this.total_charges) + parseInt(price);
        this.charges.push(Vue.util.extend(
          {}, this.selected_services, this.selected_services.title = title, this.selected_services.price = price)
        )
      } else {
          this.total_charges = parseInt(this.total_charges) - parseInt(price);
          this.charges = this.charges.filter(function( obj ) {
            return obj.title !== title;
          });
      }
    }
  },
  mounted: function() {
      
  }
}
</script>
<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity .3s ease;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}

</style>