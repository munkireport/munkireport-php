<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import ActionSection from '@/Components/ActionSection.vue';
import Checkbox from '@/Components/Checkbox.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TextInput from '@/Components/TextInput.vue';
import BootstrapModal from '@/Components/BootstrapModal.vue';

const props = defineProps({
    tokens: Array,
    availablePermissions: Array,
    defaultPermissions: Array,
});

const createApiTokenForm = useForm({
    name: '',
    permissions: props.defaultPermissions,
});

const updateApiTokenForm = useForm({
    permissions: [],
});

const deleteApiTokenForm = useForm({});

const displayingToken = ref(false);
const managingPermissionsFor = ref(null);
const apiTokenBeingDeleted = ref(null);

const createApiToken = () => {
    createApiTokenForm.post(route('api-tokens.store'), {
        preserveScroll: true,
        onSuccess: () => {
            displayingToken.value = true;
            createApiTokenForm.reset();
        },
    });
};

const manageApiTokenPermissions = (token) => {
    updateApiTokenForm.permissions = token.abilities;
    managingPermissionsFor.value = token;

    //this.$bvModal.show('permissions-modal')
};

const updateApiToken = () => {
    updateApiTokenForm.put(route('api-tokens.update', managingPermissionsFor.value), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => (managingPermissionsFor.value = null),
    });
};

const confirmApiTokenDeletion = (token) => {
    apiTokenBeingDeleted.value = token;
};

const deleteApiToken = () => {
    deleteApiTokenForm.delete(route('api-tokens.destroy', apiTokenBeingDeleted.value), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => (apiTokenBeingDeleted.value = null),
    });
};

</script>

<template>
    <div>
        <!-- Generate API Token -->
        <FormSection @submitted="createApiToken">
            <template #title>
                Create API Token
            </template>

            <template #description>
                API tokens allow third-party services to authenticate with our application on your behalf.
            </template>

            <template #form>
                <!-- Token Name -->
                <div class="form-group">
                  <label for="name">Name</label>
                  <input type="text"
                         class="form-control"
                         id="name"
                         v-model="createApiTokenForm.name"
                         autofocus />
                  <div v-text="createApiTokenForm.errors.name" class="invalid-feedback" />
                </div>

                <!-- Token Permissions -->
                <div v-if="availablePermissions.length > 0" class="form-group">
                    <div class="d-inline-block pr-4">Permissions</div>
                    <div v-for="permission in availablePermissions" :key="permission" class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" :checked="createApiTokenForm.permissions" :value="permission" />
                      <label class="form-check-label" :for="permission">{{ permission }}</label>
                    </div>
                </div>
            </template>

            <template #actions>
                <ActionMessage :on="createApiTokenForm.recentlySuccessful" class="mr-3">
                    Created.
                </ActionMessage>

                <PrimaryButton :class="{ 'opacity-25': createApiTokenForm.processing }" :disabled="createApiTokenForm.processing">
                    Create
                </PrimaryButton>
            </template>
        </FormSection>

        <div v-if="tokens.length > 0">
            <SectionBorder />

            <!-- Manage API Tokens -->
            <div class="mt-10 sm:mt-0">
                <ActionSection>
                    <template #title>
                        Manage API Tokens
                    </template>

                    <template #description>
                        You may delete any of your existing tokens if they are no longer needed.
                    </template>

                    <!-- API Token List -->
                    <template #content>
                        <table class="table">
                          <thead>
                            <tr>
                              <th scope="col">Name</th>
                              <th scope="col">Last used</th>
                              <th scope="col">Permissions</th>
                              <th scope="col">Delete</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="token in tokens" :key="token.id">
                              <td>{{ token.name }}</td>
                              <td>{{ token.last_used_ago }}</td>
                              <td>
                                <button
                                    v-if="availablePermissions.length > 0"
                                    type="button"
                                    class="btn btn-light"
                                    @click="manageApiTokenPermissions(token)"
                                >
                                  Permissions
                                </button>
                              </td>
                              <td>
                                <button type="button" class="btn btn-danger" @click="confirmApiTokenDeletion(token)">
                                  Delete
                                </button>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                    </template>
                </ActionSection>
            </div>
        </div>

        <!-- Token Value Modal -->
        <DialogModal :show="displayingToken" @close="displayingToken = false">
            <template #title>
                API Token
            </template>

            <template #content>
                <div>
                    Please copy your new API token. For your security, it won't be shown again.
                </div>

                <pre v-if="$page.props.jetstream.flash.token" class="mt-4 px-4 py-2 rounded">
                    <code class="user-select-all">{{ $page.props.jetstream.flash.token }}</code>
                </pre>
            </template>

            <template #footer>
                <SecondaryButton @click="displayingToken = false">
                    Close
                </SecondaryButton>
            </template>
        </DialogModal>

        <!-- API Token Permissions Modal -->
        <DialogModal :show="managingPermissionsFor != null" @close="managingPermissionsFor = null">
            <template #title>
                API Token Permissions
            </template>

            <template #content>
              <div class="row">
                <div class="col">
                    <div v-for="permission in availablePermissions" :key="permission">
                        <label class="flex items-center">
                            <Checkbox v-model:checked="updateApiTokenForm.permissions" :value="permission" />
                            <span class="ml-2 text-sm text-gray-600">{{ permission }}</span>
                        </label>
                    </div>
                </div>
              </div>
            </template>

            <template #footer>
                <button class="btn btn-secondary" @click="managingPermissionsFor = null">Cancel</button>
                <button class="btn btn-primary ml-3" :disabled="updateApiTokenForm.processing" @click="updateApiToken">Save</button>
            </template>
        </DialogModal>

        <!-- Delete Token Confirmation Modal -->
        <ConfirmationModal :show="apiTokenBeingDeleted != null" @close="apiTokenBeingDeleted = null">
            <template #title>
                Delete API Token
            </template>

            <template #content>
                Are you sure you would like to delete this API token?
            </template>

            <template #footer>
                <button class="btn btn-secondary" @click="apiTokenBeingDeleted = null">Cancel</button>
                <button
                    class="btn btn-danger ml-3"
                    :disabled="deleteApiTokenForm.processing"
                    @click="deleteApiToken"
                >Delete</button>
            </template>
        </ConfirmationModal>
    </div>
</template>
