<script setup lang="ts">
import { ref } from 'vue'

const open = ref(false)


</script>

<template>

  <button
      type="button"
      class="btn btn-primary"
      @click="open = true"
  >
    Launch demo modal
  </button>

  <Teleport to="body">
    <div v-if="open" class="vmodal">
      <slot />
    </div>
  </Teleport>
</template>

<style scoped>
.vmodal {
  position: fixed;
  z-index: 999;
  top: 20%;
  left: 50%;
  width: 300px;
  margin-left: -150px;
}
</style>

