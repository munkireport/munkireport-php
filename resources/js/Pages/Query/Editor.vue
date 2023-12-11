<script lang="ts" setup>
import {ref, defineComponent, onMounted, computed} from 'vue';
import CodeMirror from 'vue-codemirror6';
import { bracketMatching, syntaxHighlighting } from '@codemirror/language';
import { autocompletion, closeBrackets } from '@codemirror/autocomplete';
import { graphql, updateSchema } from 'cm6-graphql';
import { oneDarkTheme, oneDark, oneDarkHighlightStyle } from '@codemirror/theme-one-dark';
// import { Bootstrap } from 'codemirror6-bootstrap-theme';
import { Bootstrap, bootstrapHighlightStyle } from './codemirror6-bootstrap-theme'
import {gql, useQuery} from "@urql/vue";
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-bs4';

DataTable.use(DataTablesCore);


let first = ref(10);
let pageNumber = ref(1);

const value = ref();
const q = `
  query() {
    reportData {
      data {
        serial_number
      }
    }
  }
`

const result = useQuery({
  query: q,
})
const columns = [
  { data: 'serial_number' }
]
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
      :extensions="[bracketMatching(), autocompletion(), closeBrackets(), graphql(), oneDark, syntaxHighlighting(oneDarkHighlightStyle)]"
  />
    <div v-if="fetching">Fetching...{{fetching}}</div>
    <div v-else-if="error">{{error}}</div>
    <DataTable :data="data" :columns="columns" class="display" />
  </div>
</template>
