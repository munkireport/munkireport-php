import { defineConfig } from 'vite';
import { resolve } from 'path'
import laravel from 'laravel-vite-plugin';
import gql from 'vite-plugin-simple-gql';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import basicSsl from '@vitejs/plugin-basic-ssl'
import {svelte} from '@sveltejs/vite-plugin-svelte';
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
    optimizeDeps: {
        exclude: [
            '@urql/svelte',   // https://formidable.com/open-source/urql/docs/basics/svelte/#installation
            'codemirror',     // https://github.com/touchifyapp/svelte-codemirror-editor#usage-with-vite--svelte-kit
            'cm6-graphql'
        ]
    },
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
    },
    build: {
        rollupOptions: {
            input: {
                // This entry point is for full SPA
                app: resolve(__dirname, 'resources/js/app.ts'),

                // This entry point for hybrid jQuery/Svelte pages where Svelte is only used on part of the page eg.
                // For the search box
                hybrid: resolve(__dirname, 'resources/js/app-hybrid.ts'),
            }
        }
    }
});
