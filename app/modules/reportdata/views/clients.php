<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container">

	<div class="row">
		
		<?php $widget->view($this, 'registered_clients'); ?>

  </div>
  
  <div class="row">
	
  <?php $widget->view($this, 'client'); ?>

  <?php $widget->view($this, 'filevault'); ?>

  </div> <!-- /row -->

  <div class="row">

    <?php $widget->view($this, 'os'); ?>

    <?php $widget->view($this, 'osbuild'); ?>


  </div> <!-- /row -->

</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>
