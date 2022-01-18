@extends('layouts.blank')

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-markdown.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-tagsinput.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/bootstrap-markdown.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/marked.min.js') }}"></script>
    <script src="{{ asset('assets/js/munkireport.comment.js') }}"></script>
@endpush

@section('content')
    <div class="container mt-5">
        <div class="jumbotron">
            <h1 class="display-4" data-i18n="errors.426"><i class="fa fa-exclamation-sign"></i> You are required to visit this site using a secure connection.</h1>
            <p class="lead"><a data-i18n="auth.go_secure" href="<?php echo mr_secure_url(); ?>">Go to secure site</a></p>
        </div>
    </div>
@endsection

