<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container">

  <div class="row">

    <?php $this->view('widgets/timemachine_widget'); ?>
		<?php $this->view('crashplan_widget', '', MODULE_PATH . 'crashplan/views/'); ?>
		<?php $this->view('backup2go_widget', '', MODULE_PATH . 'backup2go/views/'); ?>

  </div>

</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>