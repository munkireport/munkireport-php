<script lang="ts">
    import {page} from '@inertiajs/svelte';
    import {Col, Container, Row, Table, Column, Spinner, Alert, Button, ButtonGroup} from 'sveltestrap';
    import {createClient} from '../../graphql'
    import {queryStore, gql} from "@urql/svelte";

    let client = createClient($page.props.graphql_url)
    let first: number = 10;
    let pageNumber: number = 1;
    type QueryVariables = {
        first: number;
        page: number;
    }

    type QueryResultItem = {
        serial_number: string;
        console_user: string;
        long_username: string;
    }

    type QueryResult = {
        reportData: {
            data: QueryResultItem[];
            paginatorInfo: {
                total: number;
            }
        }
    }

    $: data = queryStore<QueryResult, QueryVariables>({
        client,
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
        variables: { first, page: pageNumber }
    })
</script>


<Container fluid>
    <Row class="mt-4">
        <Col>
            {#if $data.fetching}
                <Spinner size="sm" type="grow" />
            {:else if $data.error}
                <Alert color="danger">
                    <h4 class="alert-heading text-capitalize">Error Loading Table</h4>
                    <p>
                        {$data.error}
                    </p>
                    <small>Try refreshing this table again</small>
                </Alert>
            {:else}
                <Table rows={$data.data.reportData.data} let:row>
                    <Column header="Serial" width="8rem">
                        {row.serial_number}
                    </Column>
                </Table>
            {/if}
        </Col>
    </Row>
</Container>
