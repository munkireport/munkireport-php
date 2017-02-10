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
		if( ! data.mrxprotect){
			$('#softwareupdate-msg').text(i18n.t('no_data'));
		}
		else{
			
			// Hide
			$('#softwareupdate-msg').text('');
			$('#softwareupdate-view').removeClass('hide');
			
			// Add strings
			if(data.automaticcheckenabled === "1"){
				 $('#softwareupdate-automaticcheckenabled').text("Yes");
			} else {
				 $('#softwareupdate-automaticcheckenabled').text("No");
			}
            
			if(data.automaticdownload === "1"){
				 $('#softwareupdate-automaticdownload').text("Yes");
			} else {
				 $('#softwareupdate-automaticdownload').text("No");
			}
            
			if(data.configdatainstall === "1"){
				 $('#softwareupdate-configdatainstall').text("Yes");
			} else {
				 $('#softwareupdate-configdatainstall').text("No");
			}
            
			if(data.criticalupdateinstall === "1"){
				 $('#softwareupdate-criticalupdateinstall').text("Yes");
			} else {
				 $('#softwareupdate-criticalupdateinstall').text("No");
			}
            
			if(data.lastsessionsuccessful === "1"){
				 $('#softwareupdate-lastsessionsuccessful').text("Yes");
			} else {
				 $('#softwareupdate-lastsessionsuccessful').text("No");
			}
            
			if(data.skiplocalcdn === "1"){
				 $('#softwareupdate-skiplocalcdn').text("Yes");
			} else {
				 $('#softwareupdate-skiplocalcdn').text("No");
			}
            
			$('#softwareupdate-lastattemptsystemversion').text(data.lastattemptsystemversion);
			$('#softwareupdate-lastbackgroundccdsuccessfuldate').text(data.lastbackgroundccdsuccessfuldate);
			$('#softwareupdate-lastbackgroundsuccessfuldate').text(data.lastbackgroundsuccessfuldate);
			$('#softwareupdate-lastfullsuccessfuldate').text(data.lastfullsuccessfuldate);  
			$('#softwareupdate-lastrecommendedupdatesavailable').text(data.lastrecommendedupdatesavailable);
			$('#softwareupdate-lastresultcode').text(data.lastresultcode);
			$('#softwareupdate-lastsuccessfuldate').text(data.lastsuccessfuldate);  
			$('#softwareupdate-lastupdatesavailable').text(data.lastupdatesavailable);
			$('#softwareupdate-recommendedupdates').text(data.recommendedupdates);
			$('#softwareupdate-mrxprotect').text(data.mrxprotect);  
			$('#softwareupdate-catalogurl').text(data.catalogurl);  
            
			// Update the tab badge count
			$('#softwareupdate-cnt').text(data.lastupdatesavailable);
		}

	});
     
});
    
</script>