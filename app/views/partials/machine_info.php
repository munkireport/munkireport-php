<h2>Machine info</h2>
<div class="row">
	<div class="span6">
		<table class="table table-striped">
			<tbody>
				<tr>
					<th>Hostname:</th>
					<td><?=$machine['hostname']?></td>
				</tr>
				<tr>
					<th>Username:</th>
					<td><?=$meta['console_user']?></td>
				</tr>
				<tr>
					<th>Full username:</th>
					<td><?=$meta['long_username']?></td>
				</tr>
				<tr>
					<th>Last machine date:</th>
					<td><?=$meta['checkin-date-relative']?></td>
				</tr>
				<tr>
					<th>Remote IP:</th>
					<td><?=$meta['remote_ip']?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="span6">
		<table class="table table-striped">
			<tbody>
				<tr>
					<th>Model:</th>
					<td><?=$machine['machine_model']
						. ' '
						. $machine['machine_name']
						. ' '
						. $machine['current_processor_speed']?></td>
				</tr>
				<tr>
					<th>Memory:</th>
					<td><?=$machine['physical_memory']?></td>
				</tr>
				<tr>
					<th>Serial:</th>
					<td><?=$machine['serial_number']?></td>
				</tr>
				<tr>
					<th>OS:</th>
					<td>OS X <?=$machine['os_version'].' ('.$machine['cpu_arch'].')'?></td>
				</tr>
				<tr>
					<th>Free Disk Space:</th>
					<td><?=$machine['formatted_available_disk_space']?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>