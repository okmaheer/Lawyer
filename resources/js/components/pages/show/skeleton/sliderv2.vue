<template>
    <content-loader 
    :speed='2'
    :width='400'
    :height='160'
    backgroundColor="#f3f3f3"
    foregroundColor="#ecebeb"
  >

    <rect x="225" y="20" rx="0" ry="0" width="106" height="115" /> 
    <rect x="65" y="36" rx="0" ry="0" width="79" height="3" /> 
    <rect x="65" y="42" rx="0" ry="0" width="106" height="6" /> 
    <rect x="66" y="52" rx="0" ry="0" width="79" height="3" /> 
    <rect x="66" y="62" rx="0" ry="0" width="143" height="14" /> 
    <rect x="67" y="81" rx="0" ry="0" width="143" height="14" /> 
    <rect x="68" y="107" rx="0" ry="0" width="40" height="19" />
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