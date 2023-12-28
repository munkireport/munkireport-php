<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('user-password.update'), {
        errorBag: 'updatePassword',
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }

            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <FormSection @submitted="updatePassword">
        <template #title>
            Update Password
        </template>

        <template #description>
            Ensure your account is using a long, random password to stay secure.
        </template>

        <template #form>
          <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password"
                   class="form-control"
                   id="current_password"
                   ref="currentPasswordInput"
                   v-model="form.current_password"
                   autocomplete="current-password"
                   :class="{ 'is-invalid': !!form.errors.current_password }"
            />
            <div v-text="form.errors.current_password" class="invalid-feedback" />
          </div>

          <div class="form-group">
            <label for="password">New Password</label>
            <input
                id="password"
                class="form-control"
                ref="passwordInput"
                v-model="form.password"
                type="password"
                autocomplete="new-password"
                :class="{ 'is-invalid': !!form.errors.password }"
            />
            <div v-text="form.errors.password" class="invalid-feedback" />
          </div>

          <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                class="form-control"
                autocomplete="new-password"
                :class="{ 'is-invalid': !!form.errors.password_confirmation }"
            />
            <div v-text="form.errors.password_confirmation" class="invalid-feedback" />
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
