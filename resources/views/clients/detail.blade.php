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
<script>
  var serialNumber = @json($serial_number);
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
                        @foreach($tab_list as $name => $data)

                        <li>
                            <a href="#{{ $name }}" data-toggle="tab"><span data-i18n="{{ $data['i18n'] }}"></span>
                                @isset($data['badge'])
                                    <span id="{{ $data['badge'] }}" class="badge">0</span>
                                @endisset
                            </a>
                        </li>

                        @endforeach

                    </ul>
                </div><!-- /btn-group -->

                <input type="text" class="form-control mr-computer_name_input" readonly>

                @if (Gate::allows('archive'))
                <div class="input-group-btn">
                    <button type="button" id="archive_button" class="btn btn-default">
                        <span class="hidden-sm hidden-xs"></span>
                        <i class="fa fa-archive"></i>
                    </button>
                </div><!-- /btn-group -->
                @endif

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

                @foreach($tab_list as $name => $data)

                <div class="tab-pane @isset($data['class']) active @endisset" id="{{ $name }}">
                    <?php mr_view(
                        $data['view'],
                        isset($data['view_vars']) ? $data['view_vars'] : array(),
                        isset($data['view_path']) ? $data['view_path'] : conf('view_path')); ?>
                </div>

                @endforeach

            </div>
        </div> <!-- /span 12 -->
    </div> <!-- /row -->
</div>  <!-- /container -->

@endsection
