<?php //Initialize models needed for the table
$bluetooth = new bluetooth_model($serial_number);
?>

	<h2>Bluetooth</h2>

		<table class="table table-striped">
			<tbody>
			    <tr>
					<td>Bluetooth Hardware ID</td>
					<td><?php echo $bluetooth->hardware_id; ?></td>
				</tr>
				<tr>
					<td>Bluetooth Status</td>
					<td><?php echo $bluetooth->bluetooth_status; ?></td>
				</tr>
				<tr>
					<td>Keyboard Status</td>
					<td><?php echo $bluetooth->keyboard_battery; ?></td>
				</tr>
					<tr>
					<td>Mouse Status</td>
					<td><?php echo $bluetooth->mouse_battery; ?></td>
				</tr>
					<tr>
					<td>Trackpad Status</td>
					<td><?php echo $bluetooth->trackpad_battery; ?></td>
				</tr>
			</tbody>
		</table>