@extends('layouts.app')

@push('stylesheets')
    <link rel="stylesheet" href="{{ $subdirectory }}assets/css/bootstrap-markdown.min.css" />
    <link rel="stylesheet" href="{{ $subdirectory }}assets/css/bootstrap-tagsinput.css" />
@endpush

@push('scripts')
    <script src="{{ $subdirectory }}assets/js/bootstrap-markdown.js"></script>
    <script src="{{ $subdirectory }}assets/js/bootstrap-tagsinput.min.js"></script>
    <script src="{{ $subdirectory }}assets/js/typeahead.bundle.min.js"></script>
    <script src="{{ $subdirectory }}assets/js/marked.min.js"></script>
    <script src="{{ $subdirectory }}assets/js/munkireport.comment.js"></script>
    <script src="{{ $subdirectory }}assets/js/munkireport.storageplot.js"></script>
    <script src="{{ $subdirectory }}assets/js/clients/client_detail.js"></script>
@endpush

@section('content')
<script>
  var serialNumber = '{{ $serial_number }}';
</script>

<div class="container">
    <div class="row">

        <div class="col-lg-12">

            <div class="input-group">

                <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <span data-i18n="show" class="hidden-sm hidden-xs"></span>
                        <i class="fa fa-list fa-fw"></i>
                    </button>
                    <ul class="dropdown-menu client-tabs" role="tablist">
                        @foreach($tabs as $name => $info)
                        <li>
                            <a href="#{{ $name }}" data-toggle="tab">
                                <span data-i18n="{{ $info['i18n'] }}"></span>
                                @isset ($info['badge'])
                                    <span id="{{ $info['badge'] }}" class="badge">0</span>
                                @endisset
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div><!-- /btn-group -->

                <input type="text" class="form-control mr-computer_name_input" readonly>

                <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <span data-i18n="remote_control" class="hidden-sm hidden-xs"></span>
                        <i class="fa fa-binoculars fa-fw"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" role="tablist" id="client_links">
                    </ul>
                </div><!-- /btn-group -->

            </div>

        </div><!-- /col -->

    </div><!-- /row -->
    <div class="row">
        <div class="col-lg-12">

            <div class="tab-content">
                @foreach($tabViews as $name => $viewName)
                    @if (isset($data['class']))
                        <div class="tab-pane active" id="{{ $name }}">
                    @else
                        <div class="tab-pane" id="{{ $name }}">
                    @endif

                    @include($viewName)
                    </div>
                @endforeach
            </div>
        </div> <!-- /span 12 -->
    </div> <!-- /row -->
</div>  <!-- /container -->
@endsection
