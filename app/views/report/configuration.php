<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
))?>

<div class="container">

  <div class="row">

	<?php $this->view('widgets/duplicated_computernames')?>

	<?php $this->view('widgets/modified_computernames_widget')?>

	<?php $this->view('widgets/bound_to_ds_widget')?>

  </div> <!-- /row -->

</div>  <!-- /container -->

<?php $this->view('partials/foot')?>
