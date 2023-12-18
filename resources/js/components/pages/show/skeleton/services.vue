<template>
    <content-loader 
    :speed='2'
    :width='400'
    :height='50'
    backgroundColor="#f3f3f3"
    foregroundColor="#ecebeb"
  >
    <rect x="5" y="16" rx="3" ry="3" width="46" height="2" /> 
    <rect x="3" y="27" rx="3" ry="3" width="68" height="9" /> 
    <rect x="85" y="16" rx="3" ry="3" width="46" height="2" /> 
    <rect x="82" y="28" rx="3" ry="3" width="68" height="9" /> 
    <rect x="326" y="16" rx="3" ry="3" width="46" height="2" /> 
    <rect x="245" y="16" rx="3" ry="3" width="46" height="2" /> 
    <rect x="166" y="16" rx="3" ry="3" width="46" height="2" /> 
    <rect x="244" y="28" rx="3" ry="3" width="68" height="9" /> 
    <rect x="165" y="28" rx="3" ry="3" width="68" height="9" /> 
    <rect x="325" y="27" rx="3" ry="3" width="68" height="9" />
  </content-loader>
</template>
<script>
import { ContentLoader } from 'vue-content-loader'

export default {
    name: 'PropertyListSkeleton',
    props:['items'],
    components: { 
        ContentLoader
    },
}
</script>