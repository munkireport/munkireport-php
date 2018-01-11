@extends('layouts.app')

@push('scripts')
	<script src="{{ $subdirectory }}assets/js/clients/client_list.js"></script>
	<script src="{{ $subdirectory }}assets/js/munkireport.autoupdate.js"></script>
@endpush

@section('content')

<div class="container">

  <div class="row">

	<?php $widget->render('duplicated_computernames'); ?>

	<?php $widget->render('modified_computernames'); ?>

    <?php $widget->render('bound_to_ds'); ?>

  </div> <!-- /row -->

</div>  <!-- /container -->
@endsection

