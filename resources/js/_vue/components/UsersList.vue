<template>
  <div>
    <div class="row">
      <div class="col-md-12">
        <div class="input-group">
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-eye-open"></span>
          </span>

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

    </div>
    <div class="row" style="margin-top: 20px;">
      <div class="col-md-12">
        <div class="list-group">
          <a v-for="item in items"
             href="#"
             :class="{ 'list-group-item': true, 'active': selectedId === item.id }"
             @click.stop="select(item.id)">

            <Spinner v-if="loading === item.id" class="spinner-badge">!</Spinner>
            <h4 class="list-group-item-heading" v-text="item.name"></h4>
            <p class="list-group-item-text">
              {{ item.email }}
              {{ item.role }}
            </p>
          </a>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import Spinner from './Spinner.vue';
export default {
  name: "UsersList",
  components: {Spinner},
  props: [
      'items',
      'loading'
  ],
  data() {
    return {
      show: {
        users: true,
        managers: true,
        admins: true,
      },
      selectedId: null,
    }
  },
  methods: {
    select(userId) {
      this.selectedId = userId;
      this.$emit('selected', userId);
    }
  },
}
</script>

<style scoped>
.spinner-badge {
  float: right;
  margin-right: 20px;
  position: relative;
  width: 20px;
  height: 20px;
}
</style>
