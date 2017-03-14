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
					<th data-i18n="wifi.channel"></th>
					<td id="wifi-channel"></td>
				</tr>
				<tr>
					<th data-i18n="wifi.rssilevel"></th>
					<td id="wifi-rssilevel"></td>
				</tr>
				<tr>
					<th data-i18n="wifi.noise"></th>
					<td id="wifi-noise"></td>
				</tr>
				<tr>
					<th data-i18n="wifi.snr"></th>
					<td id="wifi-snr"></td>
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
		} else if (data.state == 'no wifi') {
			$('#wifi-msg').text(i18n.t('wifi.no_wifi_client_tab'));
		} else if (data.state == 'off') {
			$('#wifi-msg').text(i18n.t('wifi.off_client_tab'));
        } else{

			// Hide
			$('#wifi-msg').text('');
			$('#wifi-view').removeClass('hide');

			// Add strings
			$('#wifi-ssid').text(data.ssid);
			$('#wifi-bssid').text(data.bssid);
			$('#wifi-lasttrx').html('<span title="'+(data.lasttxrate*0.125)+' MB/sec">'+data.lasttxrate+" Mbps</span>");
			$('#wifi-maxtrx').html('<span title="'+(data.maxrate*0.125)+' MB/sec">'+data.maxrate+" Mbps</span>");
			$('#wifi-channel').text(data.channel);
			$('#wifi-rssilevel').html('<span title="'+i18n.t('wifi.rssi_detail')+'">'+data.agrctlrssi+" db</span>");
			$('#wifi-noise').html('<span title="'+i18n.t('wifi.noise_detail')+'">'+data.agrctlnoise+" db</span>");
            
			if(data.state == "running") {
				 $('#wifi-state').text(i18n.t('wifi.running'));
			} else if(data.state == "off") {
				 $('#wifi-state').text(i18n.t('wifi.off'));
			} else if(data.state == "no wifi") {
				 $('#wifi-state').text(i18n.t('wifi.no_wifi'));
			} else if(data.state == "init") {
				 $('#wifi-state').text(i18n.t('wifi.init'));
			} else if(data.state == "sharing") {
				 $('#wifi-state').text(i18n.t('wifi.sharing'));
			} else if(data.state == "unknown") {
				 $('#wifi-state').text(i18n.t('wifi.unknown'));
			} else{
				 $('#wifi-state').text(data.state);
			}            
            
			if(data.link_auth == "none") {
				 $('#wifi-wifiauthtype').text(i18n.t('wifi.none'));
			} else if(data.link_auth == "802.1x") {
				 $('#wifi-wifiauthtype').text(i18n.t('wifi.802.1x'));
			} else if(data.link_auth == "leap") {
				 $('#wifi-wifiauthtype').text(i18n.t('wifi.leap'));
			} else if(data.link_auth == "wps") {
				 $('#wifi-wifiauthtype').text(i18n.t('wifi.wps'));
			} else if(data.link_auth == "wep") {
				 $('#wifi-wifiauthtype').text(i18n.t('wifi.wep'));
			} else if(data.link_auth == "wpa") {
				 $('#wifi-wifiauthtype').text(i18n.t('wifi.wpa'));
			} else if(data.link_auth == "wpa-psk") {
				 $('#wifi-wifiauthtype').text(i18n.t('wifi.wpa_psk'));
			} else if(data.link_auth == "wpa2-psk") {
				 $('#wifi-wifiauthtype').text(i18n.t('wifi.wpa2_psk'));
			} else if(data.link_auth == "wpa2") {
				 $('#wifi-wifiauthtype').text(i18n.t('wifi.wpa2'));
			} else{
				 $('#wifi-wifiauthtype').text(data.link_auth);
			}
            
			if(data.x802_11_auth == "open") {
				 $('#wifi-xauthtype').text(i18n.t('wifi.open'));
			} else{
				 $('#wifi-xauthtype').text(data.x802_11_auth);
			}
            
			if(data.op_mode == "station ") {
				 $('#wifi-apmode').text(i18n.t('wifi.station'));
			} else{
				 $('#wifi-apmode').text(data.op_mode);
			}
            
			$('#wifi-snr').html('<span title="'+i18n.t('wifi.snr_detail')+'">'+(data.agrctlrssi - data.agrctlnoise)+' db</span>');

		}

	});

});

</script>