		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-hdd-o"></i> <span data-i18n="free_disk_space">Free Disk Space</span></h3>
				
				</div>

				<div class="panel-body text-center">

				<?$queryobj = new Disk_report_model();
				$sql = "select COUNT(1) as total, COUNT(CASE WHEN FreeSpace < 10737418240 THEN 1 END) AS warning, 
					COUNT(CASE WHEN FreeSpace < 5368709120 THEN 1 END) AS danger FROM diskreport";
					?>
					<?if($obj = current($queryobj->query($sql))):?>
					<a href="<?=url('show/listing/disk#'.rawurlencode('freespace > 10GB'))?>" class="btn btn-success">
						<span class="bigger-150"> <?=$obj->total - $obj->warning?> </span><br>
						10GB +
					</a>
					<a href="<?=url('show/listing/disk#'.rawurlencode('5GB freespace 10GB'))?>" class="btn btn-warning">
						<span class="bigger-150"> <?=$obj->warning - $obj->danger?> </span><br>
						&lt; 10GB
					</a>
					<a href="<?=url('show/listing/disk#'.rawurlencode('freespace < 5GB'))?>" class="btn btn-danger">
						<span class="bigger-150"> <?=$obj->danger?> </span><br>
						&lt; 5GB
					</a>
					<?endif?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->