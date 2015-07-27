<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container">

  <div class="row">

    <?php $this->view('widgets/registered_clients_widget'); ?>

  </div>

  <div class="row">

    <?php $this->view('widgets/os_widget'); ?>

    <?php $this->view('widgets/client_widget'); ?>

  </div> <!-- /row -->

</div>  <!-- /container -->

<?php $this->view('partials/foot'); ?>