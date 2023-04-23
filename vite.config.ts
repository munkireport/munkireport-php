import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import gql from 'vite-plugin-simple-gql';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import basicSsl from '@vitejs/plugin-basic-ssl'
import {svelte, vitePreprocess} from '@sveltejs/vite-plugin-svelte';
import sveltePreprocess from 'svelte-preprocess';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts', 'resources/sass/app.scss'],
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
        viteStaticCopy({
            targets: [
                {
                    src: 'node_modules/jquery/dist/jquery.min.js',
                    dest: 'public/assets/js/jquery.min.js'
                },
                {
                    src: 'node_modules/popper.js/dist/umd/popper.min.js',
                    dest: 'public/assets/js/popper.min.js'
                },
                {
                    src: 'node_modules/bootstrap/dist/js/bootstrap.min.js',
                    dest: 'public/assets/js/bootstrap.min.js'
                },
                {
                    src: 'node_modules/bootstrap/dist/css/bootstrap.min.css',
                    dest: 'public/assets/themes/Default/bootstrap.min.css'
                },
                {
                    src: 'node_modules/bootstrap-markdown/js/bootstrap-markdown.js',
                    dest: 'public/assets/js/bootstrap-markdown.js'
                },
                {
                    src: 'node_modules/bootstrap-markdown/css/bootstrap-markdown.min.css',
                    dest: 'public/assets/css/bootstrap-markdown.min.css'
                },
                // bootstrap-tagsinput does not work with Bootstrap 4, or even 3.
                // consider breaking backwards compatibility and removing it.
                {
                    src: 'node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js',
                    dest: 'public/assets/js/bootstrap-tagsinput.min.js'
                },
                {
                    src: 'node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css',
                    dest: 'public/assets/css/bootstrap-tagsinput.css'
                },
                {
                    src: 'node_modules/bootstrap4-tagsinput/tagsinput.js',
                    dest: 'public/assets/js/bootstrap4-tagsinput.js'
                },
                {
                    src: 'node_modules/bootstrap4-tagsinput/tagsinput.css',
                    dest: 'public/assets/css/bootstrap4-tagsinput.css'
                },
                {
                    src: 'node_modules/marked/marked.min.js',
                    dest: 'public/assets/js/marked.min.js'
                },
                {
                    src: 'node_modules/moment/min/moment.min.js',
                    dest: 'public/assets/js/moment.min.js'
                },
                {
                    src: 'node_modules/nvd3/build/nv.d3.min.css',
                    dest: 'public/assets/nvd3/nv.d3.min.css'
                },
                {
                    src: 'node_modules/nvd3/build/nv.d3.min.js',
                    dest: 'public/assets/js/nv.d3.min.js'
                },

                // DataTables.NET with Bootstrap 4 styles
                {
                    src: 'node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css',
                    dest: 'public/assets/css/dataTables.bootstrap4.min.css'
                },
                {
                    src: 'node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js',
                    dest: 'public/assets/js/dataTables.bootstrap4.js'
                },

                // JSZIP required for the Excel export button on DataTables.NET
                {
                    src: 'node_modules/jszip/dist/jszip.min.js',
                    dest: 'public/assets/js/jszip.min.js'
                },

                // DataTables.NET export buttons (with Bootstrap 4 styles)
                {
                    src: 'node_modules/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css',
                    dest: 'public/assets/css/buttons.bootstrap4.min.css'
                },
                {
                    src: 'node_modules/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js',
                    dest: 'public/assets/js/buttons.bootstrap4.min.js'
                },

                // munkireport originally used the typeahead.bundle.min file
                {
                    src: 'node_modules/typeahead.js/dist/typeahead.bundle.min.js',
                    dest: 'public/assets/js/typeahead.bundle.min.js'
                },

                // Added for v6 - Bootstrap 4 Autocomplete for Search Bar
                {
                    src: 'node_modules/bootstrap-4-autocomplete/dist/bootstrap-4-autocomplete.min.js',
                    dest: 'public/js/bootstrap-4-autocomplete.min.js'
                }
            ]
        })
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
