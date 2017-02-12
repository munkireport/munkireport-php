	<h2 data-i18n="client.tab.fv_escrow"></h2>
	
	<div id="filevault-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>
	
	<div id="filevault-view" class="row hide">
		<div class="col-md-5">
			<table class="table table-striped">
				<tr>
					<th data-i18n="filevault.status_last_checkin" class="mr-check-in_date"></th>
					<td id="filevault-filevault_status"></td>
				</tr>
				<tr>
					<th data-i18n="filevault.enableddate"></th>
					<td id="filevault-EnabledDate"></td>
				</tr>
				<tr>
					<th data-i18n="filevault.filevault_users"></th>
					<td id="filevault-filevault_users"></td>
				</tr>
				<tr>
					<th data-i18n="filevault.recoverykey"></th>
					<td id="filevault-RecoveryKey"></td>
				</tr>
				<tr>
					<th data-i18n="filevault.lvguuid"></th>
					<td id="filevault-LVGUUID"></td>
				</tr>
				<tr>
					<th data-i18n="filevault.lvuuid"></th>
					<td id="filevault-LVUUID"></td>
				</tr>
				<tr>
					<th data-i18n="filevault.pvuuid"></th>
					<td id="filevault-PVUUID"></td>
				</tr>
				<tr>
					<th data-i18n="filevault.hddserial"></th>
					<td id="filevault-HddSerial"></td>
				</tr>
			</table>
		</div>
		<div class="col-md-6">
			<div style="height: 512px" id="map-canvas"></div>
		</div>
	</div>

<script>
$(document).on('appReady', function(e, lang) {
	
	// Get directory_service data
	$.getJSON( appUrl + '/module/filevault_status/get_data/' + serialNumber, function( data ) {

			// Hide
			$('#filevault-msg').text('');
			$('#filevault-view').removeClass('hide');

            // Add strings
			$('#filevault-EnabledDate').text(data.EnabledDate);
			$('#filevault-filevault_users').text(data.filevault_users);
			$('#filevault-RecoveryKey').text(data.RecoveryKey);
			$('#filevault-LVGUUID').text(data.LVGUUID);
			$('#filevault-LVUUID').text(data.LVUUID);
			$('#filevault-PVUUID').text(data.PVUUID);
			$('#filevault-HddSerial').text(data.HddSerial);
            
			// Set FileVault status
			if(data.filevault_status === "" && data.filevault_users !== "") {
				 $('#filevault-filevault_status').text(i18n.t('filevault.encrypted'));
			} else if(data.filevault_status === "" || data.filevault_users === "") {
				 $('#filevault-filevault_status').text(i18n.t('filevault.unencrypted'));
			} else{
				 $('filevault-filevault_status').text("");
			}
	});
	
});

</script>