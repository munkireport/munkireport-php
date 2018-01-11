@extends('layouts.app')

@push('scripts')
    <script src="{{ $subdirectory }}assets/js/clients/client_list.js"></script>
    <script src="{{ $subdirectory }}assets/js/munkireport.autoupdate.js"></script>
@endpush

@section('content')

<div class="container">

  <div class="row">

	<?php $widget->render('disk_report'); ?>

	<?php $widget->render('disk_type'); ?>

    <?php $widget->render('filevault'); ?>

  </div> <!-- /row -->

  <div class="row">

	<?php $widget->render('smart_status'); ?>
      
	<?php $widget->render('filesystem_type'); ?>

  </div> <!-- /row -->


</div>  <!-- /container -->

@endsection
