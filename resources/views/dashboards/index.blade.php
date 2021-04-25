@extends('layouts.spa')

@push('stylesheets')
@endpush

@push('head_scripts')
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('/js/vendor.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/app.js') }}"></script>
@endpush

@section('content')
    <div class="container-fluid" id="app">
        <router-view></router-view>
    </div>
@endsection
