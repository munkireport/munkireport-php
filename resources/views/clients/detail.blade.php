@extends('layouts.mr')

@push('stylesheets')
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-markdown.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-tagsinput.css') }}" />
{{--<link rel="stylesheet" href="{{ asset('assets/css/bootstrap4-tagsinput.css') }}" />--}}
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/bootstrap-markdown.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-tagsinput.min.js') }}"></script>
{{--    <script src="{{ asset('assets/js/bootstrap4-tagsinput.js') }}">--}}
    <script src="{{ asset('assets/js/typeahead.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/marked.min.js') }}"></script>
    <script src="{{ asset('assets/js/munkireport.comment.js') }}"></script>
@endpush

@section('content')
<script>
  var serialNumber = @json($serial_number);
</script>

<div class="container-fluid">
    <div class="row pt-4">
        <div class="col">
            <div class="input-group">
                <div class="input-group-prepend">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="showTabMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span data-i18n="show" class="hidden-sm hidden-xs"></span>
                        <i class="fa fa-list fa-fw"></i>
                    </button>
                    <div class="dropdown-menu client-tabs" role="tablist" aria-labelledby="showTabMenuButton">
                        @foreach($tab_list as $name => $data)
                            <a class="dropdown-item" href="#{{ $name }}" data-toggle="tab"><span data-i18n="{{ $data['i18n'] }}"></span>
                            @isset($data['badge'])
                                <span id="{{ $data['badge'] }}" class="badge badge-secondary">0</span>
                            @endisset
                            </a>
                        @endforeach
                    </div>
                </div>

                <input type="text" class="form-control mr-computer_name_input" readonly>

                <div class="input-group-append">
                    @if (Auth::user()->can('archive', $reportData))
                    <button id="archive_button" class="btn btn-outline-secondary" type="button">
                        <span class="hidden-sm hidden-xs"></span>
                        <i class="fa fa-archive"></i>
                    </button>
                    @endif

                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="showRemoteAccessMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span data-i18n="remote_control" class="hidden-sm hidden-xs"></span>
                        <i class="fa fa-binoculars fa-fw"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" role="tablist" id="client_links" aria-labelledby="showRemoteAccessMenuButton">
                        <a class="dropdown-item" href="#">nothing available</a>
                    </div>
                </div>
            </div>
        </div><!-- /col -->
    </div><!-- /row -->
    <div class="row pt-4">
        <div class="col">
            <div class="tab-content">
                @foreach($tab_list as $name => $data)
                <div class="tab-pane @isset($data['class']) active @endisset" id="{{ $name }}">
{{--                    @isset($data['module'])--}}
{{--                        {{ "{$data['module']}::{$data['view']}" }}--}}
{{--                        @include("{$data['module']}::{$data['view']}", $data['view_vars'] ?? [])--}}
{{--                    @else--}}
{{--                        @include($data['view'], $data['view_vars'] ?? [])--}}
{{--                    @endisset--}}

                    <?php
                    mr_view(
                        $data['view'],
                        $data['view_vars'] ?? array(),
                        $data['view_path'] ?? conf('view_path'));
                    ?>
                </div>
                @endforeach
            </div>
        </div>
    </div> <!-- /row -->
</div>  <!-- /container -->

@endsection
