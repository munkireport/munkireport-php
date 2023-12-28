<script setup>
import SectionTitle from './SectionTitle.vue';
import Card from './Card.vue';
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
      <Card>
        <slot name="content" />
        <template #footer>
          <slot name="actions" />
        </template>
      </Card>
    </div>
  </div>
</template>
