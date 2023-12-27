<script lang="ts" setup>
import {ref, defineProps} from 'vue'
import vClickOutside from 'click-outside-vue3'

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

const toggle = () => open.value = ! open.value;

const hide = () => {
  console.log('click outside')
  open.value = false
}

</script>

<template>
  <div class="dropdown" v-click-outside="hide">
    <slot name="toggle" :toggle="toggle">
      <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false" @click="open = ! open">
        {{ label }}
      </button>
    </slot>

    <div class="dropdown-menu" :class="{ 'show': open, 'dropdown-menu-right': right }">
      <slot></slot>
    </div>
  </div>
</template>

