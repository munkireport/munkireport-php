<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import BlankLayout from '@/Layouts/BlankLayout.vue';

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
  <Head title="Reset Password" />
  <BlankLayout>
    <div class="container mt-4">
      <AuthenticationCard>
          <template #header>
            Reset Password
          </template>
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

            <div class="form-group">
              <label for="password">Password</label>
              <input id="password"
                     class="form-control"
                     v-model="form.password"
                     type="password"
                     required
                     autocomplete="new-password" />
              <div class="invalid-feedback" v-if="form.errors.password">{{ form.errors.password }}</div>
            </div>
            <div class="form-group">
              <label for="password_confirmation">Confirm Password</label>
              <input id="password_password"
                     class="form-control"
                     v-model="form.password_confirmation"
                     type="password"
                     required
                     autocomplete="new-password" />
              <div class="invalid-feedback" v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</div>
            </div>
              <button class="btn btn-primary" type="submit" :disabled="form.processing">Reset Password</button>
          </form>
      </AuthenticationCard>
    </div>
  </BlankLayout>
</template>
