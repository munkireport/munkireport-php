		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="icon-hdd"></i> Disk status</h3>
				
				</div>

				<div class="panel-body text-center">

				<?$queryobj = new Disk_report_model();
				$sql = "select COUNT(1) as total, COUNT(CASE WHEN Percentage > 80 THEN 1 END) AS warning, 
					COUNT(CASE WHEN Percentage > 90 THEN 1 END) AS danger FROM diskreport";
					?>
					<?if($obj = current($queryobj->query($sql))):?>
					<a href="<?=url('show/listing/disk')?>" class="btn btn-success">
						<span class="bigger-150"> <?=$obj->total - $obj->warning?> </span><br>
						Under 80%
					</a>
					<a href="<?=url('show/listing/disk')?>" class="btn btn-warning">
						<span class="bigger-150"> <?=$obj->warning - $obj->danger?> </span><br>
						Over 80%
					</a>
					<a href="<?=url('show/listing/disk')?>" class="btn btn-danger">
						<span class="bigger-150"> <?=$obj->danger?> </span><br>
						Over 90%
					</a>
					<?endif?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->