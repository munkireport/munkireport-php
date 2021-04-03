@extends('layouts.mr')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div id="page">
                <users></users>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/users.js') }}"></script>
@endsection
