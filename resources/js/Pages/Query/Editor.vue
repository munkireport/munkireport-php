<script lang="ts" setup>
import { ref, defineComponent, onMounted } from 'vue';
import CodeMirror from 'vue-codemirror6';
import { graphql, updateSchema } from 'cm6-graphql';

const value = ref(`
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
`);

onMounted(() => {

})

</script>

<template>
  <code-mirror v-model="value" :extensions="[graphql()]" />
</template>
