		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading" data-container="body" title="Battery condition listing">

					<h3 class="panel-title"><i class="fa fa-flash"></i> Battery Condition</h3>

				</div>

				<div class="panel-body text-center">
					<?php
						$queryobj = new Power_model();
						$sql = "SELECT COUNT(CASE WHEN condition='Normal' THEN 1 END) AS Normal,
										COUNT(CASE WHEN condition='Replace Soon' THEN 1 END) AS Soon,
										COUNT(CASE WHEN condition='Service Battery' THEN 1 END) AS Service,
										COUNT(CASE WHEN condition='Replace Now' THEN 1 END) AS Now,
										COUNT(CASE WHEN condition='No Battery' THEN 1 END) AS Missing
							 			FROM power;";
						$obj = current($queryobj->query($sql));
					?>
				<?if($obj):?>
					<a href="<?=url('show/listing/power#Replace%20Now')?>" class="btn btn-danger">
						<span class="bigger-150"> <?=$obj->Now?> </span><br>
						Now
					</a>
					<a href="<?=url('show/listing/power#Service%20Battery')?>" class="btn btn-warning">
						<span class="bigger-150"> <?=$obj->Service?> </span><br>
						Service
					</a>
					<a href="<?=url('show/listing/power#Replace%20Soon')?>" class="btn btn-warning">
						<span class="bigger-150"> <?=$obj->Soon?> </span><br>
						Soon
					</a>
					<a href="<?=url('show/listing/power#Normal')?>" class="btn btn-success">
						<span class="bigger-150"> <?=$obj->Normal?> </span><br>
						Normal
					</a>
					<?if($obj->Missing > 0):?>
						<a href="<?=url('show/listing/power#No%20Battery')?>" class="btn btn-danger">
							<span class="bigger-150"> <?=$obj->Missing?> </span><br>
							No Battery
					<?endif?>
					</a>
				<?endif?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->
