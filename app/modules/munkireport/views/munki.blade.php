@extends('layouts.app')

@push('scripts')
    <script src="{{ $subdirectory }}assets/js/clients/client_list.js"></script>
    <script src="{{ $subdirectory }}assets/js/munkireport.autoupdate.js"></script>
@endpush

@section('content')

<div class="container">

  <div class="row">

    <?php $widget->render('munki'); ?>

    <?php $widget->render('munki_versions'); ?>
    
    <?php $widget->render('munkiinfo_munkiprotocol'); ?>

  </div>
  <div class="row">
	  
	  <?php $widget->render('get_failing'); ?>

	  <?php $widget->render('pending'); ?>
	
	  <?php $widget->render('manifests'); ?>

</div> <!-- /row -->
 <div class="row">

    <?php $widget->render('pending_munki'); ?>
    
    <?php $widget->render('pending_apple'); ?>


  </div> <!-- /row -->



</div>  <!-- /container -->
@endsection