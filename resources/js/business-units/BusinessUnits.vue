<template>
  <ListDetailView>
    <template v-slot:list>
      <ListView
          :items="businessUnits.data"
      >
        <template v-slot:item="slotProps">
          <router-link
              :to="'/business_units/' + slotProps.item.id"
              class="list-group-item list-group-item-action"
              v-bind:class="{ active: slotProps.item.id === $route.params.id }"
          >
            <div class="d-flex w-100 justify-content-between">
              <h5 class="mb-1" v-text="slotProps.item.name"></h5>
            </div>
            <p v-if="slotProps.item.address" class="mb-1 font-italic" v-text="slotProps.item.address"></p>
            <p v-else class="mb-1 font-italic">No address provided.</p>

            <p v-if="slotProps.item.link" class="mb-1 font-italic" v-text="slotProps.item.link"></p>
            <p v-else class="mb-1 font-italic">No link provided.</p>
          </router-link>
        </template>
      </ListView>
    </template>
    <template v-slot:detail>
      <div class="card-header">
        Business Unit
      </div>
      <div class="card-body">
        <BusinessUnit v-if="businessUnit" v-bind="businessUnit" :loading="$apollo.loading"></BusinessUnit>
      </div>
    </template>
  </ListDetailView>
</template>

<script>
import gql from 'graphql-tag';
import BusinessUnit from './BusinessUnit';
import ListDetailView from '../components/ListDetailView';
import ListView from '../components/ListView';

import CREATE_BUSINESS_UNIT from './CreateBusinessUnit.graphql';
import READ_BUSINESS_UNIT from './BusinessUnit.graphql';
import READ_BUSINESS_UNITS from './BusinessUnits.graphql';

export default {
  name: "BusinessUnits",
  components: {
    BusinessUnit,
    ListDetailView,
    ListView,
  },
  data() {
    return {
      name: "",
      businessUnit: null,
      businessUnits: [],
    }
  },
  methods: {
    createBusinessUnit() {
      this.$apollo.mutate({
        mutation: CREATE_BUSINESS_UNIT,
        variables: {
          name: this.name,
        },
        update: (store, { data: { createBusinessUnit } }) => {
          console.log(store);
          const { businessUnits } = store.readQuery({
            query: READ_BUSINESS_UNITS
          });
        }
      }).then((data) => {
        console.log(data);
      }).catch((err) => {
        console.error(err);
      });
    },
  },
  apollo: {
    businessUnit: {
      query: READ_BUSINESS_UNIT,
      variables() {
        return {
          id: this.$route.params.id
        }
      }
    },
    businessUnits: {
      query: READ_BUSINESS_UNITS,
    }
  },
}
</script>

<style scoped>

</style>
