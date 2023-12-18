<template>
  <content-loader
    :speed='2'
    :width='1140'
    :height='585'
    primaryColor="#f6f4f4"
    secondaryColor="#d1cccc"
  >
    <rect x="13" y="163" rx="3" ry="3" width="350" height="416" /> 
    <rect x="264" y="0" rx="3" ry="3" width="730" height="20" /> 
    <rect x="1328" y="50" rx="3" ry="3" width="261" height="506" /> 
    <rect x="1603" y="49" rx="3" ry="3" width="261" height="506" /> 
    <rect x="485" y="35" rx="3" ry="3" width="262" height="45" /> 
    <rect x="261" y="95" rx="3" ry="3" width="730" height="40" /> 
    <rect x="401" y="163" rx="3" ry="3" width="350" height="416" /> 
    <rect x="782" y="163" rx="3" ry="3" width="350" height="416" />
  </content-loader>
</template>

<script>
  import { ContentLoader } from "vue-content-loader"

  export default {
    components: { ContentLoader }
  }
</script>
