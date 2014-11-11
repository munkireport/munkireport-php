		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

			  <div class="panel-heading" data-container="body" title="HD SMART Status">

			    <h3 class="panel-title"><i class="fa fa-exclamation-circle"></i> HD SMART Status</h3>

			  </div>

			  <div class="panel-body text-center">

			  <?php
			  	$queryobj = new Machine_model();
					$sql = "SELECT COUNT(*) AS total
									FROM diskreport
									WHERE SMARTStatus = 'Failing';";
					$obj = current($queryobj->query($sql));
				?>

				<?if($obj):?>

					<a href="<?=url('show/listing/disk#failing')?>" class="btn btn-danger">
						<span class="bigger-150"> <?=$obj->total?> </span><br>
						Failing!
					</a>

				<?else:?>

					<a href="<?=url('show/listing/disk')?>" class="btn btn-success">
						<span class="bigger-150"> <?=$obj->total?> </span><br>
						Verified
					</a>

				<?endif?>

			  </div>

			</div><!-- /panel -->

		</div><!-- /col -->
