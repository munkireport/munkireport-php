<script lang="ts" setup>
import {ref, defineProps} from 'vue'
import {Link, usePage} from '@inertiajs/vue3'
import {useTranslation} from 'i18next-vue'
import AppLayout from '@/Layouts/AppLayout.vue';
import QueryForm from "../../../Components/Osquery/QueryForm.vue";

</script>

<template>
  <AppLayout>
    <div class="container mt-4">
      <h1>Create new query</h1>
      <QueryForm />
    </div>
  </AppLayout>
</template>

