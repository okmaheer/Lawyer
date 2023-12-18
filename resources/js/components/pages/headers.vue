<template>
    <div class="amt-section-select amt-profile-settings">
        <ul class="at-profile-setting__imgs">
            <li v-for="(header, index) in headers" :key="index+'-header'">
                <input type="radio" :name="'header'" :id="'header-style'+index" :value="header.value" v-model="form.pageMeta.header">
                <label :for="'header-style'+index">
                    <span>
                        <img :src="header.image" alt="Image Description">
                        <span class="at-tick"><span><i class="fas fa-check"></i></span></span>
                    </span>
                </label>
            </li>
        </ul>
    </div>
</template>
<script>
export default {
    props:['form'],
    data() {
        return {
            baseUrl: APP_URL,
            headers:this.getHeaderList()
        }
    },
};
</script>
