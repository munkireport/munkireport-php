@extends('layouts.spa')

@push('stylesheets')
@endpush

@push('head_scripts')
@endpush

@push('scripts')
<script type="text/javascript" src="{{ asset('/js/manifest.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/vendor.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/app.js') }}"></script>
@endpush

@section('content')
    <div id="app">
        <App/>
    </div>
@endsection
