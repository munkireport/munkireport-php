<?php $this->view('partials/head'); ?>

<div class="container">

	<?php foreach(conf('dashboard_layout', array()) AS $row):?>

	<div class="row">

		<?php foreach($row as $item):?>

		<?php $this->view("widgets/${item}_widget"); ?>

		<?php endforeach?>

	</div> <!-- /row -->

	<?php endforeach?>

</div>	<!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>
