<template>
  <div class="EventsWidget h-100">
    <ScrollBoxWidget :icon="icon" :title="$t('events:event_plural')" :listing-url="listingUrl">
      <a v-if="events" v-for="item in events.data"
         :key="item.module + '-' + item.timestamp"
         class="list-group-item"
         :href="'/clients/detail/' + item.serial_number + '#' + item.tab">

        <span class="pull-right" style="padding-left: 10px">{{ item.timestamp }}</span>
        <i :class="['text-' + item.type,'fa', 'fa-times-circle']"></i>
        <span v-text="item.machine.computer_name"></span>
        <span class="d-sm-none d-md-inline"> | </span>
        <br class="d-none d-sm-block d-md-none">
        <span v-wait-for-t>{{ item.module }} {{ $t(item.msg) }}</span>
      </a>
    </ScrollBoxWidget>
  </div>
</template>

<script>
import 'whatwg-fetch';
import gql from 'graphql-tag';
import ScrollBoxWidget from './ScrollBoxWidget';
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
  }
}
</script>

<style scoped>

</style>
