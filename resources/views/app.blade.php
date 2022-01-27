@extends('layouts.spa')

@push('stylesheets')
@endpush

@push('head_scripts')
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ mix('/js/manifest.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/js/vendor.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
@endpush

@section('content')
    <div id="app">
        <App></App>
    </div>
@endsection
