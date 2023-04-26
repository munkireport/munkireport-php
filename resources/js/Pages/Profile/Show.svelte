<script lang="ts">
    import AppLayout from '../../Layouts/AppLayout.svelte'
    import { Col, Container, Row } from 'sveltestrap';
    import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.svelte";

    export let user;
</script>

<AppLayout>
    <Container class="mt-4">
        <Row>
            <Col>
                <h2>Profile</h2>
                <UpdateProfileInformationForm user={user} />
            </Col>
        </Row>
    </Container>
</AppLayout>
