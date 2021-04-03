<template>
  <div class="uiv container">
    <div class="row">
      <div class="col-lg-12">
        <h1>Profile</h1>

        <form v-if="user">
          <div class="form-group">
            <label for="username">Username</label>
            <input readonly type="text" class="form-control" id="username" :value="user.data.name" />
          </div>
          <div class="form-group">
            <label for="displayName">Display Name</label>
            <input readonly type="text" class="form-control" id="displayName" :value="user.data.display_name" />
          </div>
          <div class="form-group">
            <label for="email">E-mail</label>
            <input readonly type="text" class="form-control" id="email" :value="user.data.email" />
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">

        <form id="add-contact-method" class="form-inline">
          <p class="lead">Add a contact method</p>
          <dropdown ref="channel" v-model="showChannel">
            <btn type="primary" class="dropdown-toggle">Type <span class="caret"></span></btn>
            <template slot="dropdown">
              <li><a role="button"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> E-mail</a></li>
              <li><a role="button"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span> SMS</a></li>
              <li><a role="button"><span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span> Webhook</a></li>
              <li><a role="button"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> Telegram</a></li>
            </template>
          </dropdown>
          <div class="form-group">
            <label for="address">Address</label>
            <input type="text" class="form-control" id="address" placeholder="" />
          </div>

          <button type="submit" class="btn btn-primary">Add</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { Tabs, Dropdown } from 'uiv';
import 'whatwg-fetch';

export default {
  name: "Profile",
  components: {
    Tabs,
    Dropdown
  },
  data() {
    return {
      error: null,
      user: null,
      showChannel: false,
    };
  },
  mounted() {
    fetch('/api/v6/me')
      .then((response) => {
        return response.json();
      })
      .then((data) => {
        this.user = data;
      })
      .catch((e) => {
        this.error = e.message;
      });
  }
}
</script>

<style scoped>

</style>
