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
			<p>Error, warnings, activity</p>
			
		</div>
		
	</div> <!-- /row -->

</div>	<!-- /container -->

<?$this->view('partials/foot')?>
