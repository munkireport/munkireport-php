<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container">

 	<div class="row">

		<?php $widget->view($this, 'caching'); ?>
		<?php $widget->view($this, 'caching_usage'); ?>
		<?php $widget->view($this, 'caching_reachable_servers'); ?>

	</div> <!-- /row -->
    
	<div class="row">
            
		<?php $widget->view($this, 'caching_media'); ?>
		<?php $widget->view($this, 'caching_icloud'); ?>
		<?php $widget->view($this, 'caching_software'); ?>
        
	</div> <!-- /row -->

	<div class="row">

		<?php $widget->view($this, 'caching_graph'); ?>

	</div> <!-- /row -->


</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>
