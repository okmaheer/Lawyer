<template>
    <content-loader
    :speed='2'
    :width='1140'
    :height='373'
    primaryColor="#f6f4f4"
    secondaryColor="#d1cccc"
  >
    <rect x="3" y="19" rx="3" ry="3" width="540" height="333" /> 
    <rect x="570" y="62" rx="3" ry="3" width="540" height="95" /> 
    <rect x="11" y="298" rx="3" ry="3" width="350" height="30" /> 
    <rect x="1328" y="50" rx="3" ry="3" width="261" height="506" /> 
    <rect x="1603" y="49" rx="3" ry="3" width="261" height="506" /> 
    <rect x="513" y="501" rx="0" ry="0" width="85" height="27" /> 
    <rect x="568" y="225" rx="3" ry="3" width="160" height="53" /> 
    <rect x="767" y="225" rx="3" ry="3" width="160" height="53" />
  </content-loader>
</template>
<script>
  import { ContentLoader } from "vue-content-loader"

  export default {
    components: { ContentLoader },
  }
</script>