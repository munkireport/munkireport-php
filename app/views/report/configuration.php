<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container">

  <div class="row">

	<?php $widget->view($this, 'duplicated_computernames'); ?>
	
	<?php $widget->view($this, 'modified_computernames'); ?>
	
	<?php $widget->view($this, 'tag'); ?>

  </div> <!-- /row -->

  <div class="row">

	  <?php $widget->view($this, 'certificate'); ?>

	  <?php $widget->view($this, 'bound_to_ds'); ?>
	  
	  <?php $widget->view($this, 'findmymac'); ?>

  </div> <!-- /row -->
  
  <div class="row">
	
	<?php $widget->view($this, 'printer'); ?>

  </div> <!-- /row -->

</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>
