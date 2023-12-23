<script lang="ts" setup>
import {ref, defineProps, withDefaults} from 'vue'
import {Link, usePage} from '@inertiajs/vue3'
import {useTranslation} from 'i18next-vue'

export interface Props<TItem> {
  items: TItem[];
  key: any;

  flush: boolean;
}

const props = withDefaults(defineProps<Props<any>>(), {
  flush: false,
})

</script>

<template>
  <ul class="list-group" :class="{ 'list-group-flush': props.flush }">
    <li class="list-group-item"
        v-for="item in items"
        :key="props.key"
        :class="{ 'active': item.active, 'disabled': item.disabled }"
    />
  </ul>
</template>

