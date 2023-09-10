<script lang="ts">
    import {useForm} from '@inertiajs/svelte'
    import i18next from "i18next";
    import {
        Badge,
        Form,
        FormGroup,
        Input,
        Label,
        Button,
        Card,
        CardBody,
        CardHeader,
        Row,
        Col,
        Alert
    } from 'sveltestrap';
    import {i18n} from '../../../i18n';

    export let user;
    export let locales: string[] = [];


    // The error returned by changing to a locale immediately which cannot be loaded
    let localeClientError: any;

    let form = useForm({
        name: user.name,
        email: user.email,
        locale: user.locale,
    })

    export let errors: { [inputName: string]: any };
    $: $form.setError(errors)


    function submit(e) {
        e.preventDefault()

        // i18next keeps the language preference client-side, so we need to set it immediately.
        i18next.changeLanguage($form.locale, (err, t) => {
            if (err) {
                localeClientError = err;
            }
        })

        $form.put('/user/profile-information')
    }
</script>

<Card>
    <CardHeader>
        Profile
    </CardHeader>
    <CardBody>
        <Form on:submit={submit}>
            <Row>
                <Col>
                    {#if user.source !== null}
                        <Alert color="warning">Some data is managed by your identity provider and cannot be changed by
                            MunkiReport PHP
                        </Alert>
                    {/if}
                </Col>
            </Row>
            <Row>
                <Col sm="12" md="6">
                    <FormGroup>
                        <Label for="name">{$i18n.t('displayname')}</Label>
                        <Input
                                id="name"
                                name="name"
                                bind:value={$form.name}
                                invalid={$form.errors.name}
                                disabled={user.source !== null}
                        />
                        {#if $form.errors.name}
                            <div class="invalid-feedback">{errors.name}</div>
                        {/if}
                    </FormGroup>
                </Col>
                <Col sm="12" md="6">
                    <FormGroup>
                        <Label for="email">{$i18n.t('email.email')}</Label>
                        <Input
                                type="email"
                                id="email"
                                name="email"
                                bind:value={$form.email}
                                invalid={$form.errors.email}
                                disabled={user.source !== null}
                        />
                        {#if $form.errors.email}
                            <div class="invalid-feedback">{$form.errors.email}</div>
                        {/if}
                    </FormGroup>
                </Col>
            </Row>
            <Row>
                <Col sm="12" md="6">
                    <FormGroup>
                        <Label for="locale">{$i18n.t('language')}</Label>
                        <Input type="select"
                               name="locale"
                               id="locale"
                               bind:value={$form.locale}
                               invalid={localeClientError}
                        >
                            <option value="">None (System Language)</option>
                            {#each locales as locale}
                                <option value="{locale}">{$i18n.t('nav.lang.' + locale)}</option>
                            {/each}
                        </Input>
                        {#if localeClientError}
                            <div class="invalid-feedback">{localeClientError.toString()}</div>
                        {/if}
                    </FormGroup>
                </Col>
            </Row>
            <Row>
                <Col>
                    <Button color="primary" type="submit" disabled={$form.processing}>Save</Button>
                </Col>
            </Row>
        </Form>
    </CardBody>
</Card>
