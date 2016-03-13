<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container">

  <div class="row">

	<?php $this->view('widgets/duplicated_computernames_widget'); ?>

	<?php $this->view('widgets/modified_computernames_widget'); ?>

	<?php $this->view('widgets/certificate_widget'); ?>

  </div> <!-- /row -->

  <div class="row">

	  <?php $this->view('tag_widget', '', MODULE_PATH . 'tag/views/'); ?>

	  <?php $this->view('widgets/bound_to_ds_widget'); ?>

  </div> <!-- /row -->
  
  <div class="row">

	  <?php $this->view('notification_widget', '', MODULE_PATH . 'notification/views/'); ?>

  </div> <!-- /row -->

</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>
