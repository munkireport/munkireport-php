<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container">

  <div class="row">

    <?php $this->view('widgets/munki_widget'); ?>

    <?php $this->view('widgets/pending_apple_widget'); ?>
    
    <?php $this->view('widgets/pending_munki_widget'); ?>

  </div>
  <div class="row">

    <?php $this->view('widgets/pending_widget'); ?>

    <?php $this->view('widgets/manifests_widget'); ?>
    
    <?php $this->view('widgets/munki_versions_widget'); ?>

  </div> <!-- /row -->


</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>