<template>
  <div v-if="loading" class="spinner-border" role="status">
    <span class="sr-only">Loading...</span>
  </div>
  <div v-else class="detail-view">
    <form>
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

    <hr />

    <h4>Users</h4>
    <UserRoleAssignmentInput v-on:selected="addUser" />
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

export default {
  name: "BusinessUnit",
  props: ['id', 'name', 'address', 'link', 'loading', 'users', 'machineGroups'],
  components: {
    UserRoleAssignmentInput,
    UsersMiniTable,
  },
  data() {
    return {
    }
  },
  methods: {
    addUser(evt) {
      const userToAdd = { id: evt.id, name: evt.name, email: evt.email };

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
        update: (store, { data: { updateBusinessUnitRelationships } }) => {
          const { businessUnit } = store.readQuery({
            query: gql`query BusinessUnit ($id: ID!) {
              businessUnit(id: $id) {
                  id
                  name
                  address
                  link
                  users {
                    id
                    role
                    name
                    email
                  }
                  machineGroups {
                     id
                     name
                  }
              }
            }`,
            variables: { id: this.id }
          });

          const businessUnitUsersCopy = businessUnit.users.slice();
          businessUnitUsersCopy.push({
            id: updateBusinessUnitRelationships.users[0].id,
            role: updateBusinessUnitRelationships.users[0].role,
          });
          businessUnit.users = businessUnitUsersCopy;

          store.writeQuery({ query: gql`query BusinessUnit ($id: ID!) {
              businessUnit(id: $id) {
                  id
                  name
                  address
                  link
                  users {
                    id
                    role
                    name
                    email
                  }
                  machineGroups {
                     id
                     name
                  }
              }
            }`,
            variables: { id: this.id }
          }, { businessUnit })
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
        }
      }).then((data) => {
        console.log(data);
      }).catch((err) => {
        console.error(err);
      })
    }
  }
}
</script>

<style scoped>

</style>
