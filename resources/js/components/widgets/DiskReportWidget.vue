<template>
  <div class="DiskReportWidget h-100">
    <ThresholdWidget :icon="icon" :title="title" :listing-url="listingUrl" :thresholds="thresholds" :values="values"/>
  </div>
</template>

<script>
import 'whatwg-fetch';
import ThresholdWidget from './ThresholdWidget';

export default {
  name: "DiskReportWidget",
  components: {
    ThresholdWidget,
  },
  data() {
    return {
      icon: 'fa-hdd-o',
      title: 'free_disk_space',
      listingUrl: '/show/listing/disk_report/disk',
      thresholds: [
        {
          classes: 'btn-danger',
          label: '< 5GB'
        },
        {
          classes: 'btn-warning',
          label: '< 10GB',
        },
        {
          classes: 'btn-success',
          label: '10GB+',
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
        for (var k in data.stats) {
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
