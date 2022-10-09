<template>
    <ScrollBoxWidget :icon="icon" :title="$t('events:event_plural')" :listing-url="listingUrl">
      <a v-if="events" v-for="item in events.data"
         :key="item.module + '-' + item.timestamp"
         class="list-group-item"
         :href="'/clients/detail/' + item.serial_number + tab(item)">

        <span class="pull-right" style="padding-left: 10px">{{ timestamp(item.timestamp) }}</span>
        <i :class="['text-' + item.type,'fa', 'fa-times-circle']"></i>
        <span v-text="item.machine.computer_name"></span>
        <span class="d-sm-none d-md-inline"> | </span>
        <br class="d-none d-sm-block d-md-none">
        <span v-wait-for-t>{{ item.module }} {{ message(item) }}</span>
      </a>
    </ScrollBoxWidget>
</template>

<script>
import 'whatwg-fetch';
import gql from 'graphql-tag';
import ScrollBoxWidget from './ScrollBoxWidget.vue';
import { fromUnixTime } from 'date-fns';

export default {
  name: "EventsWidget",
  components: {
    ScrollBoxWidget,
  },
  apollo: {
    events: gql`query {
      events {
        data {
          id
          serial_number
          msg
          module
          timestamp
          data
          type

          machine {
            computer_name
          }
        }
      }
}`
  },
  data() {
    return {
      title: "",
      icon: "fa-bullhorn",
      listingUrl: "/show/listing/event/event",
      error: "",
    }
  },
  methods: {
    tab: item => {
      switch (item.module) {
        case 'munkireport':
        case 'managedinstalls':
          return '#tab_munki';
        case 'diskreport':
          return '#tab_storage-tab';
        case 'certificate':
          return '#tab_certificate-tab';
        default:
          return '#tab_summary';
      }
    },

    message: function(item) {
      const data = item.data || '{}';
      console.log(data);
      return this.$t(item.msg, JSON.parse(data));
    },

    timestamp: function(item) {
      if (!item.data) return;
      const data = JSON.parse(item.data);
      const date = fromUnixTime(data.timestamp);
      return date;
    },

    formatEventData: item => {

      item.tab = '#tab_summary';

      if(item.module == 'munkireport' || item.module == 'managedinstalls'){
        item.tab = '#tab_munki';
        item.module = '';
        item.data = item.data || '{}';
        item.msg = this.$t(item.msg, JSON.parse(item.data));
      }
      else if(item.module == 'diskreport'){
        item.tab = '#tab_storage-tab';
        item.module = '';
        item.data = item.data || '{}';
        item.msg = this.$t(item.msg, JSON.parse(item.data));
      }
      else if(item.module == 'reportdata'){
        item.msg = this.$t(item.msg);
      }
      else if(item.module == 'certificate'){
        item.tab = '#tab_certificate-tab';
        item.module = '';
        item.data = item.data || '{}';
        var parsedData = JSON.parse(item.data);
        // Convert unix timestamp to relative time
        parsedData.moment = moment(parsedData.timestamp * 1000).fromNow();
        // console.log(parsedData)
        item.msg = this.$t(item.msg, parsedData);
      }

      return item;
    },
  }
}
</script>

<style scoped>

</style>
