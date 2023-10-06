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
      </div>
      <div class="col-sm">
        <form @submit.prevent="$emit('submitted')">
            <div
                class="px-4 py-5 bg-white sm:p-6 shadow"
                :class="hasActions ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md'"
            >
                <div class="grid grid-cols-6 gap-6">
                    <slot name="form" />
                </div>
            </div>

            <div v-if="hasActions" class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                <slot name="actions" />
            </div>
        </form>
      </div>
    </div>
</template>
