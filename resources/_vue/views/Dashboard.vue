<template>
  <div>Sorry, currently broken and needs update</div>
<!--  <grid-layout-->
<!--      :layout.sync="layout"-->
<!--      :col-num="12"-->
<!--      :row-height="30"-->
<!--      :is-draggable="true"-->
<!--      :is-resizable="true"-->
<!--      :is-mirrored="false"-->
<!--      :vertical-compact="true"-->
<!--      :margin="[10, 10]"-->
<!--      :use-css-transforms="true"-->
<!--      :responsive="true"-->
<!--      >-->
<!--    <grid-item v-for="item in layout"-->
<!--               :static="item.static"-->
<!--               :x="item.x"-->
<!--               :y="item.y"-->
<!--               :w="item.w"-->
<!--               :h="item.h"-->
<!--               :i="item.i"-->
<!--               :key="item.i">-->
<!--      <component :is="item.widget" />-->
<!--    </grid-item>-->
<!--  </grid-layout>-->
</template>

<script>
// vue-grid-layout not compat with VueJS3
// import VueGridLayout from 'vue-grid-layout';
// import ButtonWidget from '../components/widgets/ButtonWidget.vue';
// import DiskReportWidget from '../components/widgets/DiskReportWidget.vue';
// import ScrollBoxWidget from '../components/widgets/ScrollBoxWidget.vue';
// import EventsWidget from '../components/widgets/EventsWidget.vue';

// import layout from '../layouts/demo';

export default {
  name: "dashboard",
  components: {
    // GridLayout: VueGridLayout.GridLayout,
    // GridItem: VueGridLayout.GridItem,
    // 'widget-disk-report': DiskReportWidget,
    // ButtonWidget,
    // ScrollBoxWidget,
    // 'widget-event-messages': EventsWidget,
  },
  // data() {
  //   return {
  //     layout,
  //   };
  // },
  mounted() {

  }
}
</script>

<style scoped>

</style>
