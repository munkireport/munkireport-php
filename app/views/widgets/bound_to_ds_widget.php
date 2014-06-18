		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

			  <div class="panel-heading">

			    <h3 class="panel-title"><i class="fa fa-bullseye"></i> Bound to a Directory Service</h3>

			  </div>

			  <div class="panel-body text-center">
			  	<?php
			  	$queryobj = new directory_service_model();
				$sql = "SELECT COUNT(1) as total,
						COUNT(CASE WHEN which_directory_service LIKE '%Not bound%' THEN 1 END) AS notbound
						FROM directoryservice;";
				$obj = current($queryobj->query($sql));
				?>
				<?if($obj):?>
				<a href="<?=url('show/listing/directoryservice')?>" class="btn btn-success">
					<span class="bigger-150"> <?=$obj->total - $obj->notbound?> </span><br>
					Bound
				</a>
				<a href="<?=url('show/listing/directoryservice')?>" class="btn btn-danger">
					<span class="bigger-150"> <?=$obj->notbound?> </span><br>
					Not Bound
				</a>
				<?endif?>

			  </div>

			</div><!-- /panel -->

		</div><!-- /col -->