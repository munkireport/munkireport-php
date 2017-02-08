	<h2 data-i18n="sccm_status.title"></h2>

		<table class="table table-striped">
			<tbody>
				<tr>
					<td data-i18n="sccm_status.enrollment_status"></td>
					<td id="sccm-agent_status"></td>
				</tr>
				<tr>
					<td data-i18n="sccm_status.mgmt_point"></td>
					<td id="sccm-mgmt_point"></td>
				</tr>
				<tr>
					<td data-i18n="sccm_status.enrollment_name"></td>
					<td id="sccm-enrollment_name"></td>
				</tr>
				<tr>
					<td data-i18n="sccm_status.last_checkin"></td>
					<td id="sccm-last_checkin"></td>
				</tr>
				<tr>
					<td data-i18n="sccm_status.cert_exp"></td>
					<td id="sccm-cert_exp"></td>
				</tr>
				<tr>
					<td data-i18n="sccm_status.enrollment_server"></td>
					<td id="sccm-enrollment_server"></td>
				</tr>
			</tbody>
		</table>

<script>
$(document).on('appReady', function(e, lang) {
	
	// Get wifi data
	$.getJSON( appUrl + '/module/sccm_status/get_data/' + serialNumber, function( data ) {
		if( data.agent_status){

			// Add strings
			$('#sccm-agent_status').text(data.agent_status);
			$('#sccm-mgmt_point').text(data.mgmt_point);
			$('#sccm-enrollment_name').text(data.enrollment_name);
			$('#sccm-last_checkin').text(data.last_checkin);
			$('#sccm-cert_exp').text(data.cert_exp);
			$('#sccm-enrollment_server').text(data.enrollment_server);
		}

	});
	
});

</script>
