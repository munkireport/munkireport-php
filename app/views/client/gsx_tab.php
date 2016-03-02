<h2>GSX <a data-i18n="gsx.recheck" class="btn btn-default btn-xs" href="<?php echo url('module/gsx/recheck_gsx/' . $serial_number);?>"></a></h2>
	
	<div id="gsx-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

    <div id="gsx-view" class="row hide">

        <div class="col-md-6">
			<table class="table table-striped">
				<tr>
                	<th data-i18n="warranty.coverage"></th>
					<td id="gsx-warrantystatus"></td>
                </tr>
                <tr>
                	<th data-i18n="gsx.estpurchasedate"></th>
					<td id="gsx-estimatedpurchasedate"></td>
                </tr>
                <tr>
                	<th data-i18n="reg_date"></th>
					<td id="gsx-registrationdate"></td>
                </tr>
                <tr>
                	<th data-i18n="gsx.warrantydays"></th>
					<td id="gsx-daysremaining"></td>
                </tr>
                <tr>
                	<th data-i18n="gsx.coverage.start"></th>
					<td id="gsx-coveragestartdate"></td>
                </tr>
                <tr>
                	<th data-i18n="gsx.coverage.end"></th>
					<td id="gsx-coverageenddate"></td>
                </tr>
                <tr>
                	<th data-i18n="gsx.coverage.constart"></th>
					<td id="gsx-contractcoveragestartdate"></td>
                </tr>
                <tr>
                	<th data-i18n="gsx.coverage.conend"></th>
					<td id="gsx-contractcoverageenddate"></td>
                </tr>
                <tr>
                	<th data-i18n="gsx.contracttype"></th>
					<td id="gsx-contracttype"></td>
                </tr>
                <tr>
                	<th data-i18n="gsx.sla"></th>
					<td id="gsx-warrantyreferenceno"></td>
                </tr>
                <tr>
                	<th data-i18n="gsx.modeldescription"></th>
					<td id="gsx-productdescription"></td>
                </tr>
                <tr>
                	<th data-i18n="gsx.configuration"></th>
					<td id="gsx-configdescription"></td>
                </tr>
                <tr>
                	<th data-i18n="gsx.laborcovered"></th>
					<td id="gsx-laborcovered"></td>
                </tr>
                <tr>
                	<th data-i18n="gsx.partscovered"></th>
					<td id="gsx-partcovered"></td>
                </tr>
                <tr>
                	<th data-i18n="gsx.country"></th>
					<td id="gsx-purchasecountry"></td>
                </tr>
                <tr>
                	<th data-i18n="gsx.loaner"></th>
					<td id="gsx-isloaner"></td>
                </tr>
                <tr>
                	<th data-i18n="gsx.vintage"></th>
					<td id="gsx-isvintage"></td>
                </tr>
                <tr>
                	<th data-i18n="gsx.obsolete"></th>
					<td id="gsx-isobsolete"></td>
                </tr>
            </table>
          </div>
    </div>

<script>
$(document).on('appReady', function(e, lang) {
	
	// Get GSX data
	$.getJSON( appUrl + '/module/gsx/get_data/' + serialNumber, function( data ) {
		if( ! data.warrantystatus){
			$('#gsx-msg').text(i18n.t('no_data'));
		}
		else{
			
			// Hide
			$('#gsx-msg').text('');
			$('#gsx-view').removeClass('hide');
			
			// Add strings
			$('#gsx-warrantystatus').text(data.warrantystatus);
			$('#gsx-coverageenddate').text(data.coverageenddate);
			$('#gsx-coveragestartdate').text(data.coveragestartdate);
			$('#gsx-daysremaining').text(data.daysremaining);
			$('#gsx-estimatedpurchasedate').text(data.estimatedpurchasedate);
			$('#gsx-purchasecountry').text(data.purchasecountry);
			$('#gsx-registrationdate').text(data.registrationdate);
			$('#gsx-productdescription').text(data.productdescription);
			$('#gsx-configdescription').text(data.configdescription);
			$('#gsx-contractcoverageenddate').text(data.contractcoverageenddate);
			$('#gsx-contractcoveragestartdate').text(data.contractcoveragestartdate);
			$('#gsx-contracttype').text(data.contracttype);
			$('#gsx-laborcovered').text(data.laborcovered);
			$('#gsx-partcovered').text(data.partcovered);
			$('#gsx-warrantyreferenceno').text(data.warrantyreferenceno);
			$('#gsx-isloaner').text(data.isloaner);
			$('#gsx-warrantymod').text(data.warrantymod);
			$('#gsx-isvintage').text(data.isvintage);
			$('#gsx-isobsolete').text(data.isobsolete);      
		}

	});
	
});

</script>