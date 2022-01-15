@extends('layouts.mr')

@push('stylesheets')
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('assets/js/clients/client_list.js') }}"></script>
@endpush

@section('content')
    <div class="container">
        <div class="row pt-4">
            <h3 class="display-4" data-i18n="system.status"></h3>
        </div>

        <div class="row pt-4">
            <div id="mr-phpinfo" class="col">
                <h4 data-i18n="php.php"></h4>
                <table class="table table-striped"><tr><td data-i18n="loading"></td></tr></table>
            </div>

            <div id="mr-db" class="col">
                <h4 data-i18n="database"></h4>
                <table class="table table-striped"><tr><td data-i18n="loading"></td></tr></table>
            </div>
        </div>

    </div>  <!-- /container -->
@endsection
