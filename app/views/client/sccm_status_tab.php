<?php //Initialize models needed for the table
$sccm_status = "";
?>
	<h2>SCCM_Agent_Status</h2>

		<table class="table table-striped">
			<tbody>
			<thead>
				<tr>
					<th>SCCM Agent Information:</th>
					<th>Value</th>
				</tr>
			</thead>
				<tr>
					<td>Enrollment Status</td>
					<td><?=$sccm_status->agent_status?></td>
				</tr>
				<tr>
					<td>Management Point</td>
					<td><?=$sccm_status->mgmt_point?></td>
				</tr>
				<tr>
					<td>Enrollment User Name</td>
					<td><?=$sccm_status->enrollment_name?></td>
				</tr>
				<tr>
					<td>Last Agent Checkin</td>
					<td><?=$sccm_status->last_checkin?></td>
				</tr>
				<tr>
					<td>Client Cert Expiry</td>
					<td><?=$sccm_status->cert_exp?></td>
				</tr>
				<tr>
					<td>Enrollment User Name</td>
					<td><?=$sccm_status->enrollment_name?></td>
				</tr>
				<tr>
					<td>Enrollment Server</td>
					<td><?=$sccm_status->enrollment_server?></td>
				</tr>
			</tbody>
		</table>
