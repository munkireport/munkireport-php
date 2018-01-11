@extends('layouts.app')

@push('scripts')
	<script src="{{ $subdirectory }}assets/js/clients/client_list.js"></script>
	<script src="{{ $subdirectory }}assets/js/munkireport.autoupdate.js"></script>
@endpush

@section('content')

<div class="container">

	<div class="row">

		<?php $widget->render('timemachine'); ?>
		<?php $widget->render('crashplan'); ?>
		<?php $widget->render('backup2go'); ?>

	</div>

</div>  <!-- /container -->

@endsection