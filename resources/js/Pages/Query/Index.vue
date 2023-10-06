<script lang="ts" setup>
import { ref, computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue';
import {gql, useQuery} from '@urql/vue';
import Editor from "./Editor.vue";

let first = ref(10);
let pageNumber = ref(1);

const result = useQuery({
  query: gql`
    query($first: Int!, $page: Int) {
        reportData(first: $first, page: $page) {
            data {
                serial_number
                console_user
                long_username
            }
            paginatorInfo {
                total
            }
        }
    }
  `,
  variables: { first, page: pageNumber }
})
const fetching = computed(() => result.fetching);
</script>

<template>
  <AppLayout title="Query">
    <div class="container-fluid">
      <div v-if="fetching">
        Loading...
      </div>
      <Editor />
    </div>
  </AppLayout>
</template>
