{{--

MunkiReport v6 PHP View Dashboards.
This should be deleted when views/dashboards/default.blade.php becomes stable, as we should not use PHP style Views

--}}
@extends('layouts.mr')

@push('scripts')
    <script src="{{ asset('assets/js/munkireport.autoupdate.js') }}"></script>
@endpush

@section('content')
<div class="container-fluid">
    <div class="alert alert-warning" role="alert">
        This module is using an older version of the dashboard template. Bug the author(s) to update the view to a v6 Blade Component-Based template.
    </div>
    @foreach($dashboard_layout as $row)
	<div class="row pt-4">
        @foreach($row as $item => $data)
            @if(is_array($data) && array_key_exists('widget', $data))
                <?php
                $obj = new View();
                $widget->view($obj, $data['widget'], $data)
                ?>
            @else
                <?php
                $obj = new View();
                $widget->view($obj, $item, $data)
                ?>
            @endif
        @endforeach
	</div>
	@endforeach

</div>
@endsection
