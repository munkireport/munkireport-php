<?php $this->view('partials/head'); ?>

<div class="container">

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title bu-title"></h3>
				</div>
			</div>
		</div>
	</div>

	<?foreach(conf('dashboard_layout', array()) AS $row):?>

	<div class="row">

		<?foreach($row as $item):?>

		<?php $this->view("widgets/${item}_widget"); ?>

		<?endforeach?>

	</div> <!-- /row -->

	<?endforeach?>

</div>	<!-- /container -->

<script>

		// Get all business units and machine_groups
		var defer = $.when(
			$.getJSON(baseUrl + 'unit/get_data'),
			$.getJSON(baseUrl + 'unit/get_machine_groups')
			);

		// Render when all requests are successful
		defer.done(function(bu_data, mg_data){
			console.log(bu_data)
			var name = bu_data[0].name ||'All Business Units'
			$('h3.bu-title').text(name);
		});
	


</script>

<?php $this->view('partials/foot'); ?>
