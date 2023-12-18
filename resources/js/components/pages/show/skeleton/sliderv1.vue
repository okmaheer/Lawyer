<template>
    <content-loader 
    :speed='2'
    :width='400'
    :height='160'
    backgroundColor="#f3f3f3"
    foregroundColor="#ecebeb"
  >

  <rect x="164" y="69" rx="0" ry="0" width="1" height="21" /> 
    <rect x="90" y="9" rx="0" ry="0" width="106" height="115" /> 
    <rect x="222" y="54" rx="0" ry="0" width="97" height="9" /> 
    <rect x="222" y="67" rx="0" ry="0" width="106" height="14" /> 
    <rect x="223" y="85" rx="0" ry="0" width="95" height="8" /> 
    <rect x="224" y="97" rx="0" ry="0" width="51" height="9" /> 
    <rect x="5" y="78" rx="0" ry="0" width="39" height="7" /> 
    <rect x="278" y="97" rx="0" ry="0" width="51" height="9" /> 
    <rect x="348" y="78" rx="0" ry="0" width="39" height="7" />
  </content-loader>
</template>
<script>
import { ContentLoader } from 'vue-content-loader'

export default {
    components: { 
        ContentLoader
    },
}
</script>