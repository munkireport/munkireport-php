<?$this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
))?>

<div class="container">

  <div class="row">

    <?$this->view('widgets/os_widget')?>

    <?$this->view('widgets/hardware_age_widget')?>


  </div> <!-- /row -->

  <div class="row">

    <?$this->view('widgets/computer_type_widget')?>

    <?$this->view('widgets/network_location_widget')?>

  </div> <!-- /row -->


  <div class="row">

    <?$this->view('widgets/models_breakdown_widget')?>

    <?$this->view('widgets/manifests_widget')?>

    <?$this->view('widgets/warranty_graph_widget')?>

  </div> <!-- /row -->

</div>  <!-- /container -->

<?$this->view('partials/foot')?>