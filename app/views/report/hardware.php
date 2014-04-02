<?$this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
))?>

<div class="container">

  <div class="row">

    <?$this->view('widgets/age_widget')?>

    <?$this->view('widgets/hardware_widget')?>

  </div> <!-- /row -->

  <div class="row">

    <?$this->view('widgets/installed_memory_widget')?>

		<?$this->view('widgets/smart_status_widget')?>

    <?$this->view('widgets/extended_hardware_widget')?>

  </div> <!-- /row -->

	<div class="row">

		<?$this->view('widgets/memory_widget')?>

		<?$this->view('widgets/warranty_graph_widget')?>

	</div> <!-- /row -->


</div>  <!-- /container -->

<?$this->view('partials/foot')?>
