<script lang="ts" setup>
import { ref, computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue';
import {gql, useQuery} from '@urql/vue';
import Editor from "./Editor.vue";


</script>

<template>
  <AppLayout>
    <div class="container-fluid">
      <Editor />
    </div>
  </AppLayout>
</template>
