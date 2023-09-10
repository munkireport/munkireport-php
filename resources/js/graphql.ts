import { Client, cacheExchange, fetchExchange } from '@urql/svelte';

/**
 * Create an @urql/svelte GraphQL client, because Svelte context does not persist when using InertiaJS for routing,
 * and we want to avoid boilerplate in every Page.ts.
 *
 * - XSRF is covered by the site cookie XSRF-TOKEN unless you are doing cross-origin GQL.
 * - Auth is covered by session unless you are using stateless (we arent yet)
 */
export const createClient = (url: string) => {
    return new Client({
        url,
        exchanges: [cacheExchange, fetchExchange],
    })
};

