<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container">

 	<div class="row">

		<?php $widget->view($this, 'installed_memory'); ?>

		<?php $widget->view($this, 'smart_status'); ?>

		<?php $widget->view($this, 'external_displays_count'); ?>

	</div> <!-- /row -->

	<div class="row">

		<?php $widget->view($this, 'hardware_model'); ?>
		
		<?php $widget->view($this, 'screen_size_breakdown'); ?>

		<?php $widget->view($this, 'hardware_warranty'); ?>

	</div> <!-- /row -->

	<div class="row">

		<?php $widget->view($this, 'hardware_type'); ?>

		<?php $widget->view($this, 'hardware_age'); ?>

		<?php $widget->view($this, 'memory'); ?>

	</div> <!-- /row -->


</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>
