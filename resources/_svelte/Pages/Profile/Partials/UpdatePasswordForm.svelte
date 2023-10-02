<script lang="ts">
    import {useForm} from '@inertiajs/svelte'
    import {Button, Card, CardBody, CardHeader, Col, Form, FormGroup, Input, Label, Row} from "sveltestrap";

    function submit(e) {
        e.preventDefault()
        $form.put('/user/password')
    }

    let form = useForm({
        current_password: null,
        password: null,
        password_confirmation: null,
    })

    // Required because the Svelte Form error functionality doesn't deal with errorBags
    export let errors;
    $: $form.setError(errors)
</script>


<Card class="mt-2">
    <CardHeader>
        Update Password
    </CardHeader>
    <CardBody>
        <Form on:submit={submit}>
            <Row>
                <Col sm="12" md="6">
                    <FormGroup>
                        <Label for="current_password">Current Password</Label>
                        <Input
                                id="current_password"
                                type="password"
                                bind:value={$form.current_password}
                                invalid={$form.errors.current_password}
                        />
                        {#if $form.errors.current_password}
                            <div class="invalid-feedback">{$form.errors.current_password}</div>
                        {/if}
                    </FormGroup>
                </Col>
                <Col sm="12" md="6">
                    <FormGroup>
                        <Label for="password">New Password</Label>
                        <Input
                                id="password"
                                type="password"
                                bind:value={$form.password}
                                invalid={$form.errors.password}
                        />
                        {#if $form.errors.password}
                            <div class="invalid-feedback">{$form.errors.password}</div>
                        {/if}
                    </FormGroup>
                </Col>

            </Row>
            <Row>
                <Col sm="12" md="6">

                </Col>
                <Col sm="12" md="6">
                    <FormGroup>
                        <Label for="password_confirmation">Confirm Password</Label>
                        <Input
                                id="password_confirmation"
                                type="password"
                                bind:value={$form.password_confirmation}
                        />
                    </FormGroup>

                </Col>
            </Row>
            <Row>
                <Col class="justify-content-end">
                    <Button color="primary" type="submit" disabled={$form.processing}>Update</Button>
                </Col>
            </Row>

        </Form>
    </CardBody>
</Card>
