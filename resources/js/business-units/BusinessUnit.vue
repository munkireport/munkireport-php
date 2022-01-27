<template>
  <div v-if="loading" class="spinner-border" role="status">
    <span class="sr-only">Loading...</span>
  </div>
  <div v-else class="detail-view">
    <button class="btn btn-danger mb-4" @click.prevent="$emit('destroy', { id })">Delete</button>

    <form @submit.prevent="save">
      <div class="form-group">
        <label for="nameInput">Business Unit Name</label>
        <input type="text" class="form-control" id="nameInput" name="name" aria-describedBy="nameInput" :value="name" />
      </div>

      <div class="form-group">
        <label for="addressInput">Address</label>
        <input type="text" class="form-control" id="addressInput" name="address" aria-describedBy="addressInput" :value="address" />
      </div>

      <div class="form-group">
        <label for="linkInput">Link</label>
        <input type="text" class="form-control" id="linkInput" name="link" aria-describedBy="linkInput" :value="link" />
      </div>

      <button type="submit" class="btn btn-primary">Save</button>
    </form>

    <hr class="invisible" />

    <h4>Users</h4>
    <UserRoleAssignmentInput v-on:selected="addUser" v-model="searchUser" />
    <hr class="invisible" />
    <UsersMiniTable :users="users" v-on:removed="removeUser" />


    <hr />
    <h4>Machine Groups</h4>

    <ul>
      <li v-for="machineGroup in machineGroups">
        machineGroup
      </li>
    </ul>

  </div>
</template>

<script>
import gql from 'graphql-tag';
import UserRoleAssignmentInput from "./UserRoleAssignmentInput";
import UsersMiniTable from "./UsersMiniTable";
import UPDATE_BUSINESS_UNIT_RELATIONSHIPS from './UpdateBusinessUnitRelationships.graphql';
import READ_BUSINESS_UNIT from './BusinessUnit.graphql';

export default {
  name: "BusinessUnit",
  props: ['id', 'name', 'address', 'link', 'loading', 'users', 'machineGroups'],
  components: {
    UserRoleAssignmentInput,
    UsersMiniTable,
  },
  data() {
    return {
      searchUser: "",
    }
  },
  methods: {
    addUser(evt) {
      const userToAdd = { id: evt.id, name: evt.name, email: evt.email };
      this.searchUser = "";

      this.$apollo.mutate({
        mutation: UPDATE_BUSINESS_UNIT_RELATIONSHIPS,
        variables: {
          input: {
            id: this.id,
            users: {
              connect: [{
                id: evt.id,
                role: 'user',
              }]
            },
          }
        },
        update: (cache, { data: { updateBusinessUnitRelationships } }) => {
          // data = result of mutation
          const { businessUnit } = cache.readQuery({
            query: READ_BUSINESS_UNIT,
            variables: { id: this.id }
          });

          const clone = {
            ...businessUnit,
            users: updateBusinessUnitRelationships.users.slice(),
          };

          cache.writeQuery({
            query: READ_BUSINESS_UNIT,
            variables: { id: this.id },
            data: { businessUnit: clone },
          });
        }
      }).then((data) => {
        console.log(data);
      }).catch((err) => {
        console.error(err);
      });
    },
    removeUser(user) {
      this.$apollo.mutate({
        mutation: UPDATE_BUSINESS_UNIT_RELATIONSHIPS,
        variables: {
          input: {
            id: this.id,
            users: {
              disconnect: [user.id]
            },
          }
        },
        update: (cache, { data: { updateBusinessUnitRelationships } }) => {
          // data = result of mutation
          const { businessUnit } = cache.readQuery({
            query: READ_BUSINESS_UNIT,
            variables: { id: this.id }
          });

          const clone = {
            ...businessUnit,
            users: updateBusinessUnitRelationships.users.slice(),
          };

          cache.writeQuery({
            query: READ_BUSINESS_UNIT,
            variables: { id: this.id },
            data: { businessUnit: clone },
          });
        }
      }).then((data) => {
        console.log(data);
      }).catch((err) => {
        console.error(err);
      })
    },
    save(evt) {
      console.dir(evt);
      this.$apollo.mutate({

      })
    }
  }
}
</script>

<style scoped>

</style>
