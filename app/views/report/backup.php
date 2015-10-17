<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container">

  <div class="row">

    <?php $this->view('widgets/timemachine_widget'); ?>
	<?php $this->view('crashplan_widget', '', MODULE_PATH . 'crashplan/views/'); ?>

  </div>

</div>  <!-- /container -->

<?php $this->view('partials/foot'); ?>