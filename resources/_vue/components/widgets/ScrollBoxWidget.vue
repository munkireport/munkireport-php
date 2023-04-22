<template>
  <Widget :icon="icon" :i18n-title="title" :link="listingUrl">
    <div class="list-group scroll-box" style="max-height: 308px">
      <slot></slot>
    </div>
  </Widget>
</template>

<script>
import Widget from '../Widget.vue';

export default {
  name: "ScrollBoxWidget",
  components: {
    Widget,
  },
  props: {
    tooltip: String,
    icon: String,
    title: String,
    listingUrl: String
  }
}
</script>

<style scoped>
.scroll-box {
  overflow-y: scroll;
}
</style>
