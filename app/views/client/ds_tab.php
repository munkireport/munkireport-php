<h2>DeployStudio <a data-i18n="ds.recheck" class="btn btn-default btn-xs" href="<?php echo url('module/ds/recheck_ds/' . $serial_number);?>"></a></h2>
	
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
                	<th data-i18n="ds.dstudio-host-primary-key"></th>
					<td id="dstudio-host-primary-key"></td>
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
	
	// Get ds data
	$.getJSON( appUrl + '/module/ds/get_data/' + serialNumber, function( data ) {
		if( ! data.warrantystatus){
			$('#ds-msg').text(i18n.t('no_data'));
		}
		else{
			
			// Hide
			$('#ds-msg').text('');
			$('#ds-view').removeClass('hide');
			
			// Add strings
			$('#architecture').text(data.warrantystatus);
			$('#cn').text(data.coverageenddate);
			$('#dstudio-auto-disable').text(data.coveragestartdate);
			$('#dstudio-auto-reset-workflow').text(data.daysremaining);
			$('#dstudio-auto-started-workflow').text(data.estimatedpurchasedate);
			$('#dstudio-bootcamp-windows-computer-name').text(data.purchasecountry);
			$('#dstudio-disabled').text(data.registrationdate);
			$('#dstudio-group').text(data.productdescription);
			$('#dstudio-host-ard-field-1').text(data.configdescription);
			$('#dstudio-host-ard-field-2').text(data.contractcoverageenddate);
			$('#dstudio-host-ard-field-3').text(data.contractcoveragestartdate);
			$('#dstudio-host-ard-field-3').text(data.contracttype);
			$('#dstudio-host-ard-ignore-empty-fields').text(data.laborcovered);
			$('#dstudio-host-delete-other-locations').text(data.partcovered);
			$('#dstudio-host-model-identifier').text(data.warrantyreferenceno);
			$('#dstudio-host-new-network-location').text(data.isloaner);
			$('#dstudio-host-primary-key').text(data.isloaner);
			$('#dstudio-host-serial-number').text(data.isloaner);
			$('#dstudio-host-type').text(data.isloaner);
			$('#dstudio-hostname').text(data.isloaner);
			$('#dstudio-last-workflow').text(data.isloaner);
			$('#dstudio-last-workflow-duration').text(data.isloaner);
			$('#dstudio-last-workflow-execution-date').text(data.isloaner);
			$('#dstudio-last-workflow-status').text(data.warrantymod);
			$('#dstudio-mac-addr').text(data.isvintage);
		}

	});
	
});

</script>