<?php //Initialize models needed for the table
$filevault_escrow = new filevault_escrow_model($serial_number);
$filevault_status = new filevault_status_model($serial_number);
$report   = new Reportdata_model($serial_number);
?>


<!-- Format date -->
				<script>
					$(document).ready(function() {
						$( "dd time" ).each(function( index ) {
							$(this).html(moment($(this).attr('datetime') * 1000).fromNow());
							$(this).tooltip().css('cursor', 'pointer');
						});
					});
				</script>
		

	<h2>FileVault Escrow</h2>

		<table class="table table-striped">
			<tbody>
				<tr>
					<td>Status as of last Check-in
					<dd>(<time title="<?=strftime('%c', $report->timestamp)?>" datetime="<?=$report->timestamp?>"><?=strftime('%x', $report->timestamp)?></time>)</dd></td>
					<td><?=$filevault_status->filevault_status?></td>
				</tr>
				<tr>
					<td>Enable Date</td>
					<td><?=$filevault_escrow->EnabledDate?></td>
				</tr>
				<tr>
					<td>Enabled User(s)</td>
					<td><?=$filevault_status->filevault_users?></td>
				</tr>
					<tr>
					<td>Personal Recovery Key</td>
					<td><?=$filevault_escrow->RecoveryKey?></td>
				</tr>
					<tr>
					<td>HardWare UUID</td>
					<td><?=$filevault_escrow->HardwareUUID?></td>
				</tr>
					<tr>
					<td>Logical Volume Group UUID</td>
					<td><?=$filevault_escrow->LVGUUID?></td>
				</tr>
					<tr>
					<td>Logical Volume UUID</td>
					<td><?=$filevault_escrow->LVUUID?></td>
				</tr>
					<tr>
					<td>Physical Volume UUID</td>
					<td><?=$filevault_escrow->PVUUID?></td>
				</tr>
					<tr>
					<td>Computer Serial Number</td>
					<td><?=$filevault_escrow->SerialNumber?></td>
				</tr>
					<tr>
					<td>Hard Drive Serial Number</td>
					<td><?=$filevault_escrow->HddSerial?></td>
				</tr>
			</tbody>
		</table>