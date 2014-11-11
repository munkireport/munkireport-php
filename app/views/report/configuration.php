<?$this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
))?>

<div class="container">

  <div class="row">

	<?$this->view('widgets/duplicated_computernames')?>

	<?$this->view('widgets/modified_computernames_widget')?>

	<?$this->view('widgets/bound_to_ds_widget')?>

  </div> <!-- /row -->

</div>  <!-- /container -->

<?$this->view('partials/foot')?>
