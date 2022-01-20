<template>
  <div class="DiskReportWidget h-100">
    <ButtonWidget :icon="icon" :title="title" :listing-url="listingUrl" :thresholds="thresholds" :values="values"/>
  </div>
</template>

<script>
import 'whatwg-fetch';
import ButtonWidget from './ButtonWidget';

export default {
  name: "DiskReportWidget",
  components: {
    ButtonWidget,
  },
  data() {
    return {
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
      ],
      values: [],
    }
  },
  mounted() {
    fetch('/module/disk_report/get_stats')
      .then((res) => res.json())
      .then((data) => {
        let values = [];
        for (let k in data.stats) {
          if (!data.stats.hasOwnProperty(k)) continue;
          values.push(data.stats[k]);
        }

        this.values = values;
      });
  },
  methods: {

  }
}
</script>

<style scoped>

</style>
