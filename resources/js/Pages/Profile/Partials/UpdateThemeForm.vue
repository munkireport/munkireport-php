<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const themeSelect = ref(null);


const props = defineProps({
  user: Object,
  themes: Array,
});

const form = useForm({
    theme: 'Default'
});

const updateTheme = () => {
    form.put(route('user-profile-information.update'), {
        errorBag: 'updateTheme',
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {

        },
    });
};
</script>

<template>
    <FormSection @submitted="updateTheme">
        <template #title>
            Update Theme
        </template>

        <template #description>
            Set your desired theme
        </template>

        <template #form>
          <div class="form-group">
            <label for="themeSelection">Theme</label>
            <select class="form-control" id="themeSelection" ref="themeSelect">
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
