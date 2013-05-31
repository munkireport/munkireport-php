<?$this->view('partials/head')?>

<div class="container">

	<div class="row">

		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('.clientlist').dataTable({
					"bFilter": false,
					"bInfo": false,
					"bPaginate": false,
					"bStateSave": true,
					"aaSorting": [[3,'desc']]
				});
			} );
		</script>
		<h1>Munkireport</h1>
		<h2>Errors</h2>

		<?if ( ! $error_clients):?>
		    <p><i>No errors.</i></p>
		<?else:?>
			<?$this->view('partials/client_table', array('clients' => $error_clients))?>
		<?endif?>

		<h2>Warnings</h2>

		<?if ( ! $warning_clients):?>
		    <p><i>No errors.</i></p>
		<?else:?>
			<?$this->view('partials/client_table', array('clients' => $warning_clients))?>
		<?endif?>


		<h2>Activity</h2>

		<?if ( ! $activity_clients):?>
		    <p><i>No active clients.</i></p>
		<?else:?>
			<?$this->view('partials/client_activity', array('clients' => $activity_clients))?>
		<?endif?>
	</div> <!-- /row -->

</div>	<!-- /container -->

<?$this->view('partials/foot')?>
