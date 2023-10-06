<script setup lang="ts">
import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'

</script>

<template>
  <Navbar />
  <main style="padding-top: 56px;">
    <slot></slot>
  </main>
  <Footer />
</template>
