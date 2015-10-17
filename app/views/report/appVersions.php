<?$this->view('partials/head', array(
  "scripts" => array(
    "clients/client_list.js"
  )
))?>

<div class="container">

  <div class="row">

    <?$this->view('widgets/app_widget')?>

  </div> <!-- /row -->

</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?$this->view('partials/foot')?>
