<?php //Initialize models needed for the table
$power = new power_model($serial_number);
?>
	<h2>Power</h2>

		<table class="table table-striped">
			<tbody>
			<thead>
				<tr>
					<th>Battery Information:</th>
					<th>Value</th>
				</tr>
			</thead>
				<tr>
					<td>Manufacture Date:</td>
					<td><?=$power->manufacture_date?></td>
				</tr>
				<tr>
					<td>Design Capacity (mAh):</td>
					<td><?=$power->design_capacity?></td>
				</tr>
					<tr>
					<td>Full Charge Capacity (mAh):</td>
					<td><?=$power->max_capacity?></td>
				</tr>
					<tr>
					<tr>
					<td>Health %:</td>
					<td><?=$power->max_percent?></td>
				</tr>
					<tr>
					<td>Charge Remaining (mAh):</td>
					<td><?=$power->current_capacity?></td>
				</tr>
					<tr>
					<td>Charged %:</td>
					<td><?=$power->current_percent?></td>
				</tr>
					<tr>
					<td>Cycle Count:</td>
					<td><?=$power->cycle_count?></td>
				</tr>
					<tr>
					<td>Temperature:</td>
					<td><?php
						if ($power->temperature == 0 || $power->temperature == ""){
							echo "";
							}
						else {
							if (conf('temperature_unit') == "F"){
								// Fahrenheit
								echo round(((($power->temperature * 9/5 ) + 3200 ) / 100), 2) . "° F";
								}
							else {
								// Celsius
								echo $power->temperature = ($power->temperature / 100) . "° C";
								}
						}
						?></td>
				</tr>
					<tr>
					<td>Condition:</td>
					<td><?=$power->condition?></td>
				</tr>
			</tbody>
		</table>
