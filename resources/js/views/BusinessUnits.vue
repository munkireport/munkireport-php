<template>
  <div>
    <div class="row">
      <div class="col-lg-12">
        <form class="form-inline">
          <div class="form-group">
            <input type="text" class="form-control" id="businessUnitName" placeholder="Business Unit Name" v-model="name" />
          </div>
          <button class="btn btn-primary" @click.prevent="createBusinessUnit">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create
          </button>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <business-unit v-for="unit in businessUnits" :key="unit.id" :name="unit.name" />
      </div>
    </div>
  </div>
</template>

<script>
import gql from 'graphql-tag';
import BusinessUnit from '../components/BusinessUnit.vue';

export default {
  name: "BusinessUnits",
  components: {
    'business-unit': BusinessUnit,
  },
  data() {
    return {
      name: ""
    }
  },
  methods: {
    createBusinessUnit() {
      this.$apollo.mutate({
        mutation: gql`mutation($name: String!) {
          createBusinessUnit(name: $name) {
            id
            name
          }
        }`,
        variables: {
          name: this.name,
        }
      }).then((data) => {
        console.log(data);
      });
    },
  },
  apollo: {
    businessUnits: gql`query {
        businessUnits {
          id
          name
        }
    }`,
  },
}
</script>

<style scoped>

</style>
