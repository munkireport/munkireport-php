<template>
  <form class="form-row">
    <div class="col">
      <div class="input-group">
        <label class="sr-only" for="machineGroupAutocomplete">Machine group name</label>
        <vue-typeahead-bootstrap
            id="machineGroupAutocomplete"
            class="mr-sm-2"
            v-model="value"
            @hit="$emit('selected', $event)"
            :ie-close-fix="false"
            :data="machineGroupsSearch"
            :serializer="item => item.name"
            placeholder="Search group to add"
            @input="suggestUser"
        >
          <template slot="suggestion" slot-scope="{ data, htmlText }">
            <span v-html="htmlText"></span>
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
  name: "MachineGroupAssignmentInput",
  components: {
    'vue-typeahead-bootstrap': VueTypeaheadBootstrap,
  },
  data() {
    return {
      value: '',
      search: '',
      machineGroups: [],
      selectedMachineGroup: null,
      machineGroupsSearch: [],
    }
  },
  methods: {
    suggestMachineGroup() {
      this.$emit('value', this.value);

      debounce(function () {
        this.search = this.value;
      }, 400)
    }
  },
  apollo: {
    machineGroupsSearch: {
      query: gql`
        query MachineGroupsSearch ($name: String!) {
            machineGroupsSearch(name: $name) {
                id
                name
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
