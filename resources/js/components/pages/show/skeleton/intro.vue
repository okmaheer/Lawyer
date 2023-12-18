<template>
    <content-loader
        :speed='2'
        :width='1110'
        :height='520'
        primaryColor="#f8f7f7"
        secondaryColor="#ecebeb"
    >
        <rect x="601" y="100" rx="0" ry="0" width="500" height="250" /> 
        <rect x="0" y="70" rx="0" ry="0" width="270" height="16" /> 
        <rect x="0" y="115" rx="0" ry="0" width="380" height="45" /> 
        <rect x="0" y="207" rx="0" ry="0" width="525" height="72" /> 
        <rect x="4" y="332" rx="0" ry="0" width="160" height="50" /> 
    </content-loader>
</template>
<script>
  import { ContentLoader } from "vue-content-loader"

  export default {
    components: { ContentLoader },
  }
</script>