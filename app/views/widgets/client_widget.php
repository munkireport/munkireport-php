<?
$queryobj = new Reportdata_model();
$now = time();
$hour_ago = $now - 3600;
$today = strtotime('today');
$week_ago = $now - 3600 * 24 * 7;
$month_ago = $now - 3600 * 24 * 30;
$three_month_ago = $now - 3600 * 24 * 90;
$sql = "SELECT COUNT(1) as total, 
	COUNT(CASE WHEN timestamp > $hour_ago THEN 1 END) AS lasthour, 
	COUNT(CASE WHEN timestamp > $today THEN 1 END) AS today, 
	COUNT(CASE WHEN timestamp > $week_ago THEN 1 END) AS lastweek,
	COUNT(CASE WHEN timestamp > $month_ago THEN 1 END) AS lastmonth,
	COUNT(CASE WHEN timestamp BETWEEN $month_ago AND $week_ago THEN 1 END) AS inactive_week,
	COUNT(CASE WHEN timestamp BETWEEN $three_month_ago AND $month_ago THEN 1 END) AS inactive_month,
	COUNT(CASE WHEN timestamp < $three_month_ago THEN 1 END) AS inactive_three_month
	FROM reportdata";

$obj = current($queryobj->query($sql));
?>
		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-group"></i> Active clients</h3>
				
				</div>

				<div class="panel-body text-center">

					
				<?if($obj):?>

					<a href="<?=url('show/listing/clients')?>" class="btn btn-info">
						<span class="bigger-150"> <?=$obj->lastmonth?> </span>
						<br>
						This mo
					</a>
					<a href="<?=url('show/listing/clients')?>" class="btn btn-info">
						<span class="bigger-150"> <?=$obj->lastweek?> </span>
						<br>
						This wk
					</a>
					<a href="<?=url('show/listing/clients')?>" class="btn btn-info">
						<span class="bigger-150"> <?=$obj->today?> </span>
						<br>
						Today
					</a>
					<a href="<?=url('show/listing/clients')?>" class="btn btn-info">
						<span class="bigger-150"> <?=$obj->lasthour?> </span>
						<br>
						Last hour
					</a>

				<?endif?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->
		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-group"></i> Inactive clients</h3>
				
				</div>

				<div class="panel-body text-center">

					
				<?if($obj):?>

					<a href="<?=url('show/listing/clients')?>" class="btn btn-info">
						<span class="bigger-150"> <?=$obj->inactive_week?> </span>
						<br>
						Last wk
					</a>
					<a href="<?=url('show/listing/clients')?>" class="btn btn-warning">
						<span class="bigger-150"> <?=$obj->inactive_month?> </span>
						<br>
						Last mo
					</a>
					<a href="<?=url('show/listing/clients')?>" class="btn btn-danger">
						<span class="bigger-150"> <?=$obj->inactive_three_month?> </span>
						<br>
						3 months +
					</a>
					
				<?endif?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->