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
		if( ! data['dstudio-host-primary-key']){
			$('#ds-msg').text(i18n.t('no_data'));
		}
		else{
			
			// Hide
			$('#ds-msg').text('');
			$('#ds-view').removeClass('hide');
			
			// Add strings
			$('#architecture').text(data['architecture']);
			$('#cn').text(data['cn']);
			$('#dstudio-auto-disable').text(data['dstudio-auto-disable']);
			$('#dstudio-auto-reset-workflow').text(data['dstudio-auto-reset-workflow']);
			$('#dstudio-auto-started-workflow').text(data['dstudio-auto-started-workflow']);
			$('#dstudio-bootcamp-windows-computer-name').text(data['dstudio-bootcamp-windows-computer-name']);
			$('#dstudio-disabled').text(data['dstudio-disabled']);
			$('#dstudio-group').text(data['dstudio-group']);
			$('#dstudio-host-ard-field-1').text(data['dstudio-host-ard-field-1']);
			$('#dstudio-host-ard-field-2').text(data['dstudio-host-ard-field-2']);
			$('#dstudio-host-ard-field-3').text(data['dstudio-host-ard-field-3']);
			$('#dstudio-host-ard-field-4').text(data['dstudio-host-ard-field-4']);
			$('#dstudio-host-ard-ignore-empty-fields').text(data['dstudio-host-ard-ignore-empty-fields']);
			$('#dstudio-host-delete-other-locations').text(data['dstudio-host-delete-other-locations']);
			$('#dstudio-host-model-identifier').text(data['dstudio-host-model-identifier']);
			$('#dstudio-host-new-network-location').text(data['dstudio-host-new-network-location']);
			$('#dstudio-host-serial-number').text(data['dstudio-host-serial-number']);
			$('#dstudio-host-type').text(data['dstudio-host-type']);
			$('#dstudio-hostname').text(data['dstudio-hostname']);
			$('#dstudio-last-workflow').text(data['dstudio-last-workflow']);
			$('#dstudio-last-workflow-duration').text(data['dstudio-last-workflow-duration']);
			$('#dstudio-last-workflow-execution-date').text(data['dstudio-last-workflow-execution-date']);
			$('#dstudio-last-workflow-status').text(data['dstudio-last-workflow-status']);
			$('#dstudio-mac-addr').text(data['dstudio-mac-addr']);
		}

	});
	
});

</script>