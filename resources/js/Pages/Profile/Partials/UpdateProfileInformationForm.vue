<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

import { useTranslation } from "i18next-vue";
const { t, i18next } = useTranslation();

const props = defineProps({
    user: Object,
    themes: Array,
});

const form = useForm({
    _method: 'PUT',
    name: props.user.name,
    email: props.user.email,
    photo: null,
    locale: props.user.locale,
    theme: props.current_theme,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);
const selectLocale = ref(null);
const themeSelect = ref(null);

const emit = defineEmits(['themeSelect']);

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => {
          clearPhotoFileInput()
          i18next.changeLanguage(props.user.locale)

        }
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (! photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};

const updateTheme = () => {
  emit('themeSelect', form.theme)
}
</script>

<template>
    <FormSection @submitted="updateProfileInformation">
        <template #title>
            Profile Information
        </template>

        <template #description>
            Update your account's profile information and email address.
        </template>

        <template #form>
          <!-- Removed from vendor template: profile photo -->

            <!-- Name -->
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" id="name" v-model="form.name"
                     :class="{ 'is-invalid': !!form.errors.name }"
              />
              <div v-text="form.errors.name" class="invalid-feedback" />
            </div>

            <!-- Email -->
            <!-- Removed from vendor template: e-mail verification -->
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" v-model="form.email"
                     :class="{ 'is-invalid': !!form.errors.email }"
              />
              <div v-text="form.errors.email" class="invalid-feedback" />
            </div>

            <!-- Locale -->
            <div class="form-group">
              <label for="localeSelection">Locale</label>
              <select class="form-control" id="localeSelection" ref="selectLocale" v-model="form.locale"
                      :class="{ 'is-invalid': !!form.errors.locale }"
              >
                <option value="en_US">English (US)</option>
                <option value="de_DE">Deutsch (DE)</option>
                <option value="es_ES">Español (Spain)</option>
                <option value="fr_FR">Français (France)</option>
                <option value="nl_NL">Nederlands</option>
              </select>
              <div v-text="form.errors.locale" class="invalid-feedback" />
            </div>

          <!-- Theme -->
          <div class="form-group">
            <label for="themeSelection">Theme</label>
            <select class="form-control" id="themeSelection" ref="themeSelect" v-model="form.theme" @change="updateTheme"
                    :class="{ 'is-invalid': !!form.errors.theme }"
            >
              <option value="">(None) System Default</option>
              <option v-for="theme in themes" :key="theme" :value="theme">{{ theme }}</option>
            </select>
          </div>
        </template>

        <template #actions>
          <div class="d-flex justify-content-end">
            <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
          </div>
        </template>
    </FormSection>
</template>
