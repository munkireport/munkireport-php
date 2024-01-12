<script setup>
import {ref} from 'vue';
import { usePage } from '@inertiajs/vue3'

import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';

const page = usePage()
const props = defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});

const theme = ref(page.auth?.user?.theme ?? 'Default');
const onThemeSelect = (t) => {
  theme.value = t ?? 'Default'
}

</script>

<template>
    <AppLayout title="Profile" :theme="theme">
        <div class="container">
          <div v-if="$page.props.jetstream.canUpdateProfileInformation">
              <UpdateProfileInformationForm :user="$page.props.auth.user" :themes="$page.props.themes" @themeSelect="onThemeSelect" />

              <SectionBorder />
          </div>

          <div v-if="$page.props.jetstream.canUpdatePassword && $page.props.user.source == null">
              <UpdatePasswordForm class="mt-10 sm:mt-0" />

              <SectionBorder />
          </div>
          <div v-else>
            <p>You cannot manage your password from this application because you have signed on using an Identity Provider</p>
          </div>


          <!-- Disabled in MunkiReport v6.0.0, Not available -->
          <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
              <TwoFactorAuthenticationForm
                  :requires-confirmation="confirmsTwoFactorAuthentication"
                  class="mt-10 sm:mt-0"
              />
              <SectionBorder />
          </div>

          <LogoutOtherBrowserSessionsForm :sessions="sessions" class="mt-10 sm:mt-0" />

          <!-- Disabled in MunkiReport v6.0.0, Not available -->
          <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
              <SectionBorder />
              <DeleteUserForm class="mt-10 sm:mt-0" />
          </template>
        </div>
    </AppLayout>
</template>
