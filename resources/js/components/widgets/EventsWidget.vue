<template>
  <div class="EventsWidget h-100">
    <ScrollBoxWidget :icon="icon" :title="$t('event:event.event_plural')">
      <a v-for="item in items"
         :key="item.module + '-' + item.timestamp"
         class="list-group-item"
         :href="'/clients/detail/' + item.serial_number + item.tab">

        <span class="pull-right" style="padding-left: 10px">{{ item.timestamp }}</span>
        <i :class="['text-' + item.type,'fa', 'fa-times-circle']"></i>
        <span v-text="item.computer_name"></span>
        <span class="d-sm-none d-md-inline"> | </span>
        <br class="d-none d-sm-block d-md-none">
        <span>{{ item.module }} {{ item.msg }}</span>
      </a>
    </ScrollBoxWidget>
  </div>
</template>

<script>
import 'whatwg-fetch';
import ScrollBoxWidget from './ScrollBoxWidget';

export default {
  name: "EventsWidget",
  components: {
    ScrollBoxWidget,
  },
  data() {
    return {
      title: "",
      icon: "fa-bullhorn",
      listingUrl: "/show/listing/event/event",
      error: "",
      items: [],
    }
  },
  mounted() {
    fetch('/module/event/get/50')
        .then((res) => res.json())
        .then((data) => {
          if (data.error) {
            this.error = data.error;
          } else {
            this.items = data.items;
          }
        });


  }
}
</script>

<style scoped>

</style>
