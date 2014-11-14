		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

			  <div class="panel-heading">

			    <h3 class="panel-title"><i class="fa fa-smile-o"></i> Munki</h3>

			  </div>

			  <div class="panel-body text-center">
			  	<?php $munkireport = new Munkireport_model();
				$sql = "select 
					sum(errors > 0) as errors, 
					sum(warnings > 0) as warnings, 
					sum(pendinginstalls > 0) as pending,
					sum(installresults > 0) as installed 
					from munkireport;";
				?>
				<?php foreach($munkireport->query($sql) as $obj):?>
				<a href="<?php echo url('show/listing/munki#errors'); ?>" class="btn btn-danger">
					<span class="bigger-150"> <?php echo $obj->errors; ?> </span><br>
					<span data-i18n="error" data-i18n-options='{"count":<?php echo $obj->errors; ?>}'>Errors</span>
				</a>
				<a href="<?php echo url('show/listing/munki#warnings'); ?>" class="btn btn-warning">
					<span class="bigger-150"> <?php echo $obj->warnings; ?> </span><br>
					<span data-i18n="warning" data-i18n-options='{"count":<?php echo $obj->warnings; ?>}'>Warnings</span>
				</a>
				<a href="<?php echo url('show/listing/munki#pendinginstalls'); ?>" class="btn btn-info">
					<span class="bigger-150"> <?php echo $obj->pending; ?> </span><br>
					<span data-i18n="pending">Pending</span>
				</a>
				<a href="<?php echo url('show/listing/munki#installresults'); ?>" class="btn btn-success">
					<span class="bigger-150"> <?php echo $obj->installed; ?> </span><br>
					<span data-i18n="installed">Installed</span>
				</a>
				<?php endforeach; ?>

			  </div>

			</div><!-- /panel -->

		</div><!-- /col -->