@extends('layouts.mr')

@push('stylesheets')
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/munkireport.autoupdate.js') }}"></script>
    <script>
      var loadAllModuleLocales = true
    </script>
@endpush


@section('content')
    <div class="container">
        <div class="row pt-4">
            <div class="col">
                <h3 class="display-4">
                    <span data-i18n="widget.gallery"></span>
                    <span id="total-count" class="badge badge-light">
                    {{ count($dashboard_layout) }}
                </span>
                </h3>
            </div>
        </div>

        @foreach($dashboard_layout AS $item => $data)
            <div class="row">
                <div class="col-lg-12" id="{{ $data['widget_obj']->name }}_gallery">
                    <h2>{{ $data['widget_obj']->name }}</h2>
                </div>

                @if(array_key_exists('widget', $data))
                    <?php $widget->view($this, $data['widget'], $data); ?>
                @else
                    <?php $widget->view($this, $item, $data); ?>
                @endif

                <div class="col-md-6">
                    <table class="table table-striped">
                        <tr>
                            <th data-i18n="widget.module"></th>
                            <td>
                                {{ $data['widget_obj']->module }}
                                @if($data['widget_obj']->active)
                                    <span class="label label-success pull-right" data-i18n="active"></span>
                                @else
                                    <span class="label label-danger pull-right" data-i18n="inactive"></span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th data-i18n="widget.file_name"></th>
                            @if($data['widget_obj']->type == 'yaml')
                            <td>{{ $data['widget_obj']->view }}.yml</td>
                            @else
                            <td>{{ $data['widget_obj']->view }}.php</td>
                            @endif
                        </tr>
                        <tr>
                            <th data-i18n="widget.path"></th>
                            <td>{{ $data['widget_obj']->widget_file }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
@endsection
