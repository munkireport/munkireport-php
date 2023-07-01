<script lang="ts">
    import AppLayout from '../../Layouts/AppLayout.svelte'
    import {Col, Container, Row, Table} from 'sveltestrap';
    import CodeMirror from "svelte-codemirror-editor";
    // import { graphql } from 'cm6-graphql';

    import {getContextClient, queryStore, gql} from '@urql/svelte';
    let value = `
    query($first: Int!, $page: Int) {
        reportData(first: $first, page: $page) {
            data {
                serial_number
                console_user
                long_username
            }
            paginatorInfo {
                total
            }
        }
    }
    `;
    /// TODO: This should probably not live in a visual component
    import { Client, setContextClient, cacheExchange, fetchExchange } from '@urql/svelte';

    const client = new Client({
        url: '/graphql',
        exchanges: [cacheExchange, fetchExchange],
    });

    setContextClient(client);

    let first: number = 10;
    let page: number = 1;

    $: data = queryStore({
        client: getContextClient(),
        query: gql`
            query($first: Int!, $page: Int) {
                reportData(first: $first, page: $page) {
                    data {
                        serial_number
                        console_user
                        long_username
                    }
                    paginatorInfo {
                        total
                    }
                }
            }
        `,
        variables: { first, page }
    })
</script>

<AppLayout>
    <Container fluid>
        <Row>
            <Col>
                <CodeMirror bind:value />
            </Col>
        </Row>
        <Row>
            <Col>
                {#if $data.fetching}
                    <p>Loading...</p>
                {:else if $data.error}
                    <p>Got an error</p>
                {:else}
                    <Table>
                        <tbody>
                        {#each $data.data.reportData.data as rowData}
                            <tr>
                                <td>{rowData.serial_number}</td>
                                <td>{rowData.console_user}</td>
                                <td>{rowData.long_username}</td>
                            </tr>
                        {/each}
                        </tbody>
                    </Table>
                {/if}
            </Col>
        </Row>
    </Container>

</AppLayout>
