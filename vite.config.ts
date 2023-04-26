import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import gql from 'vite-plugin-simple-gql';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import basicSsl from '@vitejs/plugin-basic-ssl'
import {svelte, vitePreprocess} from '@sveltejs/vite-plugin-svelte';
import sveltePreprocess from 'svelte-preprocess';
import staticCopyFiles from './vite.copy';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.ts',
                'resources/css/app.css',
            ],
            refresh: true,
        }),
        svelte({
            // preprocess: vitePreprocess(),
            preprocess: sveltePreprocess({ typescript: true }),
            configFile: false,
        }),
        gql(),
        basicSsl(),

      // This is quite ugly but allows us to keep MunkiReport v5 compatible libraries in the same build tool as v6 and
      // preserve the ability to manage their versions through package.json.
      // Note that v5 usually didn't have a transpiler. Dependencies were just added to head/body as needed.
        viteStaticCopy(staticCopyFiles)
    ],
    resolve: {
        alias: {
            '@': '/resources/js'
        }
    },
    server: {
        // Unfortunately if you develop with a PHP server running on a different port, you will hit CORS issues.
        // So we blanket allow (dangerous in production but not in dev)
        cors: {
            origin: true
        }
    }
});
