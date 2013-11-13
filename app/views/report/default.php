<?$this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
))?>

<div class="container">

  <div class="row">

    <?$this->view('widgets/os_widget')?>

    <?$this->view('widgets/age_widget')?>


  </div> <!-- /row -->

  <div class="row">

    <?$this->view('widgets/hardware_widget')?>

    <?$this->view('widgets/memory_widget')?>


    <?$this->view('widgets/network_location_widget')?>

  </div> <!-- /row -->


  <div class="row">

    <?$this->view('widgets/extended_hardware_widget')?>

    <?$this->view('widgets/manifests_widget')?>

    <?$this->view('widgets/warranty_graph_widget')?>

  </div> <!-- /row -->

</div>  <!-- /container -->

<?$this->view('partials/foot')?>