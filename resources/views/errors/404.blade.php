@extends('layouts.mr')

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
    <div class="container">
        <div class="row">
            <div class="col-xs-4 col-xs-offset-4">
                <div class="card panel-danger">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fa fa-exclamation-sign"></i> <span data-i18n="errors.title">Error</span></h3>
                    </div>
                    <div class="card-body">
                        <p>
                            <span data-i18n="errors.404">Page not found</span>
                        </p>
                        <p>
                            {{ $exception->getMessage() }}
                        </p>
                    </div>
                </div><!-- /panel -->
            </div> <!-- /span 12 -->
        </div> <!-- /row -->
    </div>  <!-- /container -->
@endsection

