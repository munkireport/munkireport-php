<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const themeSelect = ref(null);

const theme = ref('Default');

const props = defineProps({
  user: Object,
  themes: Array,
});

const form = useForm({
    theme: 'Default'
});

const updateTheme = () => {
  console.log(`Updating theme`)
  var theme_dir = `/assets/themes/${theme}/`;
  var theme_file = `${theme_dir}bootstrap.min.css`;


  // var theme = mr.getPref('theme') || default_theme;
  // var theme_dir = appUrl + '/assets/themes/' + theme + '/';
  // var theme_file = theme_dir + 'bootstrap.min.css';
  // $('#bootstrap-stylesheet').attr('href', theme_dir + 'bootstrap.min.css');
  // $('#nvd3-override-stylesheet').attr('href', theme_dir + 'nvd3.override.css');
  //
  // // Add active to menu item
  // $('[data-switch]').parent().removeClass('active');
  // $('[data-switch="'+theme+'"]').parent().addClass('active');
  //
  // // Store theme in session
  // $.post( appUrl + "/settings/theme", { set: theme });
    // form.put(route('user-profile-information.update'), {
    //     errorBag: 'updateTheme',
    //     preserveScroll: true,
    //     onSuccess: () => form.reset(),
    //     onError: () => {
    //
    //     },
    // });
};
</script>

<template>
    <FormSection>
        <template #title>
            Update Theme
        </template>

        <template #description>
            Set your desired theme
        </template>

        <template #form>
          <div class="form-group">
            <label for="themeSelection">Theme</label>
            <select class="form-control" id="themeSelection" ref="themeSelect" v-model="theme" @change="updateTheme">
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
