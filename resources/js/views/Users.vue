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
        <users-list :items="users" @selected="selectUser"></users-list>
      </div>
      <div class="col-md-8">
        <user-form :user-id="selectedUserId"></user-form>
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
      loadingUserId: null,
      selectedUser: null,
      selectedUserId: null,
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

    selectUser(userId) {
      this.selectedUserId = userId;
    }
  }
}
</script>

<style scoped>

</style>
