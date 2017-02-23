<h2 data-i18n="munkireportinfo.clienttabtitle"></h2>
	
<div id="munkireportinfo-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>
	
	<div id="munkireportinfo-view" class="row hide">
		<div class="col-md-6">
			<table class="table table-striped">
				<tr>
					<th data-i18n="munkireportinfo.baseurl"></th>
					<td id="munkireportinfo-baseurl"></td>
				</tr>
				<tr>
					<th data-i18n="munkireportinfo.version"></th>
					<td id="munkireportinfo-version"></td>
				</tr>
				<tr>
					<th data-i18n="munkireportinfo.passphrase"></th>
					<td id="munkireportinfo-passphrase"></td>
				</tr>
				<tr>
					<th data-i18n="munkireportinfo.reportitems"></th>
					<td id="munkireportinfo-reportitems"></td>
				</tr>
			</table>
		</div>
	</div>

<script>
$(document).on('appReady', function(e, lang) {
	
	// Get munkireportinfo data
	$.getJSON( appUrl + '/module/munkireportinfo/get_data/' + serialNumber, function( data ) {
		if( ! data.version){
			$('#munkireportinfo-msg').text(i18n.t('no_data'));
		}
		else{
			
			// Hide
			$('#munkireportinfo-msg').text('');
			$('#munkireportinfo-view').removeClass('hide');
			            
			// Add strings
			$('#munkireportinfo-baseurl').text(data.baseurl);
			$('#munkireportinfo-passphrase').text(data.passphrase);
			$('#munkireportinfo-reportitems').text(data.reportitems); 
            
            // Format version
            if (data.version != "-9876543" && (data.version)) {
			$('#munkireportinfo-version').text(mr.integerToVersion(data.version));
            } else {
	        	$('#munkireportinfo-version').html('');  
            }
		}

	});
	
});

</script>