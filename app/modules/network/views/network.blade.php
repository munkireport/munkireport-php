@extends('layouts.app')

@push('scripts')
    <script src="{{ $subdirectory }}assets/js/clients/client_list.js"></script>
    <script src="{{ $subdirectory }}assets/js/munkireport.autoupdate.js"></script>
@endpush

@section('content')

<div class="container">

  <div class="row">

    <?php $widget->render('network_location'); ?>
    <?php $widget->render('wifi_networks'); ?>
    <?php $widget->render('wifi_state'); ?>

  </div> <!-- /row -->

  <div class="row">

      <?php $widget->render('network_vlan'); ?>

  </div> <!-- /row -->

</div>  <!-- /container -->
@endsection
