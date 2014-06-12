<?php //Initialize models needed for the table
$bluetooth = new bluetooth_model($serial_number);
?>

	<h2>Bluetooth</h2>

		<table class="table table-striped">
			<tbody>
				<tr>
					<td>Bluetooth Status</td>
					<td><?=$bluetooth->bluetooth_status?></td>
				</tr>
				<tr>
					<td>Keyboard Status</td>
					<td><?=$bluetooth->keyboard_battery?></td>
				</tr>
					<tr>
					<td>Mouse Status</td>
					<td><?=$bluetooth->mouse_battery?></td>
				</tr>
					<tr>
					<td>Trackpad Status</td>
					<td><?=$bluetooth->trackpad_battery?></td>
				</tr>
			</tbody>
		</table>