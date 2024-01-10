<script lang="ts" setup>
import {ref, defineProps} from 'vue'
import { onClickOutside } from '@vueuse/core'

export interface Props {
  label?: string;

  // Menu is right-aligned
  right?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  label: 'Open',
  right: false,
})

let open = ref(false);
const target = ref(null);

onClickOutside(target, event => open.value = false)
const toggle = () => open.value = ! open.value;

const hide = () => {
  console.log('click outside')
  open.value = false
}

</script>

<template>
  <div
    ref="target"
    class="dropdown"
  >
    <slot
      name="toggle"
      :toggle="toggle"
    >
      <button
        class="btn btn-secondary dropdown-toggle"
        type="button"
        data-toggle="dropdown"
        aria-expanded="false"
        @click="open = ! open"
      >
        {{ label }}
      </button>
    </slot>

    <div
      class="dropdown-menu"
      :class="{ 'show': open, 'dropdown-menu-right': right }"
    >
      <slot />
    </div>
  </div>
</template>

