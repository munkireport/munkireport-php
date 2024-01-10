<script lang="ts" setup>
import {ref, defineComponent, onMounted, computed} from 'vue';
import CodeMirror from 'vue-codemirror6';
import { bracketMatching, syntaxHighlighting } from '@codemirror/language';
import { autocompletion, closeBrackets } from '@codemirror/autocomplete';
import { graphql, updateSchema } from 'cm6-graphql';
import { oneDarkTheme, oneDark, oneDarkHighlightStyle } from '@codemirror/theme-one-dark';
// import { Bootstrap } from 'codemirror6-bootstrap-theme';
// import { Bootstrap, bootstrapHighlightStyle } from './codemirror6-bootstrap-theme'
import {gql, useQuery} from "@urql/vue";
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-bs4';

DataTable.use(DataTablesCore);


let first = ref(10);
let pageNumber = ref(1);

const value = ref(`
  query {
    reportData {
      data {
        serial_number
      }
    }
  }
`);

const result = useQuery({
  query: value,
})
const columns = () => {
  let cols = []

  for (let tbl of result.data) {
    for (let prop of result.data[tbl]) {
      cols.push({
        key: prop,
        qualified_key: `${tbl}.${prop}`
      })
    }
  }
}
const fetching = computed(() => result.fetching);
const data = computed(() => {
  return result.data?.reportData?.data
});
const error = computed(() => result.error);


</script>

<template>
  <div>

  <code-mirror
      v-model="value"
      tab
      gutter
      :extensions="[bracketMatching(), autocompletion(), closeBrackets(), graphql(), oneDark, syntaxHighlighting(oneDarkHighlightStyle)]"
  />
    <div v-if="fetching">Fetching...{{fetching}}</div>
    <div v-else-if="error">{{error}}</div>
    <DataTable :data="data" :columns="columns" class="display" />

    <div>{{ JSON.stringify(result.data.value) }}</div>
  </div>
</template>
