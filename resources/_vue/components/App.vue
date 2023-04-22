<template>
  <div class="container-fluid">
    <Navigation />
    <router-view></router-view>
  </div>
</template>

<script>
import Navigation from './Navigation.vue';
export default {
  name: "App",
  components: {
    Navigation
  }
}
</script>

<style scoped>

</style>
