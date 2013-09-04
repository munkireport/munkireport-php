		<div class="col-lg-4 col-md-4">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    <h3 class="panel-title"><i class="icon-smile"></i> Munki</h3>
			  </div>
			  <div class="panel-body">
			  	<?$munkireport = new Munkireport();
				$sql = "select 
					sum(errors > 0) as errors, 
					sum(warnings > 0) as warnings, 
					sum(pendinginstalls > 0) as pending,
					sum(installresults > 0) as installed 
					from munkireport;";
				?>
				<?foreach($munkireport->query($sql) as $obj):?>
				<a href="<?=url('show/listing/munki')?>" class="btn btn-danger">
					<span class="bigger-150"> <?=$obj->errors?> </span><br>
					Errors
				</a>
				<a href="<?=url('show/listing/munki')?>" class="btn btn-warning">
					<span class="bigger-150"> <?=$obj->warnings?> </span><br>
					Warnings
				</a>
				<a href="<?=url('show/listing/munki')?>" class="btn btn-info">
					<span class="bigger-150"> <?=$obj->pending?> </span><br>
					Pending
				</a>
				<a href="<?=url('show/listing/munki')?>" class="btn btn-success">
					<span class="bigger-150"> <?=$obj->installed?> </span><br>
					Installed
				</a>
				<?endforeach?>
			  </div>

			</div><!-- /panel -->

		</div><!-- /col -->