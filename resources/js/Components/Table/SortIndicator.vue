<script lang="ts" setup>
import {defineProps} from 'vue'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
import {
  faChevronUp,
  faChevronDown,
  faCircle,
} from '@fortawesome/free-solid-svg-icons'

export interface Props {
  sort?: 'ASC' | 'DESC' | null;
}

const props = defineProps<Props>()

</script>

<template>
  <FontAwesomeIcon
    v-if="props.sort === 'ASC'"
    :icon="faChevronUp"
  />
  <FontAwesomeIcon
    v-else-if="props.sort === 'DESC'"
    :icon="faChevronDown"
  />
</template>

