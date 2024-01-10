<script lang="ts" setup>
import {ref, defineProps} from 'vue'
import {Link, usePage} from '@inertiajs/vue3'
import {useTranslation} from 'i18next-vue'
import AppLayout from '@/Layouts/AppLayout.vue';
import { format } from 'date-fns'

import SystemInfoWidget from '@/Components/Osquery/Widgets/SystemInfoWidget.vue';
import OsVersionWidget from "@/Components/Osquery/Widgets/OsVersionWidget.vue";

interface OSQueryNode {
  host_identifier: string;
  created_at: string;
  updated_at: string;
}

export interface Props {
  node: OSQueryNode;

  user: object;

  system_info: object;
  os_version: object;
}

const props = defineProps<Props>();


</script>

<template>
  <AppLayout>
    <div class="container mt-4">
      <div class="row">
        <div class="col">
          <h3 v-text="props.node.host_identifier"></h3>

          <dl>
            <dt>Enrolled</dt>
            <dd>{{ format(new Date(props.node.created_at), 'dd.MM.yyyy') }}</dd>
          </dl>

        </div>
      </div>
      <div class="row">
        <div class="col">
          <SystemInfoWidget v-bind="props.system_info"/>
        </div>
        <div class="col">
          <OsVersionWidget v-bind="props.os_version"/>
        </div>
      </div>
      <div class="row">
        <div class="col">

        </div>
      </div>
    </div>
  </AppLayout>
</template>

