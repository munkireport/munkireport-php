@extends('layouts.blank')

@push('stylesheets')

@endpush

@push('scripts')

@endpush

@section('content')
    <div class="container mt-5">
        <div class="jumbotron">
            <h1 class="display-4" data-i18n="errors.503">MunkiReport is down for maintenance.</h1>
            <p class="lead">{{ $exception->getMessage() }}</p>
        </div>
    </div>
@endsection

