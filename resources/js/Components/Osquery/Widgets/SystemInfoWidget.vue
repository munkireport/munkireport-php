<script lang="ts" setup>
import {ref, defineProps} from 'vue'
import {useTranslation} from 'i18next-vue'
const { t, i18next } = useTranslation();

export interface OsquerySystemInfo {
  hostname: string;
  uuid: string;
  cpu_type: string;
  cpu_subtype: string;
  cpu_brand: string;
  cpu_physical_cores: number;
  cpu_logical_cores: number;
  physical_memory: number;
  hardware_vendor: string;
  hardware_model: string;
  hardware_version: string;
  hardware_serial: string;
}

const props = defineProps<OsquerySystemInfo>()

</script>

<template>
  <div class="card">
    <div class="card-header">{{ t('hardware.hardware') }}</div>
    <div class="card-body">
      <dl>
        <dt>{{ t('cpu.cpu') }}</dt>
        <dd>{{ props.cpu_brand }}</dd>
        <dt>{{ t('physical_memory') }}</dt>
        <dd>{{ props.physical_memory }}</dd>
        <dt>{{ t('serial') }}</dt>
        <dd>{{ props.hardware_serial }}</dd>
        <dt>{{ t('model') }}</dt>
        <dd>{{ props.hardware_model }}</dd>
      </dl>
    </div>
  </div>
</template>

