<script lang="ts" setup>
import { reactive, computed } from 'vue'
import { useFetch } from '@vueuse/core'
import ButtonWidget from '@/Components/Dashboard/Widgets/ButtonWidget.vue'


const p = reactive({
  icon: 'fa-hdd-o',
  title: 'free_disk_space',
  listingUrl: '/show/listing/disk_report/disk',
  thresholds: [
    {
      classes: 'btn-danger',
      label: '< 5GB',
      link: '/show/listing/disk_report/disk#freespace < 5GB'
    },
    {
      classes: 'btn-warning',
      label: '< 10GB',
      link: '/show/listing/disk_report/disk#5GB freespace 10GB'
    },
    {
      classes: 'btn-success',
      label: '10GB+',
      link: '/show/listing/disk_report/disk#freespace > 10GB'
    }
  ]
})

const { isFetching, error, data } = useFetch(`/module/disk_report/get_stats`).get().json()
const values = computed(() => {
  if (!data.value || isFetching) return;
  let v = [];
  for (let k in data.value.stats) {
    if (!data.value.stats.hasOwnProperty(k)) continue;
    v.push(data.value.stats[k]);
  }

  return v
});

</script>

<template>
  <div class="DiskReportWidget h-100">
    <ButtonWidget :icon="p.icon" :title="p.title" :listing-url="p.listingUrl" :thresholds="p.thresholds" :values="values"/>
  </div>
</template>
