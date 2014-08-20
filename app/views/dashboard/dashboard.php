<?$this->view('partials/head')?>

<div class="container">

	<?foreach(conf('dashboard_layout', array()) AS $row):?>

	<div class="row">

		<?foreach($row as $item):?>

		<?$this->view("widgets/${item}_widget")?>

		<?endforeach?>

	</div> <!-- /row -->

	<?endforeach?>

</div>	<!-- /container -->

<?$this->view('partials/foot')?>
