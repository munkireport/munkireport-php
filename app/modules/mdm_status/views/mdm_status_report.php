<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container">

 	<div class="row">

		<?php $widget->view($this, 'mdm_status'); ?>
		<?php $widget->view($this, 'mdm_enrolled_via_dep'); ?>
		

	</div> <!-- /row -->
 	<div class="row">

		<?php $widget->view($this, 'user_approved_status'); ?>

	</div> <!-- /row -->

</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>
