<template>
  <div class="uiv container">
    <div class="row">
      <div class="col-lg-12">
        <div class="page-header">
          <h1>Users</h1>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <users-list :items="users" @selected="fetchUser"></users-list>
      </div>
      <div class="col-md-8">
        <Spinner v-if="loading"></Spinner>
        <user-form v-if="selectedUser != null" :initial-data="selectedUser" :user-id="selectedUser.id"></user-form>
        <p v-else>No data to display</p>
      </div>
    </div>
  </div>
</template>

<script>

import UsersList from '../components/UsersList';
import UserForm from '../components/UserForm';
import Spinner from '../components/Spinner';

export default {
  name: "Users",
  components: {
    'users-list': UsersList,
    'user-form': UserForm,
    Spinner,
  },

  data() {
    return {
      users: [],
      loading: true,
      selectedUser: null,
      error: null,
    }
  },

  mounted() {
    this.loading = true;
    fetch('/api/v6/users')
        .then((response) => {
          return response.json();
        })
        .then((data) => {
          this.users = data.data;
        })
        .catch((e) => {
          this.error = e.message;
        })
        .finally(() => {
          this.loading = false;
        });
  },

  methods: {
    fetchUser(userId) {
      this.loading = true;
      console.log(`Fetching User ID ${userId}`);

      return fetch("/api/v6/users/" + userId)
          .then((response) => {
            return response.json();
          })
          .then((data) => {
            this.selectedUser = data.data;
          })
          .catch((e) => {
            this.error = e.message;
          })
          .finally(() => {
            this.loading = false;
          });
    }
  }
}
</script>

<style scoped>

</style>
