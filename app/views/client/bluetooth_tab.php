<?php //Initialize models needed for the table
$bluetooth = new bluetooth_model($serial_number);
?>

	<h2>Bluetooth Status</h2>

<!-- TODO work out with the negative ones -->

		<table class="table table-striped">
			<tbody>
				<tr>
					<td>Bluetooth Status</td>
					<td>
						<?php
							if ($bluetooth->bluetooth_status == 1) {
								echo '<span class="label label-success nw-dsenabled">Enabled</span>';
							}
							if ($bluetooth->bluetooth_status == 0)  {
								echo '<span class="label label-danger nw-dsenabled">Disabled</span>';
							}
						?>
					</td>
				</tr>
				<tr>
					<td>Keyboard battery life remaining</td>
					<td><?=$bluetooth->keyboard_battery?>%</td>
				</tr>
					<tr>
					<td>Mouse battery life remaining</td>
					<td><?=$bluetooth->mouse_battery?>%</td>
				</tr>
					<tr>
					<td>Trackpad battery life remaining</td>
					<td><?=$bluetooth->trackpad_battery?>%</td>
				</tr>
			</tbody>
		</table>
