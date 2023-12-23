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
  <div class="valid-feedback" role="alert" v-show="on">
    <slot />
  </div>
</template>
