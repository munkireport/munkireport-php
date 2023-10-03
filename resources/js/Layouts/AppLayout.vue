<script setup lang="ts">
import Navbar from '@/Components/Navbar.vue'

</script>

<template>
  <Navbar />
  <main style="padding-top: 56px;">
    <slot></slot>
  </main>
</template>
