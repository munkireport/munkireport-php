<script lang="ts" setup>
import { defineProps, withDefaults} from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { library } from '@fortawesome/fontawesome-svg-core'
import {faList} from "@fortawesome/free-solid-svg-icons";

library.add(faList)

export interface Props {
  error?: boolean;
  loading?: boolean;
  editing?: boolean;
  title: string;
  icon?: string;
  link?: string;
}

const props = withDefaults(defineProps<Props>(), {
  error: false,
  loading: false,
  editing: false,
  icon: null,
  link: null,
})

</script>

<template>
  <div class="widget card h-100 shadow-sm">
    <slot name="header">
      <div
        class="card-header"
        :title="props.title"
      >
        <FontAwesomeIcon
          v-if="props.icon"
          :icon="props.icon"
        /> &nbsp;
        <span>{{ props.title }}</span>
        <a
          v-if="props.link"
          :href="props.link"
          class="float-right text-reset"
        ><FontAwesomeIcon :icon="['fas', 'list']" /></a>
      </div>
    </slot>
    <slot />
    <slot name="footer" />
  </div>
</template>
