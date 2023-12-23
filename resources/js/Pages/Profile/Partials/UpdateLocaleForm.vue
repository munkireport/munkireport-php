<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const localeInput = ref(null);


const props = defineProps({
  user: Object,
});

const form = useForm({
    locale: props.user.locale
});

const updateLocale = () => {
    form.put(route('user-profile-information.update'), {
        errorBag: 'updateLocale',
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {

        },
    });
};
</script>

<template>
    <FormSection @submitted="updateLocale">
        <template #title>
            Update Locale
        </template>

        <template #description>
            Set your language preference.
        </template>

        <template #form>
          <div class="form-group">
            <label for="localeSelection">Locale</label>
            <select class="form-control" id="localeSelection" ref="localeInput" v-model="form.locale">
              <option value="en_US">English (US)</option>
              <option value="de_DE">Deutsch (DE)</option>
              <option value="es_ES">Español (Spain)</option>
              <option value="fr_FR">Français (France)</option>
              <option value="nl_NL">Nederlands</option>
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
