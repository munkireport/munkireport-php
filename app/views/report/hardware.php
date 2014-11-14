<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
))?>

<div class="container">

 	<div class="row">

		<?php $this->view('widgets/installed_memory_widget')?>

		<?php $this->view('widgets/smart_status_widget')?>

		<?php $this->view('widgets/external_displays_count_widget')?>

	</div> <!-- /row -->

	<div class="row">

		<?php $this->view('widgets/hardware_model_widget')?>

		<?php $this->view('widgets/hardware_warranty_widget')?>

	</div> <!-- /row -->

	<div class="row">

		<?php $this->view('widgets/hardware_type_widget')?>

		<?php $this->view('widgets/hardware_age_widget')?>

		<?php $this->view('widgets/memory_widget')?>

	</div> <!-- /row -->


</div>  <!-- /container -->

<?php $this->view('partials/foot')?>
