<script lang="ts">
    import { Badge, Form, FormGroup, Input, Label, Button, Card, CardBody, CardHeader } from 'sveltestrap';

    type User = {
        name: string;
        email: string;
        locale: string;
    }

    export let user: User;

    let name: string = user.name;
    let email: string = user.email;
    let locale: string = user.locale;
</script>

<Card>
    <CardBody>
    <Form>
        <FormGroup>
            <Label for="name">Name</Label>
            <Input
                    id="name"
                    name="name"
                    bind:value={name}
                    autocomplete="name"
            />
        </FormGroup>

        <FormGroup>
            <Label for="email">E-mail Address</Label>
            <Input
                    type="email"
                    id="email"
                    name="email"
                    bind:value={email}
                    autocomplete="username"
            />
        </FormGroup>

        <FormGroup>
            <Label for="locale">Preferred Locale</Label>
            <Input type="select" name="locale" id="locale">
                <option value="en">English</option>
            </Input>
        </FormGroup>

        <Button color="primary" type="submit" class="ml-auto">Save</Button>
    </Form>
    </CardBody>
</Card>
