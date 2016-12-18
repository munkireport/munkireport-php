<?php $this->view('partials/head'); ?>

<?php
	include_once(APP_PATH . '/lib/munkireport/Widgets.php');
	$widgetObj = new munkireport\Widgets(); 
?>

<div class="container">

	<?php foreach(conf('dashboard_layout', array()) AS $row):?>

	<div class="row">

		<?php foreach($row as $item):?>

		<?php 
			$widget = $widgetObj->get($item);
			$this->view($widget->file, $widget->vars, $widget->path); 
		?>

		<?php endforeach?>

	</div> <!-- /row -->

	<?php endforeach?>

</div>	<!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>
