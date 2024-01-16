@extends('layouts.blank')

@push('stylesheets')
@endpush

@push('scripts')
@endpush

@section('content')
    <div class="container mt-5">
        <div class="jumbotron">
            <h1 class="display-4" data-i18n="errors.426"><i class="fa fa-exclamation-sign"></i> You are required to visit this site using a secure connection.</h1>
            <p class="lead"><a data-i18n="auth.go_secure" href="{{ url('/', [], true) }}">Go to secure site</a></p>
        </div>
    </div>
@endsection

