<template>
  <content-loader
    :speed='2'
    :width='1903'
    :height='597'
    primaryColor="#f8f7f7"
    secondaryColor="#ecebeb"
  >
    <rect x="-1" y="-10" rx="3" ry="3" width="475" height="556" /> 
    <rect x="490" y="52" rx="3" ry="3" width="261" height="506" /> 
    <rect x="11" y="298" rx="3" ry="3" width="350" height="30" /> 
    <rect x="771" y="50" rx="3" ry="3" width="261" height="506" /> 
    <rect x="1051" y="51" rx="3" ry="3" width="261" height="506" /> 
    <rect x="1328" y="50" rx="3" ry="3" width="261" height="506" /> 
    <rect x="1603" y="49" rx="3" ry="3" width="261" height="506" /> 
    <rect x="513" y="501" rx="0" ry="0" width="85" height="27" />
  </content-loader>
</template>
<script>
import { ContentLoader } from "vue-content-loader"

export default {
    components: { ContentLoader },
}
</script>