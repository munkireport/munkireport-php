<?
	$machine = new Machine($serial);
	$hash = new Hash($serial, $report_type->name);
	$reportdata = new Reportdata($serial);
	//print_r($machine);
?>

<h2>Machine info</h2>
<div class="row">
	<div class="span6">
		<table class="table table-striped">
			<tbody>
				<tr>
					<th>Hostname:</th>
					<td><?=$machine->hostname?></td>
				</tr>
				<tr>
					<th>Username:</th>
					<td><?=$reportdata->console_user?></td>
				</tr>
				<tr>
					<th>Fulll username:</th>
					<td><?=$reportdata->long_username?></td>
				</tr>
				<tr>
					<th>Last <?=$report_type->desc?> date:</th>
					<td><?=date('Y-M-d H:i:s', $hash->timestamp)?></td>
				</tr>
				<tr>
					<th>Remote IP:</th>
					<td><?=$reportdata->remote_ip?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="span6">
		<table class="table table-striped">
			<tbody>
				<tr>
					<th>Model:</th>
					<td><?=$machine->machine_model?> <?=$machine->machine_name?> <?=$machine->current_processor_speed?></td>
				</tr>
				<tr>
					<th>Memory:</th>
					<td><?=$machine->physical_memory?></td>
				</tr>
				<tr>
					<th>Serial:</th>
					<td><?=$serial?></td>
				</tr>
				<tr>
					<th>OS:</th>
					<td><?=$machine->os_version.' ('.$machine->cpu_arch.')'?></td>
				</tr>
				<tr>
					<th>Free Disk Space:</th>
					<td><?=humanreadablesize($machine->available_disk_space * 1024)?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>