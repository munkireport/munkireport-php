<script setup>
import SectionTitle from './SectionTitle.vue';
</script>

<template>
    <div>
        <SectionTitle>
            <template #title>
                <slot name="title" />
            </template>
            <template #description>
                <slot name="description" />
            </template>
        </SectionTitle>

        <div class="mt-5">
          <slot name="content" />
        </div>
    </div>
</template>
