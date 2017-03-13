<h2 data-i18n="softwareupdate.clienttabtitle"></h2>
	
<div id="softwareupdate-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>
	
	<div id="softwareupdate-view" class="row hide">
		<div class="col-md-6">
			<table class="table table-striped">
				<tr>
					<th data-i18n="softwareupdate.automaticcheckenabled"></th>
					<td id="softwareupdate-automaticcheckenabled"></td>
				</tr>
				<tr>
					<th data-i18n="softwareupdate.automaticdownload"></th>
					<td id="softwareupdate-automaticdownload"></td>
				</tr>
				<tr>
					<th data-i18n="softwareupdate.configdatainstall"></th>
					<td id="softwareupdate-configdatainstall"></td>
				</tr>
				<tr>
					<th data-i18n="softwareupdate.criticalupdateinstall"></th>
					<td id="softwareupdate-criticalupdateinstall"></td>
				</tr>
				<tr>
					<th data-i18n="softwareupdate.mrxprotect"></th>
					<td id="softwareupdate-mrxprotect"></td>
				</tr>	
				<tr>
					<th data-i18n="softwareupdate.lastbackgroundccdsuccessfuldate"></th>
					<td id="softwareupdate-lastbackgroundccdsuccessfuldate"></td>
				</tr>
				<tr>
					<th data-i18n="softwareupdate.lastbackgroundsuccessfuldate"></th>
					<td id="softwareupdate-lastbackgroundsuccessfuldate"></td>
				</tr>
				<tr>
					<th data-i18n="softwareupdate.lastfullsuccessfuldate"></th>
					<td id="softwareupdate-lastfullsuccessfuldate"></td>
				</tr>
                				<tr>
					<th data-i18n="softwareupdate.lastsuccessfuldate"></th>
					<td id="softwareupdate-lastsuccessfuldate"></td>
				</tr>
				<tr>
					<th data-i18n="softwareupdate.lastresultcode"></th>
					<td id="softwareupdate-lastresultcode"></td>
				</tr>
				<tr>
					<th data-i18n="softwareupdate.lastsessionsuccessful"></th>
					<td id="softwareupdate-lastsessionsuccessful"></td>
				</tr>
				<tr>
					<th data-i18n="softwareupdate.lastrecommendedupdatesavailable"></th>
					<td id="softwareupdate-lastrecommendedupdatesavailable"></td>
				</tr>
				<tr>
					<th data-i18n="softwareupdate.lastupdatesavailable"></th>
					<td id="softwareupdate-lastupdatesavailable"></td>
				</tr>
				<tr>
					<th data-i18n="softwareupdate.recommendedupdates"></th>
					<td id="softwareupdate-recommendedupdates"></td>
				</tr>
                <tr>
					<th data-i18n="softwareupdate.inactiveupdates"></th>
					<td id="softwareupdate-inactiveupdates"></td>
				</tr>
				<tr>
					<th data-i18n="softwareupdate.skiplocalcdn"></th>
					<td id="softwareupdate-skiplocalcdn"></td>
				</tr>
				<tr>
					<th data-i18n="softwareupdate.lastattemptsystemversion"></th>
					<td id="softwareupdate-lastattemptsystemversion"></td>
				</tr>
				<tr>
					<th data-i18n="softwareupdate.catalogurl"></th>
					<td id="softwareupdate-catalogurl"></td>
				</tr>
			</table>
		</div>
	</div>

