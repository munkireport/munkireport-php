	<h2 data-i18n="firmware_escrow.clienttitle"></h2>

	<div id="firmware_escrow-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

	<div id="firmware_escrow-view" class="row hide">
		<div class="col-md-5">
			<table class="table table-striped">
				<tr>
					<th data-i18n="firmware_escrow.enabled_date"></th>
					<td id="firmware_escrow-enabled_date"></td>
				</tr>
				<tr>
					<th data-i18n="firmware_escrow.firmware_password"></th>
					<td id="firmware_escrow-firmware_password"></td>
				</tr>
				<tr>
					<th data-i18n="firmware_escrow.firmware_mode"></th>
					<td id="firmware_escrow-firmware_mode"></td>
				</tr>
			</table>
		</div>
		<div class="col-md-6">
		</div>
	</div>

<script>
$(document).on('appReady', function(e, lang) {

	// Get firmware_escrow data
	$.getJSON( appUrl + '/module/firmware_escrow/get_data/' + serialNumber, function( data ) {
		if( ! data.enabled_date){
			$('#firmware_escrow-msg').text(i18n.t('no_data'));
		}
		else{

			// Hide
			$('#firmware_escrow-msg').text('');
			$('#firmware_escrow-view').removeClass('hide');

			// Add strings
			$('#firmware_escrow-enabled_date').text(data.enabled_date);
			$('#firmware_escrow-firmware_password').text(data.firmware_password);
			$('#firmware_escrow-firmware_mode').text(data.firmware_mode);
		}

	});

});

</script>