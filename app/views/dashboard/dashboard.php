<?$this->view('partials/head')?>

<div class="container">

	<div class="row">
		<div class="col-lg-4">
			<h2>Errors</h2>

		</div>
		<div class="col-lg-4">
			<h2>Hardware breakdown</h2>
			<?$machine = new Machine();
				$sql = "select count(id) as count, machine_desc from machine group by machine_desc";
				?>
			<table class="table table-striped table-condensed">
				<?foreach($machine->query($sql) as $obj):?>
				<tr>
					<td>
						<?=$obj->machine_desc?>
						<span class="badge pull-right"><?=$obj->count?></span>
					</td>
				</tr>
				<?endforeach?>
			</table>
			
			
		</div>
		<div class="col-lg-4">
			<h2>Network breakdown</h2>
			
			<p>Differentiate between onsite and offsite</p>
			
		</div>
		
	</div> <!-- /row -->
	<div class="row">
		<div class="col-lg-4">
			<h2>OS breakdown</h2>
			<? $sql = "select count(id) as count, os_version from machine group by os_version ORDER BY os_version";
				?>
			<table class="table table-striped table-condensed">
				<?foreach($machine->query($sql) as $obj):?>
				<tr>
					<td>
						<?=$obj->os_version?>
						<span class="badge pull-right"><?=$obj->count?></span>
					</td>
				</tr>
				<?endforeach?>
			</table>
		</div>
		<div class="col-lg-4">
			<h2>Filevault status</h2>

			
		</div>
		<div class="col-lg-4">
			<h2>Battery status</h2>
			
			<p>Replace, ok, etc.</p>
			
		</div>
		
	</div> <!-- /row -->

		<div class="row">
		<div class="col-lg-4">
			<h2>Warranty status</h2>
			<?$warranty = new Warranty();
				$sql = "select count(id) as count, status from warranty group by status ORDER BY status";
				?>
			<table class="table table-striped table-condensed">
				<?foreach($warranty->query($sql) as $obj):?>
				<tr>
					<td>
						<?=$obj->status?>
						<span class="label pull-right"><?=$obj->count?></span>
					</td>
				</tr>
				<?endforeach?>
			</table>
			<?	$thirtydays = date('Y-m-d', strtotime('+30days'));
				$sql = "select count(id) as count, status from warranty WHERE end_date < '$thirtydays' AND status != 'Expired' AND end_date != '' group by status ORDER BY status";
			?>
			<table class="table table-striped table-condensed">
				<?foreach($warranty->query($sql) as $obj):?>
				<tr class="warning">
					<td>
						Expires in 30 days
						<span class="label pull-right"><?=$obj->count?></span>
					</td>
				</tr>
				<?endforeach?>
			</table>
			<a href="<?=url('clients/show/warranty_status')?>">Show all clients</a>

		</div>
		<div class="col-lg-4">
			<h2>Manifest names</h2>

			
		</div>
		<div class="col-lg-4">
			<h2>Munki</h2>
			<?$munkireport = new Munkireport();
				$sql = "select sum(errors > 0) as errors, sum(warnings>0) as warnings, sum(activity != '') as activity from munkireport;";
				?>
			<table class="table table-striped table-condensed">
				<?foreach($munkireport->query($sql) as $obj):?>
				<tr>
					<td>
						Clients with errors
						<span class="label label-danger pull-right"><?=$obj->errors?></span>
					</td>
				</tr>

				<tr class="warning">
					<td>
						Clients with warnings
						<span class="label label-warning pull-right"><?=$obj->warnings?></span>
					</td>
				</tr>
				<tr class="info">
					<td>
						Clients with activiy
						<span class="label label-info pull-right"><?=$obj->activity?></span>
					</td>
				</tr>
				<?endforeach?>
			</table>
			<p>Error, warnings, activity</p>
			
		</div>
		
	</div> <!-- /row -->
	<div class="row">
		<div class="col-lg-4">
			<h2>Disk status</h2>
			<?$diskreport = new Disk_report_model();
				$sql = "select count(id) as count from diskreport where Percentage > 85";
				?>
			<table class="table table-condensed">
				<?foreach($machine->query($sql) as $obj):?>
				<tr>
					<td>
						Over 85%
						<span class="label label-danger pull-right"><?=$obj->count?></span>
					</td>
				</tr>
				<?endforeach?>
			</table>
			<a href="<?=url('clients/show/diskstatus')?>">Show all clients</a>
		</div>
		<div class="col-lg-4">
			<h2>..</h2>

		</div>
		<div class="col-lg-4">
			<h2>..</h2>
						
		</div>
		
	</div> <!-- /row -->

</div>	<!-- /container -->

<?$this->view('partials/foot')?>
