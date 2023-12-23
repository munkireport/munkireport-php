<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import UpdateLocaleForm from './Partials/UpdateLocaleForm.vue';
import UpdateThemeForm from './Partials/UpdateThemeForm.vue';

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});
</script>

<template>
    <AppLayout title="Profile">
        <div class="container">
          <div v-if="$page.props.jetstream.canUpdateProfileInformation">
              <UpdateProfileInformationForm :user="$page.props.auth.user" />

              <SectionBorder />
          </div>

          <div v-if="$page.props.jetstream.canUpdatePassword && $page.props.user.source == null">
              <UpdatePasswordForm class="mt-10 sm:mt-0" />

              <SectionBorder />
          </div>
          <div v-else>
            <p>You cannot manage your password from this application because you have signed on using an Identity Provider</p>
          </div>



<!--          <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">-->
<!--              <TwoFactorAuthenticationForm-->
<!--                  :requires-confirmation="confirmsTwoFactorAuthentication"-->
<!--                  class="mt-10 sm:mt-0"-->
<!--              />-->

<!--              <SectionBorder />-->
<!--          </div>-->

          <div>
            <UpdateLocaleForm :user="$page.props.auth.user" class="mt-10" />
            <SectionBorder />
          </div>

          <div>
            <UpdateThemeForm :user="$page.props.auth.user" :themes="$page.props.themes" class="mt-10" />
            <SectionBorder />
          </div>

          <LogoutOtherBrowserSessionsForm :sessions="sessions" class="mt-10 sm:mt-0" />

          <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
              <SectionBorder />
              <DeleteUserForm class="mt-10 sm:mt-0" />
          </template>
        </div>
    </AppLayout>
</template>
