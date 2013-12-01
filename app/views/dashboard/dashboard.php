<?$this->view('partials/head')?>

<div class="container">

	<div class="row">

		<?$this->view('widgets/client_widget')?>

		<?$this->view('widgets/disk_report_widget')?>

		<?$this->view('widgets/munki_widget')?>
		
		<?$this->view('widgets/munki_versions_widget')?>

		<?$this->view('widgets/installed_memory_widget')?>
		
		<?$this->view('widgets/bound_to_ds_widget')?>

	</div> <!-- /row -->
	<div class="row">
		
		<?$this->view('widgets/new_clients_widget')?>

		<?$this->view('widgets/pending_apple_widget')?>
		
		<?$this->view('widgets/pending_munki_widget')?>

	</div> <!-- /row -->

	<div class="row">


		<?$this->view('widgets/warranty_widget')?>

		<?$this->view('widgets/filevault_widget')?>
		
	</div> <!-- /row -->

</div>	<!-- /container -->

<script>
	// Add tooltips
	$(document).ready(function() {
		$('[title]').tooltip();
	});

</script>

<?$this->view('partials/foot')?>
