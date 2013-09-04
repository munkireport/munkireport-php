<?$this->view('partials/head')?>

<div class="container">

	<div class="row">

		<?$this->view('widgets/client_widget')?>

		<?$this->view('widgets/munki_widget')?>

		<?$this->view('widgets/disk_report_widget')?>


	</div> <!-- /row -->

	<div class="row">

		<?$this->view('widgets/hardware_widget')?>

		<?$this->view('widgets/network_location_widget')?>


	</div> <!-- /row -->

	<div class="row">

		<?$this->view('widgets/new_clients_widget')?>

	</div> <!-- /row -->

</div>	<!-- /container -->

<?$this->view('partials/foot')?>
