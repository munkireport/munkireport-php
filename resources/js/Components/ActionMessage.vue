<script setup>
defineProps({
    on: Boolean,
});
</script>

<template>
    <div>
      <div class="alert alert-primary" role="alert" v-show="on">
        <slot />
      </div>
    </div>
</template>
