<template>
  <div class="element-form-wrapper" v-if="slider">
    <div class="amt-dhb-main_content">
        <div class="amt-dhb-heading"><h3>{{ trans('lang.content') }}</h3></div>
    </div>
    <div class="amt-formcontactus">
      <fieldset>
        <div class="form-group">
          <div class="amt-select">
            <select class="form-control" v-on:change="selectSlider" v-model="slider.slider_id">
              <option value="">{{ trans('lang.choose_slider') }}</option> 
              <option v-for="(slide, index) in sliders" :key="index" :value="slide.id">{{ slide.title }}</option>
            </select>
          </div>
        </div>
      </fieldset>
    </div>
  </div>
</template>
<script>
import Event from '../../../event'

export default {
  props: ['parent_index', 'element_id', 'slider', 'sliders'],
  mounted: function() {
  },
  methods: {
    selectSlider: function () {
      Event.$emit('slider-selected', {id:this.slider.slider_id})
    },
  }
}
</script>
