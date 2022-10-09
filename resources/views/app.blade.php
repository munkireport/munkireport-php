@extends('layouts.spa')

@push('stylesheets')
@endpush

@push('head_scripts')
@endpush

@push('scripts')
    @vite(['/js/manifest.js', '/js/vendor.js', '/js/app.js'])
@endpush

@section('content')
    <div id="app">
        <App></App>
    </div>
@endsection
