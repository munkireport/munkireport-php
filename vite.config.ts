import { defineConfig } from 'vite';
import { resolve } from 'path'
import laravel from 'laravel-vite-plugin';
import gql from 'vite-plugin-simple-gql';
import vue from '@vitejs/plugin-vue';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import basicSsl from '@vitejs/plugin-basic-ssl'
import staticCopyFiles from './vite.copy';
// import * as path from "path";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.ts',
                'resources/css/app.css',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
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
            'codemirror'      // https://github.com/touchifyapp/svelte-codemirror-editor#usage-with-vite--svelte-kit
        ]
    },
    resolve: {
        alias: {
            '@': '/resources/js',
            '@vendor': '/vendor',
            // 'ziggy-js': path.resolve('vendor/tightenco/ziggy/dist/vue.es.js'),
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

                // This entry point for hybrid jQuery/Vue pages where Vue is only used on part of the page eg.
                // For the search box. These components are globally registered.
                hybrid: resolve(__dirname, 'resources/js/app-hybrid.ts'),
            }
        }
    }
});
