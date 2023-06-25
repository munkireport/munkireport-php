<script lang="ts">
    import AppLayout from '../../Layouts/AppLayout.svelte'
    import {Col, Container, Row} from 'sveltestrap';
    import CodeMirror from "svelte-codemirror-editor";
    // import { graphql } from 'cm6-graphql';
    import { javascript } from "@codemirror/lang-javascript";
    import {getContextClient, queryStore, gql} from '@urql/svelte';
    let value = "";
    /// TODO: This should probably not live in a visual component
    import { Client, setContextClient, cacheExchange, fetchExchange } from '@urql/svelte';
    const client = new Client({
        url: 'https://localhost/graphql',
        exchanges: [cacheExchange, fetchExchange],
    });

    setContextClient(client);

    const data = queryStore({
        client: getContextClient(),
        query: gql`
            query {
                reportData {
                    serial_number
                }
            }
        `
    })
</script>

<AppLayout>
<!--    <CodeMirror bind:value lang={graphql()} />-->
</AppLayout>
