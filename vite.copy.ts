// NOTE: not using `vite-plugin-static-copy` because it cannot copy outside the build directory.
// This is quite ugly but allows us to keep MunkiReport v5 compatible libraries in the same build tool as v6 and
// preserve the ability to manage their versions through package.json.
// Note that v5 usually didn't have a transpiler. Dependencies were just added to head/body as needed.

const copy = {
    targets: [
        {
            src: 'node_modules/jquery/dist/jquery.min.js',
            dest: 'public/assets/js'
        },
        {
            src: 'node_modules/popper.js/dist/umd/popper.min.js',
            dest: 'public/assets/js'
        },
        {
            src: 'node_modules/popper.js/dist/umd/popper.min.js.map',
            dest: 'public/assets/js'
        },
        {
            src: 'node_modules/bootstrap/dist/js/bootstrap.min.js',
            dest: 'public/assets/js'
        },
        {
            src: 'node_modules/bootstrap/dist/js/bootstrap.min.js.map',
            dest: 'public/assets/js'
        },
        {
            src: 'node_modules/bootstrap/dist/css/bootstrap.min.css',
            dest: 'public/assets/themes/Default'
        },
        {
            src: 'node_modules/bootstrap/dist/css/bootstrap.min.css.map',
            dest: 'public/assets/themes/Default'
        },
        {
            src: 'node_modules/bootstrap-markdown/js/bootstrap-markdown.js',
            dest: 'public/assets/js'
        },
        {
            src: 'node_modules/bootstrap-markdown/css/bootstrap-markdown.min.css',
            dest: 'public/assets/css'
        },
        // bootstrap-tagsinput does not work with Bootstrap 4, or even 3.
        // consider breaking backwards compatibility and removing it.
        // it is also now vulnerable
        // {
        //     src: 'node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js',
        //     dest: 'public/assets/js'
        // },
        // {
        //     src: 'node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css',
        //     dest: 'public/assets/css'
        // },
        {
            src: 'node_modules/bootstrap4-tagsinput/tagsinput.js',
            dest: 'public/assets/js'
        },
        {
            src: 'node_modules/bootstrap4-tagsinput/tagsinput.css',
            dest: 'public/assets/css'
        },
        {
            src: 'node_modules/marked/marked.min.js',
            dest: 'public/assets/js'
        },
        // Moment does not have a sourcemap
        {
            src: 'node_modules/moment/min/moment.min.js',
            dest: 'public/assets/js'
        },
        {
            src: 'node_modules/nvd3/build/nv.d3.min.css',
            dest: 'public/assets/nvd3'
        },
        {
            src: 'node_modules/nvd3/build/nv.d3.min.js',
            dest: 'public/assets/js'
        },
        {
            src: 'node_modules/nvd3/build/nv.d3.min.js.map',
            dest: 'public/assets/js'
        },

        // DataTables.NET with Bootstrap 4 styles
        {
            src: 'node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css',
            dest: 'public/assets/css'
        },
        {
            src: 'node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js',
            dest: 'public/assets/js'
        },

        // JSZIP required for the Excel export button on DataTables.NET
        {
            src: 'node_modules/jszip/dist/jszip.min.js',
            dest: 'public/assets/js'
        },

        // DataTables.NET export buttons (with Bootstrap 4 styles)
        {
            src: 'node_modules/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css',
            dest: 'public/assets/css'
        },
        {
            src: 'node_modules/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js',
            dest: 'public/assets/js'
        },

        // munkireport originally used the typeahead.bundle.min file
        {
            src: 'node_modules/typeahead.js/dist/typeahead.bundle.min.js',
            dest: 'public/assets/js'
        }
    ]
}

export default copy;
