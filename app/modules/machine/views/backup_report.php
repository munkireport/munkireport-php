<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container">

	<div class="row">

		<?php $widget->view($this, 'timemachine'); ?>
		<?php $widget->view($this, 'crashplan'); ?>
		<?php $widget->view($this, 'backup2go'); ?>

	</div>

</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>