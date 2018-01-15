@extends('layouts.app')


@push('scripts')
    <script src="{{ $subdirectory }}assets/js/munkireport.autoupdate.js"></script>
@endpush


@section('content')
<div class="container">
    @foreach($conf_dashboard_layout as $row)
    <div class="row">
        @foreach($row as $item)
		<?php $widget->render($item); ?>
		@endforeach
    </div> <!-- /row -->
    @endforeach
</div>	<!-- /container -->
@endsection