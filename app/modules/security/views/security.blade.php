@extends('layouts.app')

@push('scripts')
	<script src="{{ $subdirectory }}assets/js/clients/client_list.js"></script>
	<script src="{{ $subdirectory }}assets/js/munkireport.autoupdate.js"></script>
@endpush

@section('content')

<div class="container">

 	<div class="row">

		<?php $widget->render('gatekeeper'); ?>

		<?php $widget->render('sip'); ?>

		<?php $widget->render('filevault'); ?>

	</div> <!-- /row -->

	<div class="row">

		<?php $widget->render('firmwarepw'); ?>

		<?php $widget->render('findmymac'); ?>

		<?php $widget->render('firewall_state'); ?>

    </div> <!-- /row -->

    <div class="row">

        <?php $widget->render('skel_state'); ?>

    </div> <!-- /row -->


</div>  <!-- /container -->
@endsection
