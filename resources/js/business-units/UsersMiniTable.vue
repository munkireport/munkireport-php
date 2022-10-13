<template>
  <table class="table">
    <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">E-mail</th>
      <th scope="col">Role</th>
      <th scope="col"></th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="user in users">
      <td v-text="user.name"></td>
      <td v-text="user.email"></td>
      <td v-text="user.role"></td>
      <td class="text-right"><a title="remove user from business unit" href="#" @click.prevent="$emit('removed', user)"><div>BIconXSquareFill</div></a></td>
    </tr>
    </tbody>
  </table>
</template>

<script>

export default {
  name: "UsersMiniTable",
  props: {
    users: {
      type: Array,
      default: function() {
        return [];
      }
    }
  },
  components: {
  },
  methods: {
  }
}
</script>

<style scoped>

</style>
