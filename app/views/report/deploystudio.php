<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container">

  <div class="row">

    <?php $this->view('widgets/ds_workflow_widget'); ?>

  </div> <!-- /row -->

</div>  <!-- /container -->

<?php $this->view('partials/foot'); ?>
