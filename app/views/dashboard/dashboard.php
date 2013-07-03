<?$this->view('partials/head')?>

<div class="container">

	<div class="row">
		<div class="span4">
			<h3>Errors</h3>

		</div>
		<div class="span4">
			<h3>Hardware breakdown</h3>
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
		<div class="span4">
			<h3>Network breakdown</h3>
			
			<p>Differentiate between onsite and offsite</p>
			
		</div>
		
	</div> <!-- /row -->
	<div class="row">
		<div class="span4">
			<h3>OS breakdown</h3>
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
		<div class="span4">
			<h3>Filevault status</h3>

			
		</div>
		<div class="span4">
			<h3>Battery status</h3>
			
			<p>Replace, ok, etc.</p>
			
		</div>
		
	</div> <!-- /row -->

		<div class="row">
		<div class="span4">
			<h3>Warranty status</h3>
			<?$warranty = new Warranty();
				$sql = "select count(id) as count, status from warranty group by status ORDER BY status";
				?>
			<table class="table table-striped table-condensed">
				<?foreach($warranty->query($sql) as $obj):?>
				<tr>
					<td>
						<?=$obj->status?>
						<span class="badge pull-right"><?=$obj->count?></span>
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
						<span class="badge pull-right"><?=$obj->count?></span>
					</td>
				</tr>
				<?endforeach?>
			</table>
			<a href="<?=url('clients/show/warranty_status')?>">Show all clients</a>

		</div>
		<div class="span4">
			<h3>Manifest names</h3>

			
		</div>
		<div class="span4">
			<h3>Munki</h3>
			<?$munkireport = new Munkireport();
				$sql = "select sum(errors > 0) as errors, sum(warnings>0) as warnings, sum(activity != '') as activity from munkireport;";
				?>
			<table class="table table-striped table-condensed">
				<?foreach($munkireport->query($sql) as $obj):?>
				<tr class="error">
					<td>
						Clients with errors
						<span class="badge badge-important pull-right"><?=$obj->errors?></span>
					</td>
				</tr>

				<tr class="warning">
					<td>
						Clients with warnings
						<span class="badge badge-warning pull-right"><?=$obj->warnings?></span>
					</td>
				</tr>
				<tr class="info">
					<td>
						Clients with activiy
						<span class="badge badge-info pull-right"><?=$obj->activity?></span>
					</td>
				</tr>
				<?endforeach?>
			</table>
			<p>Error, warnings, activity</p>
			
		</div>
		
	</div> <!-- /row -->
	<div class="row">
		<div class="span4">
			<h3>Disk status</h3>
			<?$diskreport = new Disk_report_model();
				$sql = "select count(id) as count from diskreport where Percentage > 85";
				?>
			<table class="table table-condensed">
				<?foreach($machine->query($sql) as $obj):?>
				<tr>
					<td>
						Over 85%
						<span class="badge badge-important pull-right"><?=$obj->count?></span>
					</td>
				</tr>
				<?endforeach?>
			</table>
			<a href="<?=url('clients/show/diskstatus')?>">Show all clients</a>
		</div>
		<div class="span4">
			<h3>..</h3>

		</div>
		<div class="span4">
			<h3>..</h3>
						
		</div>
		
	</div> <!-- /row -->

</div>	<!-- /container -->

<?$this->view('partials/foot')?>
