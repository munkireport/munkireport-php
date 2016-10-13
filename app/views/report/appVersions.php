<?php $this->view('partials/head', array(
  "scripts" => array(
    "clients/client_list.js"
  )
))?>

<div class="container">

  <div class="row">

    <?php $this->view('widgets/app_widget')?>

  </div> <!-- /row -->

</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot')?>
