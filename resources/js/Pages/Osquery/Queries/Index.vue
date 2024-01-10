<script lang="ts" setup>
import {ref, defineProps, inject} from 'vue'
import {Link, usePage} from '@inertiajs/vue3'
import {useTranslation} from 'i18next-vue'
import AppLayout from '@/Layouts/AppLayout.vue';
import Table from '@/Components/Table.vue';
const route = inject('route')
import { format } from 'date-fns'

export interface Props {
  queries: object[];
}

const page = usePage<inertia.SharedData>();
const columns = [
  {
    key: "id",
    label: "ID"
  },
  {
    key: "platform",
    label: "Platform",
  },
  {
    key: "created_at",
    label: "Enrolled",
    sortable: true,
  },
  {
    key: "updated_at",
    label: "Updated",
    sortable: true,
  }
];
const props = defineProps<Props>();

</script>

<template>
  <AppLayout>
    <div class="container mt-4">
      <h3>OSQuery Queries</h3>

      <Link :href="route('osquery.admin.queries.create')" class="btn btn-primary">New Query</Link>

      <Table
        class="mt-4"
        hover
        :columns="columns"
        :data="page.props.queries"
      >
        <template #default="slotProps">
          <span v-if="slotProps.column.key === 'updated_at'">{{ format(new Date(slotProps.item[slotProps.column.key]), 'dd.MM.yyyy') }}</span>
          <span v-else-if="slotProps.column.key === 'created_at'">{{ format(new Date(slotProps.item[slotProps.column.key]), 'dd.MM.yyyy') }}</span>
          <span
            v-else
            v-text="slotProps.item[slotProps.column.key]"
          />
        </template>
      </Table>
    </div>
  </AppLayout>
</template>

