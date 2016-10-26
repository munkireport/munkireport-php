<?php //Initialize models needed for the table
$sccm_status = "";
?>
	<h2 data-i18n="sccm_status.title"></h2>

		<table class="table table-striped">
			<tbody>
			<thead>
				<tr>
					<th data-i18n="sccm_status.titleheader"></th>
					<th>Value</th>
				</tr>
			</thead>
				<tr>
					<td data-i18n="sccm_status.enrollment_status"></td>
					<td><?=$sccm_status->agent_status?></td>
				</tr>
				<tr>
					<td data-i18n="sccm_status.mgmt_point"></td>
					<td><?=$sccm_status->mgmt_point?></td>
				</tr>
				<tr>
					<td data-i18n="sccm_status.enrollment_name"></td>
					<td><?=$sccm_status->enrollment_name?></td>
				</tr>
				<tr>
					<td data-i18n="sccm_status.last_checkin"></td>
					<td><?=$sccm_status->last_checkin?></td>
				</tr>
				<tr>
					<td data-i18n="sccm_status.cert_exp"></td>
					<td><?=$sccm_status->cert_exp?></td>
				</tr>
				<tr>
					<td data-i18n="sccm_status.enrollment_server"></td>
					<td><?=$sccm_status->enrollment_server?></td>
				</tr>
			</tbody>
		</table>
