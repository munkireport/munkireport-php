<script setup>
import ApiTokenManager from '@/Pages/API/Partials/ApiTokenManager.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    tokens: Array,
    availablePermissions: Array,
    defaultPermissions: Array,
});
</script>

<template>
    <AppLayout title="API Tokens">
      <div class="container">
        <div>
          <ApiTokenManager
              :tokens="tokens"
              :available-permissions="availablePermissions"
              :default-permissions="defaultPermissions"
          />
        </div>
      </div>
    </AppLayout>
</template>
