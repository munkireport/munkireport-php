@extends('layouts.mr')

@push('scripts')
    <script src="{{ asset('assets/js/munkireport.autoupdate.js') }}"></script>
@endpush

@section('content')
<div class="container-fluid">
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
