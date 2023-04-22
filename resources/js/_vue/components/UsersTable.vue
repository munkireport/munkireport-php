<template>
  <div>
    <table class="munkireport table table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>E-mail</th>
          <th>SSO</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="items.length === 0">
          <td>No data available</td>
        </tr>
        <tr v-for="user in items" :key="user.id">
          <td v-text="user.id"></td>
          <td v-text="user.name"></td>
          <td v-text="user.email"></td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "UsersTable",
  props: [
      'items'
  ],
  data() {
    return {
    }
  }
}
</script>

<style scoped>
.munkireport.table {
  cursor: pointer;
}
</style>
