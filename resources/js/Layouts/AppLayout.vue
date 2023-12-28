<script setup lang="ts">
import { ref, defineProps, withDefaults } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'

export interface Props {
  title?: string;
  theme?: string;
}

const page = usePage()

const props = withDefaults(defineProps<Props>(), {
  title: 'MunkiReport 6.0',
  theme: 'Default',
})


</script>

<template>
  <Head>
    <title>{{ title }}</title>
    <link
      id="bootstrap-stylesheet"
      rel="stylesheet"
      :href="`/assets/themes/${theme}/bootstrap.min.css`"
    >
  </Head>
  <Navbar />
  <main style="padding-top: 56px;">
    <slot />
  </main>
  <Footer />
</template>
