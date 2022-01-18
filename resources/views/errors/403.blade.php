@extends('layouts.mr')

@push('stylesheets')
@endpush

@push('scripts')
@endpush

@section('content')
    <div class="container mt-5">
        <div class="jumbotron">
            <h1 class="display-4" data-i18n="errors.403"><i class="fa fa-exclamation-sign"></i> You are not allowed to view this page</h1>
            <p class="lead">{{ $exception->getMessage() }}</p>
        </div>
    </div>
@endsection

