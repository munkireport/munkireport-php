<script lang="ts" setup>
import {ref, defineProps, computed} from 'vue'
import {Link, usePage} from '@inertiajs/vue3'
import {useTranslation} from 'i18next-vue'
import {
  faDesktop,
  faLaptop,
  faServer,
} from '@fortawesome/free-solid-svg-icons'
import {
  faApple,
} from '@fortawesome/free-brands-svg-icons'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'

export interface Props {
  model: string;
}

const props = defineProps<Props>()

const formFactor = computed(() => {
  if (props.model.startsWith('iMac')) {
    return 'desktop'
  }

  if (props.model.startsWith('MacBook')) {
    return 'laptop'
  }

  return 'unknown'
})

</script>

<template>
  <FontAwesomeIcon v-if="formFactor === 'desktop'" :icon="faDesktop" />
  <FontAwesomeIcon v-else-if="formFactor === 'laptop'" :icon="faLaptop" />
  <FontAwesomeIcon v-else :icon="faDesktop" />
</template>

