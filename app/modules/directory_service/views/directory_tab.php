	<h2 data-i18n="directory_service.clienttabtitle"></h2>

	<div id="directoryservice-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

	<div id="directoryservice-view" class="row hide">
		<div class="col-md-6">
			<table class="table table-striped">
				<tr>
					<th data-i18n="directory_service.which_directory_service"></th>
					<td id="directoryservice-which_directory_service"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.directory_service_comments"></th>
					<td id="directoryservice-directory_service_comments"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.adforest"></th>
					<td id="directoryservice-adforest"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.addomain"></th>
					<td id="directoryservice-addomain"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.computeraccount"></th>
					<td id="directoryservice-computeraccount"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.createmobileaccount"></th>
					<td id="directoryservice-createmobileaccount"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.requireconfirmation"></th>
					<td id="directoryservice-requireconfirmation"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.forcehomeinstartup"></th>
					<td id="directoryservice-forcehomeinstartup"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.mounthomeassharepoint"></th>
					<td id="directoryservice-mounthomeassharepoint"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.usewindowsuncpathforhome"></th>
					<td id="directoryservice-usewindowsuncpathforhome"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.networkprotocoltobeused"></th>
					<td id="directoryservice-networkprotocoltobeused"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.defaultusershell"></th>
					<td id="directoryservice-defaultusershell"></td>
				</tr>
                <tr>
					<th data-i18n="directory_service.mappinguidtoattribute"></th>
					<td id="directoryservice-mappinguidtoattribute"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.mappingusergidtoattribute"></th>
					<td id="directoryservice-mappingusergidtoattribute"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.mappinggroupgidtoattr"></th>
					<td id="directoryservice-mappinggroupgidtoattr"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.generatekerberosauth"></th>
					<td id="directoryservice-generatekerberosauth"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.preferreddomaincontroller"></th>
					<td id="directoryservice-preferreddomaincontroller"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.allowedadmingroups"></th>
					<td id="directoryservice-allowedadmingroups"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.authenticationfromanydomain"></th>
					<td id="directoryservice-authenticationfromanydomain"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.packetsigning"></th>
					<td id="directoryservice-packetsigning"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.packetencryption"></th>
					<td id="directoryservice-packetencryption"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.passwordchangeinterval"></th>
					<td id="directoryservice-passwordchangeinterval"></td>
				</tr>
                <tr>
					<th data-i18n="directory_service.restrictdynamicdnsupdates"></th>
					<td id="directoryservice-restrictdynamicdnsupdates"></td>
				</tr>
				<tr>
					<th data-i18n="directory_service.namespacemode"></th>
					<td id="directoryservice-namespacemode"></td>
				</tr>
			</table>
		</div>
		<div class="col-md-6">
		</div>
	</div>

