<script setup>
import { computed, useSlots } from 'vue';
import SectionTitle from './SectionTitle.vue';
import Card from '@/Components/Card.vue';

defineEmits(['submitted']);

const hasActions = computed(() => !! useSlots().actions);
</script>

<template>
  <div class="row">
    <div class="col-sm-4">
      <SectionTitle>
        <template #title>
          <slot name="title" />
        </template>
        <template #description>
          <slot name="description" />
        </template>
      </SectionTitle>
    </div>
    <div class="col-sm">
      <form @submit.prevent="$emit('submitted')">
        <Card>
          <slot name="form" />
          <template #footer>
            <slot name="actions" />
          </template>
        </Card>
      </form>
    </div>
  </div>
</template>
