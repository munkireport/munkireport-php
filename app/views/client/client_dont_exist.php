<?php $this->view('partials/head'); ?>
<div class="container">
	<div class="row">
		<div class="col-sm-offset-4 col-sm-4">
			<div class="panel panel-danger">
				<div class="panel-heading">
					<h3 class="panel-title" data-i18n="errors.title"></h3>
				</div>
				<div class="panel-body" data-i18n="errors.client_nonexistent" data-i18n-options='{"serial":"<?php echo $serial_number; ?>"}'>
				</div>
			</div>
	    </div> <!-- /span 12 -->
	</div> <!-- /row -->
</div>  <!-- /container -->

<?php $this->view('partials/foot'); ?>
