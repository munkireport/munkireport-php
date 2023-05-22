<script lang="ts">
    import AppLayout from '../../Layouts/AppLayout.svelte'
    import {Col, Container, Row} from 'sveltestrap';
    import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.svelte";
    import UpdatePasswordForm from "./Partials/UpdatePasswordForm.svelte";
    interface UserProfileErrorBags {
        updateProfileInformation?: { [inputName: string]: any },
        updatePassword?: { [inputName: string]: any },
    }

    export let user;
    export let locales: string[];
    export let errors: UserProfileErrorBags;
</script>

<AppLayout>
    <Container class="mt-4">
        <Row>
            <Col>
                <UpdateProfileInformationForm user={user} locales={locales} errors={errors && errors.updateProfileInformation} />
                {#if user.source === null}
                    <UpdatePasswordForm errors={errors && errors.updatePassword} />
                {/if}
            </Col>
        </Row>
    </Container>
</AppLayout>
