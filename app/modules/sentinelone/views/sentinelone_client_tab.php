	<h2 data-i18n="sentinelone.client_tab"></h2>

	<div id="sentinelone-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

	<div id="sentinelone-view" class="row hide">
		<div class="col-md-6">
			<table class="table table-striped">
				<tr>
					<th data-i18n="sentinelone.threats_present"></th>
					<td id="sentinelone-active_threats_present"></td>
				</tr>
				<tr>
					<th data-i18n="sentinelone.agent_id"></th>
					<td id="sentinelone-agent_id"></td>
				</tr>
				<tr>
					<th data-i18n="sentinelone.agent_install_time"></th>
					<td id="sentinelone-agent_install_time"></td>
				</tr>
				<tr>
					<th data-i18n="sentinelone.agent_running"></th>
					<td id="sentinelone-agent_running"></td>
				</tr>
				<tr>
					<th data-i18n="sentinelone.agent_version"></th>
					<td id="sentinelone-agent_version"></td>
				</tr>
				<tr>
					<th data-i18n="sentinelone.enforcing_security"></th>
					<td id="sentinelone-enforcing_security"></td>
				</tr>
				<tr>
					<th data-i18n="sentinelone.last_seen"></th>
					<td id="sentinelone-last_seen"></td>
				</tr>
				<tr>
					<th data-i18n="sentinelone.mgmt_url"></th>
					<td id="sentinelone-mgmt_url"></td>
				</tr>
				<tr>
					<th data-i18n="sentinelone.self_protection_enabled"></th>
					<td id="sentinelone-self_protection_enabled"></td>
				</tr>
			</table>
		</div>
		<div class="col-md-6">
		</div>
	</div>

<script>
$(document).on('appReady', function(e, lang) {

	// Get sentinelone data
	$.getJSON( appUrl + '/module/sentinelone/get_data/' + serialNumber, function( data ) {
			// Hide
			$('#sentinelone-msg').text('');
			$('#sentinelone-view').removeClass('hide');

            // Add strings
			$('#sentinelone-active_threats_present').text(data.active_threats_present);
			$('#sentinelone-agent_id').text(data.agent_id);
			$('#sentinelone-agent_install_time').text(data.agent_install_time);
			$('#sentinelone-agent_running').text(data.agent_running);
			$('#sentinelone-agent_version').text(data.agent_version);
			$('#sentinelone-enforcing_security').text(data.enforcing_security);
			$('#sentinelone-last_seen').text(data.last_seen);
			$('#sentinelone-mgmt_url').text(data.mgmt_url);
			$('#sentinelone-self_protection_enabled').text(data.self_protection_enabled);

			if(data.last_seen) {
				// Format date
				var last_seen = parseInt($(data.last_seen));
				var date = new Date(0);
				new_date = date.setUTCSeconds(last_seen);
				$('#sentinelone-last_seen').text(i18n.t(new_date));
			}

		});
});

</script>
