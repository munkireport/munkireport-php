<template>
  <div class="card h-100 shadow-sm">
    <div class="card-header" :title="title">
      <i v-if="icon" class="fa" :class="[icon]"></i>
      <span v-wait-for-t>{{ $t(title) }}</span>
      <a v-if="listingUrl" :href="listingUrl" class="pull-right text-reset"><i class="fa fa-list"></i></a>
    </div>
    <div class="list-group scroll-box" style="max-height: 308px">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: "ScrollBoxWidget",
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
