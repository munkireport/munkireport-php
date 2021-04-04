<template>
  <div>
    <div class="row">
      <div class="col-md-12">
        <div class="btn-group" role="group" aria-label="Show users by role">
          <button type="button"
                  :class="{ btn: true, 'btn-default': true, 'active': show.users }"
                  @click.prevent="show.users = !show.users">
            Users
          </button>
          <button type="button"
                  :class="{ btn: true, 'btn-default': true, 'active': show.managers }"
                  @click.prevent="show.managers = !show.managers">Managers</button>
          <button type="button"
                  :class="{ btn: true, 'btn-default': true, 'active': show.admins }"
                  @click.prevent="show.admins = !show.admins">Admins</button>
        </div>
      </div>
    </div>

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
      show: {
        users: true,
        managers: true,
        admins: true,
      }
    }
  },
  methods: {
    select(userId) {
      this.$emit('selected', userId);
    }
  },
}
</script>

<style scoped>
</style>
