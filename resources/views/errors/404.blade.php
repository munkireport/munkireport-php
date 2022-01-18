@extends('layouts.mr')

@push('stylesheets')
@endpush

@push('scripts')
@endpush

@section('content')
    <div class="container mt-5">
        <div class="jumbotron">
            <h1 class="display-4" data-i18n="errors.404"><i class="fa fa-exclamation-sign"></i> Page not found</h1>
            <p class="lead">The page or item you were trying to access does not exist.</p>
        </div>
    </div>
@endsection

