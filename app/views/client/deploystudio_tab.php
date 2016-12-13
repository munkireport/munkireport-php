<h2>DeployStudio <a data-i18n="ds.recheck" class="btn btn-default btn-xs" href="<?php echo url('module/deploystudio/recheck_deploystudio/' . $serial_number);?>"></a></h2>
	
	<div id="ds-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

    <div id="ds-view" class="row hide">

        <div class="col-md-6">
			<table class="table table-striped">
                <tr>
                	<th data-i18n="ds.dstudio-last-workflow"></th>
					<td id="dstudio-last-workflow"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.dstudio-last-workflow-duration"></th>
					<td id="dstudio-last-workflow-duration"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.dstudio-last-workflow-execution-date"></th>
					<td id="dstudio-last-workflow-execution-date"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.dstudio-last-workflow-status"></th>
					<td id="dstudio-last-workflow-status"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.dstudio-disabled"></th>
					<td id="dstudio-disabled"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.dstudio-group"></th>
					<td id="dstudio-group"></td>
                </tr>    
                <tr>
                	<th data-i18n="ds.dstudio-host-ard-field-1"></th>
					<td id="dstudio-host-ard-field-1"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.dstudio-host-ard-field-2"></th>
					<td id="dstudio-host-ard-field-2"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.dstudio-host-ard-field-3"></th>
					<td id="dstudio-host-ard-field-3"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.dstudio-host-ard-field-4"></th>
					<td id="dstudio-host-ard-field-4"></td>
                </tr>  
				<tr>
                	<th data-i18n="ds.computername"></th>
					<td id="cn"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.architecture"></th>
					<td id="architecture"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.dstudio-auto-disable"></th>
					<td id="dstudio-auto-disable"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.dstudio-auto-reset-workflow"></th>
					<td id="dstudio-auto-reset-workflow"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.dstudio-auto-started-workflow"></th>
					<td id="dstudio-auto-started-workflow"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.dstudio-bootcamp-windows-computer-name"></th>
					<td id="dstudio-bootcamp-windows-computer-name"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.dstudio-host-ard-ignore-empty-fields"></th>
					<td id="dstudio-host-ard-ignore-empty-fields"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.dstudio-host-delete-other-locations"></th>
					<td id="dstudio-host-delete-other-locations"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.dstudio-host-model-identifier"></th>
					<td id="dstudio-host-model-identifier"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.dstudio-host-new-network-location"></th>
					<td id="dstudio-host-new-network-location"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.dstudio-host-serial-number"></th>
					<td id="dstudio-host-serial-number"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.dstudio-host-type"></th>
					<td id="dstudio-host-type"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.dstudio-hostname"></th>
					<td id="dstudio-hostname"></td>
                </tr>
                <tr>
                	<th data-i18n="ds.dstudio-mac-addr"></th>
					<td id="dstudio-mac-addr"></td>
                </tr>
            </table>
          </div>
    </div>

<script>
$(document).on('appReady', function(e, lang) {
	
	// Get deploystudio data
	$.getJSON( appUrl + '/module/deploystudio/get_data/' + serialNumber, function( data ) {
		if( ! data['dstudio_host_primary_key']){
			$('#ds-msg').text(i18n.t('no_data'));
		}
		else{
			
			// Hide
			$('#ds-msg').text('');
			$('#ds-view').removeClass('hide');
			
			// Add strings
			$('#architecture').text(data['architecture']);
			$('#cn').text(data['cn']);
			$('#dstudio-auto-disable').text(data['dstudio_auto_disable']);
			$('#dstudio-auto-reset-workflow').text(data['dstudio_auto_reset_workflow']);
			$('#dstudio-auto-started-workflow').text(data['dstudio_auto_started_workflow']);
			$('#dstudio-bootcamp-windows-computer-name').text(data['dstudio_bootcamp_windows_computer_name']);
			$('#dstudio-disabled').text(data['dstudio_disabled']);
			$('#dstudio-group').text(data['dstudio_group']);
			$('#dstudio-host-ard-field-1').text(data['dstudio_host_ard_field_1']);
			$('#dstudio-host-ard-field-2').text(data['dstudio_host_ard_field_2']);
			$('#dstudio-host-ard-field-3').text(data['dstudio_host_ard_field_3']);
			$('#dstudio-host-ard-field-4').text(data['dstudio_host_ard_field_4']);
			$('#dstudio-host-ard-ignore-empty-fields').text(data['dstudio_host_ard_ignore_empty_fields']);
			$('#dstudio-host-delete-other-locations').text(data['dstudio_host_delete_other_locations']);
			$('#dstudio-host-model-identifier').text(data['dstudio_host_model_identifier']);
			$('#dstudio-host-new-network-location').text(data['dstudio_host_new_network_location']);
			$('#dstudio-host-serial-number').text(data['dstudio_host_serial_number']);
			$('#dstudio-host-type').text(data['dstudio_host_type']);
			$('#dstudio-hostname').text(data['dstudio_hostname']);
			$('#dstudio-last-workflow').text(data['dstudio_last_workflow']);
			$('#dstudio-last-workflow-duration').text(data['dstudio_last_workflow_duration']);
			$('#dstudio-last-workflow-execution-date').text(data['dstudio_last_workflow_execution_date']);
			$('#dstudio-last-workflow-status').text(data['dstudio_last_workflow_status']);
			$('#dstudio-mac-addr').text(data['dstudio_mac_addr']);
		}

	});
	
});

</script>