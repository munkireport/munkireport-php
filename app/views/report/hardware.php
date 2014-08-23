<?$this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
))?>

<div class="container">

 	<div class="row">

		<?$this->view('widgets/installed_memory_widget')?>

		<?$this->view('widgets/smart_status_widget')?>

		<?$this->view('widgets/external_displays_count_widget')?>

	</div> <!-- /row -->

	<div class="row">

		<?$this->view('widgets/hardware_model_widget')?>

		<?$this->view('widgets/hardware_warranty_widget')?>

	</div> <!-- /row -->

	<div class="row">

		<?$this->view('widgets/hardware_type_widget')?>

		<?$this->view('widgets/hardware_age_widget')?>

		<?$this->view('widgets/memory_widget')?>

	</div> <!-- /row -->


</div>  <!-- /container -->

<?$this->view('partials/foot')?>
