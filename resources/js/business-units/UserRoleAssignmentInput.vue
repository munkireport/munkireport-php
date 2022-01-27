<template>
  <form class="form-row">
    <div class="col">
      <div class="input-group">
        <label class="sr-only" for="usernameAutocomplete">Username or E-mail</label>
        <vue-typeahead-bootstrap
            id="usernameAutocomplete"
            class="mr-sm-2"
            v-model="value"
            @hit="$emit('selected', $event)"
            :ie-close-fix="false"
            :data="usersSearch"
            :serializer="item => item.name"
            placeholder="Search name to add"
            @input="suggestUser"
        >
          <template slot="suggestion" slot-scope="{ data, htmlText }">
            <span v-html="htmlText"></span>
            <span class="text-right text-muted" v-text="data.email"></span>
          </template>
        </vue-typeahead-bootstrap>
      </div>
    </div>
  </form>
</template>

<script>
import gql from 'graphql-tag';
import VueTypeaheadBootstrap from "vue-typeahead-bootstrap";
import { debounce } from "lodash";

export default {
  name: "UserRoleAssignmentInput",
  components: {
    'vue-typeahead-bootstrap': VueTypeaheadBootstrap,
  },
  data() {
    return {
      value: '',
      search: '',
      users: [],
      selectedUser: null,
      usersSearch: [],
    }
  },
  methods: {
    suggestUser() {
      this.$emit('value', this.value);

      debounce(function () {
        this.search = this.value;
      }, 400)
    }
  },
  apollo: {
    usersSearch: {
      query: gql`
        query UsersSearch ($name: String!) {
            usersSearch(name: $name) {
                id
                name
                email
            }
        }
      `,
      variables() {
        return {
          name: `%${this.search}%`,
        }
      }
    }
  }
}
</script>

<style scoped>

</style>
