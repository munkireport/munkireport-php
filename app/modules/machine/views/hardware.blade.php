@extends('layouts.app')

@push('scripts')
	<script src="{{ $subdirectory }}assets/js/clients/client_list.js"></script>
	<script src="{{ $subdirectory }}assets/js/munkireport.autoupdate.js"></script>
@endpush

@section('content')

<div class="container">

 	<div class="row">

		<?php $widget->render('installed_memory'); ?>

		<?php $widget->render('smart_status'); ?>

		<?php $widget->render('external_displays_count'); ?>

	</div> <!-- /row -->

	<div class="row">

		<?php $widget->render('hardware_model'); ?>
		
		<?php $widget->render('hardware_basemodel'); ?>

		<?php $widget->render('hardware_warranty'); ?>

	</div> <!-- /row -->

	<div class="row">

		<?php $widget->render('hardware_type'); ?>

		<?php $widget->render('hardware_age'); ?>

		<?php $widget->render('memory'); ?>

	</div> <!-- /row -->


</div>  <!-- /container -->

@endsection
