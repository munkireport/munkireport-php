<?php $this->view('partials/head'); ?>

<div class="container">

	<?foreach(conf('dashboard_layout', array()) AS $row):?>

	<div class="row">

		<?foreach($row as $item):?>

		<?php $this->view("widgets/${item}_widget"); ?>

		<?endforeach?>

	</div> <!-- /row -->

	<?endforeach?>

</div>	<!-- /container -->

<?php $this->view('partials/foot'); ?>
