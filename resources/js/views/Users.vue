<template>
  <div class="uiv container">
    <div class="row">
      <div class="col-lg-12">
        <h1>Users</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <users-list :items="users" @selected="fetchUser"></users-list>
      </div>
      <div class="col-md-8">
        <user-form :data="selectedUser"></user-form>
      </div>
    </div>
  </div>
</template>

<script>

import UsersList from '../components/UsersList';
import UserForm from '../components/UserForm';

export default {
  name: "Users",
  components: {
    'users-list': UsersList,
    'user-form': UserForm,
  },

  data() {
    return {
      users: [],
      selectedUser: null,
    }
  },

  mounted() {
    fetch('/api/v6/users')
        .then((response) => {
          return response.json();
        })
        .then((data) => {
          this.users = data.data;
        })
        .catch((e) => {
          this.error = e.message;
        });
  },

  methods: {
    fetchUser: (userId) => {
      return fetch("/api/v6/users/" + userId)
          .then((response) => {
            return response.json();
          })
          .then((data) => {
            this.selectedUser = data.data;
          })
          .catch((e) => {
            this.error = e.message;
          });
    }
  }
}
</script>

<style scoped>

</style>
