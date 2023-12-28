<script setup lang="ts">
import { defineProps, withDefaults, useSlots } from 'vue';

export interface Props {
  rounded?: boolean;
  shadow?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  rounded: false,
  boolean: false,
})

const slots = useSlots();

</script>

<template>
  <div
    class="card"
    :class="{ rounded, shadow }"
  >
    <div
      v-if="slots.header"
      class="card-header"
    >
      <slot name="header" />
    </div>
    <div class="card-body">
      <slot />
    </div>
    <div
      v-if="slots.footer"
      class="card-footer"
    >
      <slot name="footer" />
    </div>
  </div>
</template>