<script>
$(document).on('appReady', function(e, lang) {

	// Get directory_service data
	$.getJSON( appUrl + '/module/directory_service/get_data/' + serialNumber, function( data ) {
		if( ! data.addomain){
			$('#directoryservice-msg').text(i18n.t('no_data'));
		}
		else{

			// Hide
			$('#directoryservice-msg').text('');
			$('#directoryservice-view').removeClass('hide');

            // Add strings
			$('#directoryservice-which_directory_service').text(data.which_directory_service);
			$('#directoryservice-directory_service_comments').text(data.directory_service_comments);
			$('#directoryservice-adforest').text(data.adforest);
			$('#directoryservice-addomain').text(data.addomain);
			$('#directoryservice-computeraccount').text(data.computeraccount);
			$('#directoryservice-createmobileaccount').text(data.createmobileaccount);
			$('#directoryservice-requireconfirmation').text(data.requireconfirmation);
			$('#directoryservice-forcehomeinstartup').text(data.forcehomeinstartup);
			$('#directoryservice-mounthomeassharepoint').text(data.mounthomeassharepoint);
			$('#directoryservice-usewindowsuncpathforhome').text(data.usewindowsuncpathforhome);
			$('#directoryservice-networkprotocoltobeused').text(data.networkprotocoltobeused);
			$('#directoryservice-defaultusershell').text(data.defaultusershell);
			$('#directoryservice-mappinguidtoattribute').text(data.mappinguidtoattribute);
			$('#directoryservice-mappingusergidtoattribute').text(data.mappingusergidtoattribute);
			$('#directoryservice-mappinggroupgidtoattr').text(data.mappinggroupgidtoattr);
			$('#directoryservice-generatekerberosauth').text(data.generatekerberosauth);
			$('#directoryservice-preferreddomaincontroller').text(data.preferreddomaincontroller);
			$('#directoryservice-allowedadmingroups').text(data.allowedadmingroups);
			$('#directoryservice-authenticationfromanydomain').text(data.authenticationfromanydomain);
			$('#directoryservice-packetsigning').text(data.packetsigning);
			$('#directoryservice-passwordchangeinterval').text(data.passwordchangeinterval);
			$('#directoryservice-restrictdynamicdnsupdates').text(data.restrictdynamicdnsupdates);
			$('#directoryservice-namespacemode').text(data.namespacemode);

			if(data.createmobileaccount === "1" || data.createmobileaccount === 1) {
				 $('#directoryservice-createmobileaccount').text(i18n.t('yes'));
			} else if(data.createmobileaccount === "0" || data.createmobileaccount === 0) {
				 $('#directoryservice-createmobileaccount').text(i18n.t('no'));
			} else{
				 $('#directoryservice-createmobileaccount').text("");
			}

			if(data.requireconfirmation === "1" || data.requireconfirmation === 1) {
				 $('#directoryservice-requireconfirmation').text(i18n.t('yes'));
			} else if(data.requireconfirmation === "0" || data.requireconfirmation === 0) {
				 $('#directoryservice-requireconfirmation').text(i18n.t('no'));
			} else{
				 $('#directoryservice-requireconfirmation').text("");
			}

			if(data.forcehomeinstartup === "1" || data.forcehomeinstartup === 1) {
				 $('#directoryservice-forcehomeinstartup').text(i18n.t('yes'));
			} else if(data.forcehomeinstartup === "0" || data.forcehomeinstartup === 0) {
				 $('#directoryservice-forcehomeinstartup').text(i18n.t('no'));
			} else{
				 $('#directoryservice-forcehomeinstartup').text("");
			}

			if(data.mounthomeassharepoint === "1" || data.mounthomeassharepoint === 1) {
				 $('#directoryservice-mounthomeassharepoint').text(i18n.t('yes'));
			} else if(data.mounthomeassharepoint === "0" || data.mounthomeassharepoint === 0) {
				 $('#directoryservice-mounthomeassharepoint').text(i18n.t('no'));
			} else{
				 $('#directoryservice-mounthomeassharepoint').text("");
			}

			if(data.usewindowsuncpathforhome === "1" || data.usewindowsuncpathforhome === 1) {
				 $('#directoryservice-usewindowsuncpathforhome').text(i18n.t('yes'));
			} else if(data.usewindowsuncpathforhome === "0" || data.usewindowsuncpathforhome === 0) {
				 $('#directoryservice-usewindowsuncpathforhome').text(i18n.t('no'));
			} else{
				 $('#directoryservice-usewindowsuncpathforhome').text("");
			}

			if(data.generatekerberosauth === "1" || data.generatekerberosauth === 1) {
				 $('#directoryservice-generatekerberosauth').text(i18n.t('yes'));
			} else if(data.generatekerberosauth === "0" || data.generatekerberosauth === 0) {
				 $('#directoryservice-generatekerberosauth').text(i18n.t('no'));
			} else{
				 $('#directoryservice-generatekerberosauth').text("");
			}

			if(data.authenticationfromanydomain === "1" || data.authenticationfromanydomain === 1) {
				 $('#directoryservice-authenticationfromanydomain').text(i18n.t('yes'));
			} else if(data.authenticationfromanydomain === "0" || data.authenticationfromanydomain === 0) {
				 $('#directoryservice-authenticationfromanydomain').text(i18n.t('no'));
			} else{
				 $('#directoryservice-authenticationfromanydomain').text("");
			}
		}

	});

});

</script>
