	<h2 data-i18n="client.tab.mbbr_status"></h2>

	<div id="mbbr_status-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

	<div id="mbbr_status-view" class="row hide">
		<div class="col-md-6">
			<table class="table table-striped">
				<tr>
					<th data-i18n="mbbr_status.entitlement_status"></th>
					<td id="mbbr_status-entitlement_status"></td>
				</tr>
				<tr>
					<th data-i18n="mbbr_status.machine_id"></th>
					<td id="mbbr_status-machine_id"></td>
				</tr>
				<tr>
					<th data-i18n="mbbr_status.install_token"></th>
					<td id="mbbr_status-install_token"></td>
				</tr>
			</table>
		</div>
		<div class="col-md-6">
			<div style="height: 512px" id="map-canvas"></div>
		</div>
	</div>

<script>

$(document).on('appReady', function(e, lang) {

	// Get mbbr_status data
	$.getJSON( appUrl + '/module/mbbr_status/get_data/' + serialNumber, function( data ) {
		if( ! data.entitlement_status){
			$('#mbbr_status-msg').text(i18n.t('no_data'));
		}
		else{

			// Hide
			$('#mbbr_status-msg').text('');
			$('#mbbr_status-view').removeClass('hide');

			// Add strings
			$('#mbbr_status-entitlement_status').text(data.entitlement_status);
			$('#mbbr_status-machine_id').text(data.machine_id);
			$('#mbbr_status-install_token').text(data.install_token);

		}

	});

});

</script>
