<script setup lang="ts">
import { defineProps, withDefaults } from "vue"

export interface Props {
  on?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  on: false,
})

</script>

<template>
  <span
    v-show="on"
    class="text-success"
    role="alert"
  >
    <slot />
  </span>
</template>
