<script lang="ts" setup>

  import ScrollBoxWidget from "@/Components/Dashboard/Widgets/ScrollBoxWidget.vue";
  import {computed} from "vue";
  import {fromUnixTime, formatDistanceToNow} from "date-fns";
  import {useTranslation} from 'i18next-vue'
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
  import { library } from '@fortawesome/fontawesome-svg-core'
  import { faTimesCircle, faInfoCircle, faExclamationTriangle, faBullhorn } from '@fortawesome/free-solid-svg-icons'


  library.add(faTimesCircle, faInfoCircle, faExclamationTriangle, faBullhorn)

  const title = ""
  const icon = "fa-bullhorn"
  const listingUrl = "/show/listing/event/event"
  // const error = ""

  const message = (item) => {
    const data = item.data || '{}';
    return t(item.msg, JSON.parse(data))
    // return this.$t(item.msg, JSON.parse(data));
  }

  const timestamp = (item) => {
    const date = fromUnixTime(item);
    const d = formatDistanceToNow(date);
    return d;
  }

  const tab = (item) => {
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
  }

  const formatEventData = (item) => {
      item.tab = '#tab_summary';

      if(item.module == 'munkireport' || item.module == 'managedinstalls'){
        item.tab = '#tab_munki';
        item.module = '';
        item.data = item.data || '{}';
        item.msg = t(item.msg, JSON.parse(item.data));
      }
      else if(item.module == 'diskreport'){
        item.tab = '#tab_storage-tab';
        item.module = '';
        item.data = item.data || '{}';
        item.msg = t(item.msg, JSON.parse(item.data));
      }
      else if(item.module == 'reportdata'){
        item.msg = t(item.msg);
      }
      else if(item.module == 'certificate'){
        item.tab = '#tab_certificate-tab';
        item.module = '';
        item.data = item.data || '{}';
        var parsedData = JSON.parse(item.data);
        // Convert unix timestamp to relative time
        parsedData.moment = moment(parsedData.timestamp * 1000).fromNow();
        // console.log(parsedData)
        item.msg = t(item.msg, parsedData);
      }

      return item;
  }


  import { gql, useQuery } from '@urql/vue';

  const { fetching, data, error } = useQuery({
    query: gql`query {
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
  })

  const { t, i18next } = useTranslation();
</script>

<template>
    <ScrollBoxWidget :icon="icon" :title="t('event_plural')" :listing-url="listingUrl">
      <template v-if="data?.events?.data">
        <a
          v-for="item in data.events.data"
          :key="item.module + '-' + item.timestamp"
          class="list-group-item"
          :href="'/clients/detail/' + item.serial_number + tab(item)"
        >

          <span class="float-right" style="padding-left: 10px">{{ timestamp(item.timestamp) }}</span>
          <FontAwesomeIcon v-if="item.type === 'warning'" :icon="['fa', 'exclamation-triangle']" class="text-warning"></FontAwesomeIcon>
          <FontAwesomeIcon v-else-if="item.type === 'info'" :icon="['fa', 'info-circle']" class="text-info"></FontAwesomeIcon>
          <FontAwesomeIcon v-else :icon="['fa', 'times-circle']" class="text-danger"></FontAwesomeIcon>
          &nbsp;
          <span v-text="item.machine.computer_name"></span>
          <span class="d-sm-none d-md-inline"> | </span>
          <br class="d-none d-sm-block d-md-none">
          <span>{{ item.module }} {{ message(item) }}</span>
        </a>
      </template>
    </ScrollBoxWidget>
</template>
