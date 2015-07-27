		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading" data-container="body" title="Battery health status listing">

					<h3 class="panel-title"><i class="fa fa-medkit"></i> Battery Health %</h3>

				</div>

				<div class="panel-body text-center">
					<?php
						$queryobj = new Power_model();
						$sql = "SELECT COUNT(CASE WHEN max_percent>89 THEN 1 END) AS success,
										COUNT(CASE WHEN max_percent BETWEEN 80 AND 89 THEN 1 END) AS warning,
										COUNT(CASE WHEN max_percent<80 THEN 1 END) AS danger
							 			FROM power
							 			LEFT JOIN reportdata USING(serial_number)
							 			".get_machine_group_filter();
						$obj = current($queryobj->query($sql));
					?>
				<?if($obj):?>
					<a href="<?=url('show/listing/power')?>" class="btn btn-danger">
						<span class="bigger-150"> <?=$obj->danger?> </span><br>
						&lt; 80%
					</a>
					<a href="<?=url('show/listing/power')?>" class="btn btn-warning">
						<span class="bigger-150"> <?=$obj->warning?> </span><br>
						80% +
					</a>
					<a href="<?=url('show/listing/power')?>" class="btn btn-success">
						<span class="bigger-150"> <?=$obj->success?> </span><br>
						90% +
					</a>
				<?endif?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->
