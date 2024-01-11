<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import BlankLayout from '@/Layouts/BlankLayout.vue';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
  <Head title="Forgot Password" />
  <BlankLayout>
    <div class="container">
      <div class="d-flex justify-content-center">
        <AuthenticationCard>
            <template #header>
              Forgot Password
            </template>
            <div class="mb-4 text-sm text-gray-600">
                <p>Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div class="form-group">
                  <label for="email">Email</label>
                  <input id="email"
                         class="form-control"
                         v-model="form.email"
                         type="email"
                         required
                         autofocus
                         autocomplete="username" />
                  <div class="invalid-feedback" v-if="form.errors.email">{{ form.errors.email }}</div>
                </div>

                <div class="d-flex justify-content-end">
                  <button class="btn btn-primary" type="submit" :disabled="form.processing">Email Password Reset Link</button>
                </div>
            </form>
        </AuthenticationCard>
      </div>
    </div>
  </BlankLayout>
</template>
