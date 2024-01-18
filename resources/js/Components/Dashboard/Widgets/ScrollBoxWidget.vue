<script lang="ts" setup>
  import { defineProps } from 'vue'
  import Widget from '@/Components/Dashboard/Widget.vue'

  export interface Props {
    tooltip?: string;
    icon: string;
    title: string;
    listingUrl: string;
  }

  const props = defineProps<Props>()
</script>

<template>
  <Widget
    :icon="props.icon"
    :title="props.title"
    :link="props.listingUrl"
  >
    <div class="list-group scroll-box" style="max-height: 308px; overflow-y: scroll">
      <slot />
    </div>
  </Widget>
</template>
