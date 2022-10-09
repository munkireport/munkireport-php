<template>
  <ListDetailView>
    <template v-slot:list>
      <BusinessUnitsListView
          :items="businessUnits.data"
          :loading="$apollo.queries.businessUnits.loading"
          @create="createBusinessUnit"
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
        <template v-slot:empty>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">There are no business units.</li>
          </ul>
        </template>
      </BusinessUnitsListView>
    </template>
    <template v-slot:detail>
      <div class="card-body">
        <BusinessUnit
            v-if="businessUnit"
            v-bind="businessUnit"
            :loading="$apollo.loading"
            @destroy="destroyBusinessUnit"
        ></BusinessUnit>
      </div>
    </template>
  </ListDetailView>
</template>

<script>
import gql from 'graphql-tag';
import BusinessUnit from './BusinessUnit.vue';
import ListDetailView from '../components/ListDetailView.vue';
import BusinessUnitsListView from './BusinessUnitsListView.vue';

import CREATE_BUSINESS_UNIT from './CreateBusinessUnit.graphql';
import DESTROY_BUSINESS_UNIT from './DestroyBusinessUnit.graphql';
import READ_BUSINESS_UNIT from './BusinessUnit.graphql';
import READ_BUSINESS_UNITS from './BusinessUnits.graphql';

export default {
  name: "BusinessUnits",
  components: {
    BusinessUnit,
    ListDetailView,
    BusinessUnitsListView,
  },
  data() {
    return {
      name: "",
      businessUnit: null,
      businessUnits: [],
    }
  },
  methods: {
    createBusinessUnit({ name }) {
      this.$apollo.mutate({
        mutation: CREATE_BUSINESS_UNIT,
        variables: {
          name
        },
        update: (cache, { data: { createBusinessUnit } }) => {
          const { businessUnits } = cache.readQuery({
            query: READ_BUSINESS_UNITS
          });

          const clone = businessUnits.data.slice();
          clone.push({
            ...createBusinessUnit
          });

          cache.writeQuery({
            query: READ_BUSINESS_UNITS,
            data: { businessUnits: { ...businessUnits, data: clone } },
          })
        }
      }).then((data) => {
        console.log(data);
      }).catch((err) => {
        console.error(err);
      });
    },
    destroyBusinessUnit({ id }) {
      this.$apollo.mutate({
        mutation: DESTROY_BUSINESS_UNIT,
        variables: { id },
        update: (cache, { data: { destroyBusinessUnit } }) => {
          const { businessUnits } = cache.readQuery({
            query: READ_BUSINESS_UNITS
          });

          const clone = businessUnits.data.filter(bu => bu.id !== destroyBusinessUnit.id);

          cache.writeQuery({
            query: READ_BUSINESS_UNITS,
            data: { businessUnits: { ...businessUnits, data: clone } },
          })
        }
      }).then((data) => {
        console.log(data);
      }).catch((err) => {
        console.error(err);
      });
    }
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
