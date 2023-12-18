<template>
    <div class="dc-dashboardbox dc-prescriptionbox dc-medications">
        <div class="dc-title">
            <h4>{{ trans('lang.medications') }}:</h4> <a href="javascript:;" class="dc-add-medician" v-on:click="addMedication">{{ trans('lang.add_new') }}</a>
        </div>
        <div class="dc-formtheme dc-userform" id="dc-medican-html">
            <fieldset>
                <div class="form-group form-group-half">
                    <input id="dc-med-title" type="text" class="form-control" :placeholder="trans('lang.name')">
                </div>
                <div class="form-group form-group-half">
                    <span class="dc-select">
                        <select id="dc-medicine-types">
                            <option value="">{{ trans('lang.select_type') }}</option>
                            <option v-for="(item, index) in medicine_types" :key="index" :value="item.id">{{ item.title }}</option>
                        </select>
                    </span>
                </div>
                <div class="form-group form-group-half">
                    <span class="dc-select">
                        <select id="dc-medicine-duration">
                            <option value="">{{ trans('lang.select_medi_duration') }}</option>
                            <option v-for="(item, index) in medicine_durations" :key="index" :value="item.id">{{ item.title }}</option>
                        </select>
                    </span>
                </div>
                <div class="form-group form-group-half">
                    <span class="dc-select">
                        <select id="dc-medicine-usage">
                            <option value="">{{ trans('lang.select_medi_usage') }}</option>
                            <option v-for="(item, index) in medicine_usages" :key="index" :value="item.id">{{ item.title }}</option>
                        </select>
                    </span>
                </div>
                <div class="form-group">
                    <input type="text" id="dc-medicine-details" class="form-control" :placeholder="trans('lang.add_comment')">
                </div>
            </fieldset>
            <div class="dc-visal-sign" v-for="(medi, index) in medications" :key="index">
                <fieldset>
                    <div class="form-group form-group-half">
                        <input type="text" :name="'medications['+index+'][medicine_name]'" class="form-control" :placeholder="trans('lang.name')" v-model="medi.title">
                    </div>
                    <div class="form-group form-group-half">
                        <span class="dc-select">
                            <select :name="'medications['+index+'][medicine_type]'" v-model="medi.medicine_type_id">
                                <option value="">{{ trans('lang.select_type') }}</option>
                                <option v-for="(item, index) in medicine_types" :key="index" :value="item.id">{{ item.title }}</option>
                            </select>
                        </span>
                    </div>
                    <div class="form-group form-group-half">
                        <span class="dc-select">
                            <select :name="'medications['+index+'][medicine_duration]'" v-model="medi.medicine_duration_id">
                                <option value="">{{ trans('lang.select_medi_duration') }}</option>
                                <option v-for="(item, index) in medicine_durations" :key="index" :value="item.id">{{ item.title }}</option>
                            </select>
                        </span>
                    </div>
                    <div class="form-group form-group-half">
                        <span class="dc-select">
                            <select :name="'medications['+index+'][medicine_usage]'" v-model="medi.medicine_usage_id">
                                <option value="">{{ trans('lang.select_medi_usage') }}</option>
                                <option v-for="(item, index) in medicine_usages" :key="index" :value="item.id">{{ item.title }}</option>
                            </select>
                        </span>
                    </div>
                    <div class="form-group dc-delete-group">
                        <input type="text" :name="'medications['+index+'][comment]'" class="form-control" :placeholder="trans('lang.add_comment')" v-model="medi.comment">
                        <a href="javascript:;" class="dc-deletebtn dc-remove-visual" v-on:click="deleteMedication(index)"><i class="lnr lnr-trash"></i></a>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props:['medicine_types_array', 'medicine_durations_array', 'medicine_usages_array', 'saved_medications'],
    data ()
    {
        return {
            medicine_types: JSON.parse(this.medicine_types_array),
            medicine_durations: JSON.parse(this.medicine_durations_array),
            medicine_usages: JSON.parse(this.medicine_usages_array),
            medications:JSON.parse(this.saved_medications), 
            medication: {
                title:'',
                medicine_type_id:'',
                medicine_duration_id:'',
                medicine_usage_id:'',
                comment:''
            }
        }
    },
    methods: {
        addMedication () {
            this.medication.title = $('#dc-med-title').val();
            this.medication.medicine_type_id = $('select#dc-medicine-types').val();
            this.medication.medicine_duration_id = $('select#dc-medicine-duration').val();
            this.medication.medicine_usage_id = $('select#dc-medicine-usage').val();
            this.medication.comment = $('#dc-medicine-details').val();

            if (this.medication.title == null || this.medication.title == '' || this.medication.title.match(/^ *$/) !== null) {
                this.showError('Please enter medicine name')
                return false;
            } else if (!this.medication.medicine_type_id) {
                this.showError('Please select medicine type')
                return false;
            } else if (!this.medication.medicine_duration_id) {
                this.showError('Please select medicine duration')
                return false;
            } else if (!this.medication.medicine_usage_id) {
                this.showError('Please select medicine usage')
                return false;
            }
            this.medications.push(
                Vue.util.extend({}, 
                this.medication, 
            ))
            this.medication = {
                title:'',
                medicine_type_id:'',
                medicine_duration_id:'',
                medicine_usage_id:'',
                comment:''
            }
            $('#dc-med-title').val('');
            $('select#dc-medicine-types').val('');
            $('select#dc-medicine-duration').val('');
            $('select#dc-medicine-usage').val('');
            $('#dc-medicine-details').val('');
        },
        deleteMedication: function (index) {
            this.medications.splice(index, 1);
        },
    },
}
</script>