<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container">

  <div class="row">

    <?php $this->view('widgets/network_location_widget'); ?>
    <?php $this->view('widgets/wifi_networks_widget'); ?>
    <?php $this->view('widgets/wifi_state_widget'); ?>

  </div> <!-- /row -->

  <div class="row">

      <?php $this->view('widgets/network_vlan_widget'); ?>

  </div> <!-- /row -->

</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>