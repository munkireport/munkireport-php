<?$this->view('partials/head')?>

<?$queryobj = new Machine();// Generic queryobject?>

<div class="container">

	<div class="row">

		<div class="col-lg-4 col-md-4">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="icon-group"></i> Clients</h3>
				
				</div>

				<div class="panel-body text-center">

					<span class="btn btn-info">
					<?$sql = "select COUNT(id) as count from machine"?>
					<?if($obj = current($queryobj->query($sql))):?>
						<span class="bigger-150"> <?=$obj->count?> </span>
						<br>
						Clients
					<?endif?>
					</span>
					<span class="btn btn-success">
					<?$hour_ago = time() - 3600;
						$sql = "select COUNT(id) as count from reportdata WHERE timestamp > $hour_ago";?>
					<?if($obj = current($queryobj->query($sql))):?>
						<span class="bigger-150"> <?=$obj->count?> </span>
						<br>
						Req/hour
					<?endif?>
					</span>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->

		<div class="col-lg-4 col-md-4">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    <h3 class="panel-title"><i class="icon-smile"></i> Munki</h3>
			  </div>
			  <div class="panel-body">
			  	<?$munkireport = new Munkireport();
				$sql = "select sum(errors > 0) as errors, sum(warnings>0) as warnings, sum(pendinginstalls != 0) as activity from munkireport;";
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
					<span class="bigger-150"> <?=$obj->activity?> </span><br>
					Activiy
				</a>
				<?endforeach?>
			  </div>

			</div><!-- /panel -->

		</div><!-- /col -->


		<div class="col-lg-4 col-md-4">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="icon-hdd"></i> Disk status</h3>
				
				</div>

				<div class="panel-body">

				<?$sql = "select COUNT(CASE WHEN Percentage > 80 THEN 1 END) AS warning, 
					COUNT(CASE WHEN Percentage > 90 THEN 1 END) AS danger FROM diskreport";
					?>
					<?if($obj = current($queryobj->query($sql))):?>
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

	</div> <!-- /row -->

	<div class="row">

		<div class="col-lg-4">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="icon-globe"></i> IP</h3>
				
				</div>

				<div class="panel-body">

					<p>Differentiate between onsite and offsite</p>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->

		<div class="col-lg-4">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="icon-umbrella"></i> Warranty status</h3>
				
				</div>

				<div class="panel-body">

					<?$warranty = new Warranty();
				$sql = "select count(id) as count, status from warranty group by status ORDER BY status";
				?>
				<?foreach($warranty->query($sql) as $obj):?>
				<a href="<?=url('show/listing/warranty')?>" class="btn btn-default">
					<span class="bigger-150"> <?=$obj->count?> </span><br>
					<?=$obj->status?>
				</a>
				<?endforeach?>
			<?	$thirtydays = date('Y-m-d', strtotime('+30days'));
				$sql = "select count(id) as count, status from warranty WHERE end_date < '$thirtydays' AND status != 'Expired' AND end_date != '' group by status ORDER BY status";
			?>
				<?foreach($warranty->query($sql) as $obj):?>
				<a href="<?=url('show/listing/warranty')?>" class="btn btn-default">
					<span class="bigger-150"> <?=$obj->count?> </span><br>
					Expires in 30 days (<?=$obj->status?>)
				</a>
				<?endforeach?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->


		<div class="col-lg-4">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="icon-star"></i> New clients</h3>

				</div>
			  	
			  	<?$sql = "SELECT machine.serial_number, computer_name, reg_timestamp FROM machine LEFT JOIN reportdata USING (serial_number) ORDER BY reg_timestamp LIMIT 5"?>
				<table class="table">
					<?foreach($queryobj->query($sql) as $obj):?> 
					<tr>
						<td><a class="btn btn-xs btn-default" href="<?=url('clients/detail/'.$obj->serial_number)?>"><?=$obj->computer_name?></a></td>
						<td class="text-right"><time datetime="<?=$obj->reg_timestamp?>">...</time></td>
					</tr>
					<?endforeach?>
				</table>
			<script>
			$(document).ready(function() {
				$( "time" ).each(function( index ) {
					var date = new Date($(this).attr('datetime') * 1000);
					$(this).html(moment(date).fromNow());
				});
			});
			</script>

			</div><!-- /panel -->

		</div><!-- /col -->

		
	</div> <!-- /row -->

</div>	<!-- /container -->

<?$this->view('partials/foot')?>
