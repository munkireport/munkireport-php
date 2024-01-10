<script lang="ts" setup>
import {ref, defineProps, inject} from 'vue'
import {Link, usePage} from '@inertiajs/vue3'
import {useTranslation} from 'i18next-vue'
import AppLayout from '@/Layouts/AppLayout.vue';
import Table from '@/Components/Table.vue';
import SearchInput from '@/Components/SearchInput.vue';
import Dropdown from '@/Components/Dropdown.vue'
import ModelIcon from '@/Components/ModelIcon.vue'

const route = inject('route')
import {format} from 'date-fns'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
import {
  faAppleAlt,
  faSearch,
    faTags,
} from '@fortawesome/free-solid-svg-icons'
import {

} from '@fortawesome/free-regular-svg-icons'
import {
  faApple,
  faWindows,
  faLinux,
} from '@fortawesome/free-brands-svg-icons'


export interface Props {
  nodes: object[];
}

const page = usePage<inertia.SharedData>();
const columns = [
  {
    key: "host_identifier",
    label: "Host",
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
      <h3>OSQuery Nodes</h3>

      <div class="row mt-4">
        <div class="col">
          <SearchInput />
        </div>
        <div class="col">
          <Dropdown>
            <template #toggle="slotProps">
              <button
                type="button"
                class="btn btn-secondary dropdown-toggle"
                aria-expanded="false"
                @click.prevent="slotProps.toggle"
              >
                <FontAwesomeIcon :icon="faTags" />
                <span class="pl-2">Tags</span>
              </button>
            </template>

            <form class="px-2 py-1">
              <div class="form-group">
                <SearchInput placeholder="tag name" />
              </div>
            </form>
          </Dropdown>
        </div>
      </div>

      <Table
        class="mt-4"
        hover
        :columns="columns"
        :data="page.props.nodes"
      >
        <template #default="slotProps">
          <span v-if="slotProps.column.key === 'updated_at'">
            {{ format(new Date(slotProps.item[slotProps.column.key]), 'dd.MM.yyyy') }}
          </span>
          <span v-else-if="slotProps.column.key === 'created_at'">
            {{ format(new Date(slotProps.item[slotProps.column.key]), 'dd.MM.yyyy') }}
          </span>
          <span v-else-if="slotProps.column.key === 'host_identifier'">
            <Link :href="route('osquery.nodes.show', slotProps.item['id'])">
              {{ slotProps.item[slotProps.column.key] }}
            </Link>
          </span>
          <span
            v-else
            v-text="slotProps.item[slotProps.column.key]"
          />
        </template>
      </Table>
    </div>
  </AppLayout>
</template>

