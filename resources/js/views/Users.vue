<template>
  <div class="uiv container">
    <div class="row">
      <div class="col-lg-12">
        <h1>Users</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <users-table :items="users"></users-table>
      </div>
    </div>
  </div>
</template>

<script>

import UsersTable from '../components/UsersTable';

export default {
  name: "Users",
  components: {
    'users-table': UsersTable,
  },

  data() {
    return {
      users: [],
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
  }
}
</script>

<style scoped>

</style>
