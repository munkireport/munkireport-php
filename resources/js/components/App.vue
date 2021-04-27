<template>
  <div class="container-fluid">
    <Navigation />
    <router-view></router-view>
  </div>
</template>

<script>
import Navigation from './Navigation';
export default {
  name: "App",
  components: {Navigation}
}
</script>

<style scoped>

</style>
