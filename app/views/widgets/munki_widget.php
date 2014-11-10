		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

			  <div class="panel-heading">

			    <h3 class="panel-title"><i class="fa fa-smile-o"></i> Munki</h3>

			  </div>

			  <div class="panel-body text-center">
			  	<?$munkireport = new Munkireport_model();
				$sql = "select 
					sum(errors > 0) as errors, 
					sum(warnings > 0) as warnings, 
					sum(pendinginstalls > 0) as pending,
					sum(installresults > 0) as installed 
					from munkireport;";
				?>
				<?foreach($munkireport->query($sql) as $obj):?>
				<a href="<?=url('show/listing/munki#errors')?>" class="btn btn-danger">
					<span class="bigger-150"> <?=$obj->errors?> </span><br>
					<span data-i18n="error" data-i18n-options='{"count":<?=$obj->errors?>}'>Errors</span>
				</a>
				<a href="<?=url('show/listing/munki#warnings')?>" class="btn btn-warning">
					<span class="bigger-150"> <?=$obj->warnings?> </span><br>
					<span data-i18n="warning" data-i18n-options='{"count":<?=$obj->warnings?>}'>Warnings</span>
				</a>
				<a href="<?=url('show/listing/munki#pendinginstalls')?>" class="btn btn-info">
					<span class="bigger-150"> <?=$obj->pending?> </span><br>
					<span data-i18n="pending">Pending</span>
				</a>
				<a href="<?=url('show/listing/munki#installresults')?>" class="btn btn-success">
					<span class="bigger-150"> <?=$obj->installed?> </span><br>
					<span data-i18n="installed">Installed</span>
				</a>
				<?endforeach?>

			  </div>

			</div><!-- /panel -->

		</div><!-- /col -->