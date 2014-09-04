		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

			  <div class="panel-heading" data-container="body" title="HD SMART Status">

			    <h3 class="panel-title"><i class="fa fa-exclamation-circle"></i> HD SMART Status</h3>

			  </div>

			  <div class="panel-body text-center">

			  <?php
			  	$queryobj = new Machine_model();
						$sql = "SELECT COUNT(CASE WHEN SMARTStatus='Failing' THEN 1 END) AS Failing,
										COUNT(CASE WHEN SMARTStatus='Verified' THEN 1 END) AS Verified,
										COUNT(CASE WHEN SMARTStatus='Not Supported' THEN 1 END) AS Not_Supported
							 			FROM diskreport;";
					$obj = current($queryobj->query($sql));
				?>

				<?if($obj->Failing > 0):?>

					<a href="<?=url('show/listing/disk#failing')?>" class="btn btn-danger">
						<span class="bigger-150"> <?=$obj->Failing?> </span><br>
						Failing!
					</a>

				<?else:?>

					<a href="<?=url('show/listing/disk')?>" class="btn btn-success">
						<span class="bigger-150"> <?=$obj->Verified?> </span><br>
						Verified
					</a>
					<?if($obj->Not_Supported > 0):?>
						<a href="<?=url('show/listing/disk#Not Supported')?>" class="btn btn-info">
							<span class="bigger-150"> <?=$obj->Not_Supported?> </span><br>
							Not Supported
						</a>
					<?endif?>

				<?endif?>

			  </div>

			</div><!-- /panel -->

		</div><!-- /col -->


