<template>
    <content-loader
        :speed='2'
        :width='1110'
        :height='520'
        primaryColor="#f8f7f7"
        secondaryColor="#ecebeb"
    >
        <rect x="601" y="1" rx="0" ry="0" width="390" height="500" /> 
        <rect x="-1" y="41" rx="0" ry="0" width="430" height="56" /> 
        <rect x="5" y="74" rx="0" ry="0" width="186" height="17" /> 
        <rect x="-20" y="115" rx="0" ry="0" width="525" height="45" /> 
        <rect x="-3" y="207" rx="0" ry="0" width="525" height="72" /> 
        <rect x="4" y="332" rx="0" ry="0" width="160" height="50" /> 
        <rect x="229" y="330" rx="0" ry="0" width="160" height="50" />
    </content-loader>
</template>
<script>
  import { ContentLoader } from "vue-content-loader"

  export default {
    components: { ContentLoader },
  }
</script>