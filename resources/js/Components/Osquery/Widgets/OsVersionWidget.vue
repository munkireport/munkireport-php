<script lang="ts" setup>
import {ref, defineProps} from 'vue'
import {Link, usePage} from '@inertiajs/vue3'
import {useTranslation} from 'i18next-vue'
const { t, i18next } = useTranslation();

export interface OsqueryOsVersion {
  name: string;
  version: string;
  build: string;
  arch: string;
}

const props = defineProps<OsqueryOsVersion>()

</script>

<template>
  <div class="card">
    <div class="card-header">{{ t('os.os') }}</div>
    <div class="card-body">
      <dl>
        <dt>{{ t('os.version') }}</dt>
        <dd>{{ props.name }}  {{ props.version }}</dd>
        <dt>{{ t('buildversion') }}</dt>
        <dd>{{ props.build }}</dd>
        <dt>{{ t('cpu.arch') }}</dt>
        <dd>{{ props.arch }}</dd>
      </dl>
    </div>
  </div>
</template>

