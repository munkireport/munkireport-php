	<h2 data-i18n="filevault_escrow.fv_escrow"></h2>

	<div id="filevault-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

	<div id="filevault-view" class="row hide">
		<div class="col-md-5">
			<table class="table table-striped">
				<tr>
					<th data-i18n="filevault_escrow.status_last_checkin" class="mr-check-in_date"></th>
					<td id="filevault-filevault_status"></td>
				</tr>
				<tr>
					<th data-i18n="filevault_escrow.enableddate"></th>
					<td id="filevault-enableddate"></td>
				</tr>
				<tr>
					<th data-i18n="filevault_escrow.filevault_users"></th>
					<td id="filevault-filevault_users"></td>
				</tr>
				<tr>
					<th data-i18n="filevault_escrow.recoverykey"></th>
					<td id="filevault-recoverykey"></td>
				</tr>
				<tr>
					<th data-i18n="filevault_escrow.lvguuid"></th>
					<td id="filevault-lvguuid"></td>
				</tr>
				<tr>
					<th data-i18n="filevault_escrow.lvuuid"></th>
					<td id="filevault-lvuuid"></td>
				</tr>
				<tr>
					<th data-i18n="filevault_escrow.pvuuid"></th>
					<td id="filevault-pvuuid"></td>
				</tr>
				<tr>
					<th data-i18n="filevault_escrow.hddserial"></th>
					<td id="filevault-hddserial"></td>
				</tr>
			</table>
		</div>
		<div class="col-md-6">
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
			$('#filevault-enableddate').text(data.enableddate);
			$('#filevault-filevault_users').text(data.filevault_users);
			$('#filevault-recoverykey').text(data.recoverykey);
			$('#filevault-lvguuid').text(data.lvguuid);
			$('#filevault-lvuuid').text(data.lvuuid);
			$('#filevault-pvuuid').text(data.pvuuid);
			$('#filevault-hddserial').text(data.hddserial);

			// Set FileVault status
			if(data.filevault_status == "" && data.filevault_users !== "") {
				 $('#filevault-filevault_status').text(i18n.t('encrypted'));
			} else if(data.filevault_status == "" || data.filevault_users === "") {
				 $('#filevault-filevault_status').text(i18n.t('unencrypted'));
			} else{
				 $('filevault-filevault_status').text("");
			}
	});

});

</script>
