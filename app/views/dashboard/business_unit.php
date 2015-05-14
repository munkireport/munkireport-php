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
	
		defer = $.getJSON(baseUrl + 'unit/get_data');



		defer.done(function(data){
			var name = data.name ||'All Business Units'
			$('h3.bu-title').text(name);
		});



</script>

<?php $this->view('partials/foot'); ?>
