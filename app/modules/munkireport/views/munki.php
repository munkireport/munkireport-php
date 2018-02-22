<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container">

  <div class="row">

    <?php $widget->view($this, 'munki'); ?>

    <?php $widget->view($this, 'munki_versions'); ?>
    
    <?php $widget->view($this, 'munkiinfo_munkiprotocol'); ?>

  </div>
  <div class="row">
	  
	  <?php $widget->view($this, 'get_failing'); ?>

	  <?php $widget->view($this, 'pending'); ?>
	
	  <?php $widget->view($this, 'manifests'); ?>

</div> <!-- /row -->
 <div class="row">

    <?php $widget->view($this, 'pending_munki'); ?>
    
    <?php $widget->view($this, 'pending_apple'); ?>


  </div> <!-- /row -->



</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>