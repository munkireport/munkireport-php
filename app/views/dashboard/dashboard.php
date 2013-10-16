<?$this->view('partials/head')?>

<div class="container">

	<div class="row">

		<?$this->view('widgets/client_widget')?>

		<?$this->view('widgets/disk_report_widget')?>

		<?$this->view('widgets/munki_widget')?>

	</div> <!-- /row -->

	<div class="row">

		<?$this->view('widgets/hardware_widget')?>

		<?$this->view('widgets/network_location_widget')?>

	</div> <!-- /row -->

	<div class="row">

		<?$this->view('widgets/new_clients_widget')?>

		<?$this->view('widgets/warranty_widget')?>

		<?$this->view('widgets/filevault_widget')?>

	</div> <!-- /row -->

</div>	<!-- /container -->

<?$this->view('partials/foot')?>
