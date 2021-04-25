@extends('layouts.mr')

@push('stylesheets')
@endpush

@push('scripts')
    <script type="text/javascript" src="/js/dashboards.js"></script>
@endpush

@section('content')
<div id="app">
    <dashboards></dashboards>
</div>
@endsection
