<?php //Initialize models needed for the table
$firmware_escrow = new firmware_escrow_model($serial_number);
$report = new Reportdata_model($serial_number);
?>


	<h2>Firmware Password Escrow</h2>

		<table class="table table-striped">
			<tbody>
				<tr>
					<td>Enable Date</td>
					<td><?php echo $firmware_escrow->enabled_date; ?></td>
				</tr>
				<tr>
					<td>Firmware Password</td>
					<td><?php echo $firmware_escrow->firmware_password; ?></td>
				</tr>
				<tr>
					<td>Firmware Mode</td>
					<td><?php echo $firmware_escrow->firmware_mode; ?></td>
				</tr>
			</tbody>
		</table>