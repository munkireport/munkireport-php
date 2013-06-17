<?$this->view('partials/head')?>

<div class="container">

	<div class="row">
		<div class="span4">
			<h2>Activity</h2>

		</div>
		<div class="span4">
			<h2>Hardware breakdown</h2>
			<?$machine = new Machine();
				$sql = "select count(id) as count, machine_desc from machine group by machine_desc";
				?>
			<table class="table table-striped">
				<?foreach($machine->query($sql) as $obj):?>
				<tr>
					<td><?=$obj->machine_desc?></td>
					<td><span class="badge"><?=$obj->count?></span></td>
				</tr>
				<?endforeach?>
			</table>
			
			
		</div>
		<div class="span4">
			<h2>Network breakdown</h2>
			
			<p>Differentiate between onsite and offsite</p>
			
		</div>
		
	</div> <!-- /row -->
	<div class="row">
		<div class="span4">
			<h2>OS breakdown</h2>
			<? $sql = "select count(id) as count, os_version from machine group by machine_desc";
				?>
			<table class="table table-striped">
				<?foreach($machine->query($sql) as $obj):?>
				<tr>
					<td><?=$obj->os_version?></td>
					<td><span class="badge"><?=$obj->count?></span></td>
				</tr>
				<?endforeach?>
			</table>
		</div>
		<div class="span4">
			<h2>Filevault status</h2>

			
		</div>
		<div class="span4">
			<h2>Battery status</h2>
			
			<p>Replace, ok, etc.</p>
			
		</div>
		
	</div> <!-- /row -->

		<div class="row">
		<div class="span4">
			<h2>Warranty status</h2>

		</div>
		<div class="span4">
			<h2>Manifest names</h2>

			
		</div>
		<div class="span4">
			<h2>Munki</h2>
			<?$munkireport = new Munkireport();
				$sql = "select sum(errors > 0) as errors, sum(warnings>0) as warnings, sum(activity != '') as activity from munkireport;";
				?>
			<table class="table table-striped">
				<?foreach($munkireport->query($sql) as $obj):?>
				<tr class="error">
					<td>Clients with errors</td>
					<td><span class="badge badge-important"><?=$obj->errors?></span></td>
				</tr>

				<tr class="warning">
					<td>Clients with warnings</td>
					<td><span class="badge badge-warning"><?=$obj->warnings?></span></td>
				</tr>
				<tr class="info">
					<td>Clients with activiy</td>
					<td><span class="badge badge-info"><?=$obj->activity?></span></td>
				</tr>
				<?endforeach?>
			</table>
			<p>Error, warnings, activity</p>
			
		</div>
		
	</div> <!-- /row -->
	<div class="row">
		<div class="span4">
			<h2>Disk status</h2>
			<?$diskreport = new DiskReport();
				$sql = "select count(id) as count from diskreport where Percentage > 85";
				?>
			<table class="table">
				<?foreach($machine->query($sql) as $obj):?>
				<tr>
					<td>Over 85%</td>
					<td><span class="badge badge-important"><?=$obj->count?></span></td>
				</tr>
				<?endforeach?>
			</table>
			<a href="<?=url('clients/show/diskstatus')?>">Show all clients</a>
		</div>
		<div class="span4">
			<h2>..</h2>

		</div>
		<div class="span4">
			<h2>..</h2>
						
		</div>
		
	</div> <!-- /row -->

</div>	<!-- /container -->

<?$this->view('partials/foot')?>
