<script setup lang="ts">
import { ref, defineProps } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'

defineProps({
  title: String,
});


</script>

<template>
  <Head :title="title" />
  <Navbar />
  <main style="padding-top: 56px;">
    <slot></slot>
  </main>
  <Footer />
</template>
