<template>
  <div>
    <div class="list-group">
      <a v-for="item in items" href="#" class="list-group-item" @click.stop="select(item.id)">
        <h4 class="list-group-item-heading" v-text="item.name"></h4>
        <p class="list-group-item-text">
          {{ item.email }}
        </p>
      </a>
    </div>
  </div>
</template>

<script>
export default {
  name: "UsersList",
  props: [
      'items'
  ],
  data() {
    return {
    }
  },
  methods: {
    select(userId) {
      this.$emit('selected', userId);
    }
  }
}
</script>

<style scoped>
</style>
