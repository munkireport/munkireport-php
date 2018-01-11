@extends('layouts.app')

@push('scripts')
    <script src="{{ $subdirectory }}assets/js/clients/client_list.js"></script>
    <script src="{{ $subdirectory }}assets/js/munkireport.autoupdate.js"></script>
@endpush

@section('content')
<div class="container">

	<div class="row">
		
		<?php $widget->render('registered_clients'); ?>

  </div>
  
  <div class="row">
	
  <?php $widget->render('client'); ?>

  <?php $widget->render('filevault'); ?>

  </div> <!-- /row -->

  <div class="row">

    <?php $widget->render('os'); ?>

  </div> <!-- /row -->

</div>  <!-- /container -->
@endsection