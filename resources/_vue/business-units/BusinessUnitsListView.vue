<template>
  <div>
    <div class="card-header">
        <form @submit.prevent="$emit('create', { name: newBusinessUnitName })">
          <div class="input-group">
            <input type="text" class="form-control" id="nameInput" name="name"
                   aria-describedBy="nameInput" placeholder="New business unit" aria-label="New business unit name"
                   v-model="newBusinessUnitName"
            />
            <div class="input-group-append">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-plus"></i>
              </button>
            </div>

          </div>
        </form>
    </div>
    <div v-if="loading" class="mx-3 my-3">
      <div v-if="loading" class="spinner-border" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>

    <div v-if="(!items || items.length === 0) && !loading">
      <slot name="empty">
        <h1 class="text-center">No items found</h1>
      </slot>
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
  name: "BusinessUnitsListView",
  props: ['items', 'titleKey', 'subtitleKey', 'to', 'loading'],
  data() {
    return {
      newBusinessUnitName: "",
    }
  }
}
</script>

<style scoped>

</style>
