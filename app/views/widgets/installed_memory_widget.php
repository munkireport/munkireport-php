		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="icon-tasks"></i> Installed Memory</h3>

				</div>

				<div class="panel-body text-center">

					<?php
						$queryobj = new Reportdata();
						$sql = "SELECT 
  								COUNT(CASE when physical_memory >= 8 THEN 1 END) in_green,
  								COUNT(CASE when physical_memory < 8 AND physical_memory >= 4 THEN 1 END) in_yellow,
  								COUNT(CASE when physical_memory < 4 THEN  1 END) in_red
								FROM machine";
						$obj = current($queryobj->query($sql));
					?>
					<a href="<?=url('show/listing/hardware')?>" class="btn btn-success">
						<span class="bigger-150"> <?=$obj->in_green?> </span><br>
						8GB +
					</a>
					<a href="<?=url('show/listing/hardware')?>" class="btn btn-warning">
						<span class="bigger-150"> <?=$obj->in_yellow?> </span><br>
						4GB +
					</a>
					<a href="<?=url('show/listing/hardware')?>" class="btn btn-danger">
						<span class="bigger-150"> <?=$obj->in_red?> </span><br>
						< 4GB
					</a>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->