<script>
$(document).on('appReady', function(e, lang) {
	
	// Get softwareupdate data
	$.getJSON( appUrl + '/module/softwareupdate/get_data/' + serialNumber, function( data ) {
		if( ! data.lastsuccessfuldate){
			$('#softwareupdate-msg').text(i18n.t('no_data'));
		}
		else{
			
			// Hide
			$('#softwareupdate-msg').text('');
			$('#softwareupdate-view').removeClass('hide');
			
			// Add strings
			if(data.automaticcheckenabled === "1" || data.automaticcheckenabled === 1) {
				 $('#softwareupdate-automaticcheckenabled').text(i18n.t('yes'));
			} else if(data.automaticcheckenabled === "0" || data.automaticcheckenabled === 0) {
				 $('#softwareupdate-automaticcheckenabled').text(i18n.t('no'));
			} else{
				 $('#softwareupdate-automaticcheckenabled').text("");
			}
            
			if(data.automaticdownload === "1" || data.automaticdownload === 1) {
				 $('#softwareupdate-automaticdownload').text(i18n.t('yes'));
			} else if(data.automaticdownload === "0" || data.automaticdownload === 0) {
				 $('#softwareupdate-automaticdownload').text(i18n.t('no'));
			} else{
				 $('#softwareupdate-automaticdownload').text("");
			}
            
			if(data.configdatainstall === "1" || data.configdatainstall === 1) {
				 $('#softwareupdate-configdatainstall').text(i18n.t('yes'));
			} else if(data.configdatainstall === "0" || data.configdatainstall === 0) {
				 $('#softwareupdate-configdatainstall').text(i18n.t('no'));
			} else{
				 $('#softwareupdate-configdatainstall').text("");
			}
            
			if(data.criticalupdateinstall === "1" || data.criticalupdateinstall === 1) {
				 $('#softwareupdate-criticalupdateinstall').text(i18n.t('yes'));
			} else if(data.criticalupdateinstall === "0" || data.criticalupdateinstall === 0) {
				 $('#softwareupdate-criticalupdateinstall').text(i18n.t('no'));
			} else{
				 $('#softwareupdate-criticalupdateinstall').text("");
			}
            
			if(data.lastsessionsuccessful === "1" || data.lastsessionsuccessful === 1) {
				 $('#softwareupdate-lastsessionsuccessful').text(i18n.t('yes'));
			} else if(data.lastsessionsuccessful === "0" || data.lastsessionsuccessful === 0) {
				 $('#softwareupdate-lastsessionsuccessful').text(i18n.t('no'));
			} else{
				 $('#softwareupdate-lastsessionsuccessful').text("");
			}
            
			if(data.skiplocalcdn === "1" || data.skiplocalcdn === 1) {
				 $('#softwareupdate-skiplocalcdn').text(i18n.t('yes'));
			} else if(data.skiplocalcdn === "0" || data.skiplocalcdn === 0) {
				 $('#softwareupdate-skiplocalcdn').text(i18n.t('no'));
			} else{
				 $('#softwareupdate-skiplocalcdn').text("");
			}
            
            // Formate dates
            if(data.lastsuccessfuldate !== "" ){
	        	$('#softwareupdate-lastsuccessfuldate').html('<span title="' + data.lastsuccessfuldate + '">' + moment(data.lastsuccessfuldate).fromNow());
            }
            if(data.lastbackgroundccdsuccessfuldate !== "" ){
	        	$('#softwareupdate-lastbackgroundccdsuccessfuldate').html('<span title="' + data.lastbackgroundccdsuccessfuldate + '">' + moment(data.lastbackgroundccdsuccessfuldate).fromNow());
            }
            if(data.lastbackgroundsuccessfuldate !== "" ){
	        	$('#softwareupdate-lastbackgroundsuccessfuldate').html('<span title="' + data.lastbackgroundsuccessfuldate + '">' + moment(data.lastbackgroundsuccessfuldate).fromNow());
            }
            if(data.lastfullsuccessfuldate !== "" ){
	        	$('#softwareupdate-lastfullsuccessfuldate').html('<span title="' + data.lastfullsuccessfuldate + '">' + moment(data.lastfullsuccessfuldate).fromNow());
            }
            if(data.mrxprotect !== "" ){
	        	$('#softwareupdate-mrxprotect').html('<span title="' + data.mrxprotect + '">' + moment(data.mrxprotect).fromNow());
            }
            
            // Add data
			$('#softwareupdate-lastattemptsystemversion').text(data.lastattemptsystemversion);
			$('#softwareupdate-lastrecommendedupdatesavailable').text(data.lastrecommendedupdatesavailable);
			$('#softwareupdate-lastresultcode').text(data.lastresultcode);
			$('#softwareupdate-lastupdatesavailable').text(data.lastupdatesavailable);
			$('#softwareupdate-recommendedupdates').text(data.recommendedupdates);
			$('#softwareupdate-inactiveupdates').text(data.inactiveupdates);
			$('#softwareupdate-catalogurl').text(data.catalogurl);  
            
			// Update the tab badge count
			$('#softwareupdate-cnt').text(data.lastupdatesavailable);
		}

	});
     
});
    
</script>