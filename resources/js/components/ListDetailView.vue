<template>
  <div class="row no-gutters h-100">
    <div class="col-sm">
      <div class="card rounded-0">
        <slot name="list">
          <p class="text-center text-muted">No list items available.</p>
        </slot>
      </div>
    </div>
    <div class="col-sm-8">
      <div class="card rounded-0">
        <slot name="detail">
          <p class="text-center text-muted">Select an item to view detail.</p>
        </slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ListDetailView"
}
</script>

<style scoped>

</style>
