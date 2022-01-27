<template>
  <div>
    <div class="card-header">
      <div class="d-flex w-100 justify-content-between">
        <span>
          Business Units
        </span>
        <button type="button" class="btn btn-primary btn-sm">
          <i class="fa fa-plus"></i>
        </button>
      </div>
    </div>
    <div v-if="!items || items.length === 0">
      <h1 class="text-center">No items found</h1>
    </div>
    <ul v-else class="list-group list-group-flush">
      <template v-for="item in items">
        <slot name="item" v-bind:item="item">
          <router-link :to="to" href="#" class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
              <h5 class="mb-1" v-text="item[titleKey]"></h5>
            </div>
            <p class="mb-1 font-italic">Address / URL</p>
          </router-link>

        </slot>
      </template>
    </ul>
  </div>
</template>

<script>
export default {
  name: "ListView",
  props: ['items', 'titleKey', 'subtitleKey', 'to'],
}
</script>

<style scoped>

</style>
