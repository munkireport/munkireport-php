<script setup>
import { computed, useSlots } from 'vue';
import SectionTitle from './SectionTitle.vue';

defineEmits(['submitted']);

const hasActions = computed(() => !! useSlots().actions);
</script>

<template>
    <div class="row">
      <div class="col-sm">
        <SectionTitle>
            <template #title>
                <slot name="title" />
            </template>
            <template #description>
                <slot name="description" />
            </template>
        </SectionTitle>

        <form @submit.prevent="$emit('submitted')">
          <div class="pt-1">
            <slot name="form" />
          </div>
          <div class="pt-1">
            <slot name="actions" />
          </div>
        </form>
      </div>
    </div>
</template>
