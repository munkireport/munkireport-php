@extends('layouts.mr')

@section('content')
    <div class="container-fluid">
        <div class="row pt-4">
            <div class="col-lg-12">
                <div id="page">
                    <business-units></business-units>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/business-units.js') }}"></script>
@endsection
