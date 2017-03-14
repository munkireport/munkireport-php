	<h2 data-i18n="wifi.wifiinfo"></h2>

	<div id="wifi-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

	<div id="wifi-view" class="row hide">
		<div class="col-md-5">
			<table class="table table-striped">
				<tr>
					<th data-i18n="wifi.ssid"></th>
					<td id="wifi-ssid"></td>
				</tr>
				<tr>
					<th data-i18n="wifi.bssid"></th>
					<td id="wifi-bssid"></td>
				</tr>
				<tr>
					<th data-i18n="wifi.state"></th>
					<td id="wifi-state"></td>
				</tr>
				<tr>
					<th data-i18n="wifi.apmode"></th>
					<td id="wifi-apmode"></td>
				</tr>
				<tr>
					<th data-i18n="wifi.xauthtype"></th>
					<td id="wifi-xauthtype"></td>
				</tr>
				<tr>
					<th data-i18n="wifi.wifiauthtype"></th>
					<td id="wifi-wifiauthtype"></td>
				</tr>
				<tr>
					<th data-i18n="wifi.lasttrx"></th>
					<td id="wifi-lasttrx"></td>
				</tr>
				<tr>
					<th data-i18n="wifi.maxtrx"></th>
					<td id="wifi-maxtrx"></td>
				</tr>
				<tr>
					<th data-i18n="wifi.rssilevel"></th>
					<td id="wifi-rssilevel"></td>
				</tr>
				<tr>
					<th data-i18n="wifi.channel"></th>
					<td id="wifi-channel"></td>
				</tr>
				<tr>
					<th data-i18n="wifi.noise"></th>
					<td id="wifi-noise"></td>
				</tr>
			</table>
		</div>
		<div class="col-md-6">
		</div>
	</div>

<script>
$(document).on('appReady', function(e, lang) {

	// Get wifi data
	$.getJSON( appUrl + '/module/wifi/get_data/' + serialNumber, function( data ) {
		if( ! data.state){
			$('#wifi-msg').text(i18n.t('no_data'));
		}
		else{

			// Hide
			$('#wifi-msg').text('');
			$('#wifi-view').removeClass('hide');

			// Add strings
			$('#wifi-ssid').text(data.ssid);
			$('#wifi-bssid').text(data.bssid);
			$('#wifi-state').text(data.state);
			$('#wifi-apmode').text(data.op_mode);
			$('#wifi-xauthtype').text(data.x802_11_auth);
			$('#wifi-wifiauthtype').text(data.link_auth);
			$('#wifi-lasttrx').text(data.lasttxrate);
			$('#wifi-maxtrx').text(data.maxrate);
			$('#wifi-rssilevel').text(data.agrctlrssi);
			$('#wifi-channel').text(data.channel);
			$('#wifi-noise').text(data.agrctlnoise);

		}

	});

});

</script>
