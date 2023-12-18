<template>
    <div class="dc-dashboardbox dc-prescriptionbox">
        <div class="dc-title">
            <h4>Common Issue:</h4><a href="javascript:;" class="dc-add-vitals" v-on:click="addCommonIssue">Add New</a>
        </div>
        <div class="dc-formtheme dc-userform" id="dc-vital-signs">
            <fieldset>
                <div class="form-group form-group-half">
                    <span class="dc-select">
                        <select id="dc-common-issue-select">
                            <option value="">Select vital sign</option>
                            <option v-for="(item, index) in vital_signs" :key="index" :value="item.id">{{ item.title }}</option>
                        </select>
                    </span>
                </div>
                <div class="form-group form-group-half dc-delete-group">
                    <input type="text" id="dc-vital-signs-val" class="form-control" placeholder="Value">
                </div>
            </fieldset>
            <div class="dc-visal-sign" v-for="(issue_item, index) in Issues" :key="index">
                <fieldset>
                    <div class="form-group form-group-half">
                        <span class="dc-select">
                            <select :name="'common_issues['+index+'][vital_sign]'" v-model="issue_item.vital_sign_id">
                                <option value="">Select vital sign</option>
                                <option v-for="(select_item, select_index) in vital_signs" :key="select_index" :value="select_item.id">{{ select_item.title }}</option>
                            </select>
                        </span>
                    </div>
                    <div class="form-group form-group-half dc-delete-group">
                        <input type="text" :name="'common_issues['+index+'][value]'" class="form-control" placeholder="Value" v-model="issue_item.value">
                        <a href="javascript:;" class="dc-deletebtn dc-remove-visual" v-on:click="deleteCommonIssue(index)"><i class="lnr lnr-trash"></i></a>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props:['vital_signs_array', 'saved_vital_sign'],
    data ()
    {
        return {
            vital_signs: JSON.parse(this.vital_signs_array),
            Issues:JSON.parse(this.saved_vital_sign), 
            issue: {
                vital_sign_id:'',
                value:''
            },
            notificationSystem: {
                error: {
                    position: "topRight",
                    timeout: 4000
                }
            },
        }
    },
    methods: {
        showError(message) {
            return this.$toast.error(' ', message, this.notificationSystem.error);
        },
        addCommonIssue () {
            this.issue.vital_sign_id = $('select#dc-common-issue-select').val();
            this.issue.value = $('#dc-vital-signs-val').val();
            if (!this.issue.vital_sign_id) {
                this.showError('Please select vital sign')
                return false;
            }
            this.Issues.push(
                Vue.util.extend({}, 
                this.issue, 
            ))
            this.issue.vital_sign_id = ""
            this.issue.value = ""
            $('select#dc-common-issue-select').val('');
            $('#dc-vital-signs-val').val('');
        },
        deleteCommonIssue: function (index) {
            this.Issues.splice(index, 1);
        },
    },
}
</script>