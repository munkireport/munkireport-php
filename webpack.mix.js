const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// The mix.copy statements below reflect what you would have to do to update these dependencies manually.
// (Which is what happened in the past).
// Eventually they should be transpiled with webpack into a single vendors.js

mix.copy('node_modules/jquery/dist/jquery.min.js', 'public/assets/js/jquery.min.js');

mix.copy('node_modules/bootstrap/dist/js/bootstrap.min.js', 'public/assets/js/bootstrap.min.js');
mix.copy('node_modules/bootstrap/dist/css/bootstrap.min.css', 'public/assets/themes/Default/bootstrap.min.css');

mix.copy('node_modules/bootstrap-markdown/js/bootstrap-markdown.js', 'public/assets/js/bootstrap-markdown.js');
mix.copy('node_modules/bootstrap-markdown/css/bootstrap-markdown.min.css', 'public/assets/css/bootstrap-markdown.min.css');

mix.copy('node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js', 'public/assets/js/bootstrap-tagsinput.min.js');
mix.copy('node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css', 'public/assets/css/bootstrap-tagsinput.css');

mix.copy('node_modules/marked/marked.min.js', 'public/assets/js/marked.min.js');


mix.copy('node_modules/moment/min/moment.min.js', 'public/assets/js/moment.min.js');
// TODO: Locales?

mix.copy('node_modules/nvd3/build/nv.d3.min.css', 'public/assets/nvd3/nv.d3.min.css');
mix.copy('node_modules/nvd3/build/nv.d3.min.js', 'public/assets/js/nv.d3.min.js');

// DataTables.NET with Bootstrap 4 styles
mix.copy('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css', 'public/assets/css/dataTables.bootstrap4.min.css');
mix.copy('node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js', 'public/assets/js/dataTables.bootstrap4.js');

// JSZIP required for the Excel export button on DataTables.NET
mix.copy('node_modules/jszip/dist/jszip.min.js', 'public/assets/js/jszip.min.js');

// DataTables.NET export buttons (with Bootstrap 4 styles)
mix.copy('node_modules/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css', 'public/assets/css/buttons.bootstrap4.min.css');
mix.copy('node_modules/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js', 'public/assets/js/buttons.bootstrap4.min.js');


// munkireport originally used the typeahead.bundle.min file
mix.copy('node_modules/typeahead.js/dist/typeahead.bundle.min.js', 'public/assets/js/typeahead.bundle.min.js');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.js('resources/js/business-units.js', 'public/js');
mix.js('resources/js/profile.js', 'public/js');
mix.js('resources/js/users.js', 'public/js');
