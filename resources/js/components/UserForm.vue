<template>
  <div class="panel panel-default">
    <div class="panel-body">


  <form>
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" class="form-control" id="username" v-model="name" />
    </div>
    <div class="form-group">
      <label for="displayName">Display Name</label>
      <input type="text" class="form-control" id="displayName" v-model="display_name"
             placeholder="A full name to display instead of the username" />
    </div>
    <div class="form-group">
      <label for="email">E-mail</label>
      <div class="input-group">
        <span class="input-group-addon">@</span>
        <input type="text" class="form-control" id="email" v-model="email" />
      </div>
      <span class="help-block">This does not need to be a valid address, but it does need to be unique. It will be
        used for logging in.</span>
    </div>
    <div class="form-group">
      <label for="locale">Preferred Locale</label>
      <select id="locale" v-model="locale">
        <option disabled value="">Please select one</option>
        <option value="en_US">English (en_US)</option>
        <option value="de_DE">Deutsch (de_DE)</option>
        <option value="fr_FR">Français (fr_FR)</option>
      </select>
    </div>
    <div class="form-group">
      <label for="objectguid">Other System Unique ID</label>
      <input readonly type="text" class="form-control" id="objectguid" :value="objectguid" />
    </div>
    <div class="alert alert-warning" role="alert" v-if="objectguid">
      This user has an ID set from another system or SSO login.
      If you modify the username or e-mail they may not be able to log in.
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-primary" @click.prevent="update">Update</button>
    </div>
  </form>

    </div>
  </div>
</template>

<script>
export default {
  name: "UserForm",
  props: ['initialData', 'userId'],
  data() {
    return {
      id: null,
      objectguid: "",
      name: "",
      display_name: "",
      email: "",
      locale: "",
      ...this.initialData,
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
