<template>
  <form>
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" class="form-control" id="username" v-model="data.name" />
    </div>
    <div class="form-group">
      <label for="displayName">Display Name</label>
      <input type="text" class="form-control" id="displayName" v-model="data.display_name" />
    </div>
    <div class="form-group">
      <label for="email">E-mail</label>
      <input type="text" class="form-control" id="email" v-model="data.email" />
    </div>
    <div class="form-group">
      <label for="locale">Preferred Locale</label>
      <select id="locale" v-model="data.locale">
        <option disabled value="">Please select one</option>
        <option value="en-US">en-US</option>
      </select>
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-primary" @click.prevent="update">Update</button>
    </div>
  </form>
</template>

<script>
export default {
  name: "UserForm",
  data() {
    return {
      id: null,
      name: "",
      display_name: "",
      email: "",
      locale: "",
    }
  },
  methods: {
    update: () => {
      console.log('update user');
    }
  },
}
</script>

<style scoped>

</style>
