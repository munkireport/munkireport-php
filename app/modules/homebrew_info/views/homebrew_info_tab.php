<h2 data-i18n="homebrew_info.clienttitle"></h2>
	
<div id="homebrew_info-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>
	
	<div id="homebrew_info-view" class="row hide">
		<div class="col-md-7">
			<table class="table table-striped">
				<tr>
					<th data-i18n="homebrew_info.core_tap_head"></th>
					<td id="homebrew_info-core_tap_head"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.core_tap_origin"></th>
					<td id="homebrew_info-core_tap_origin"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.core_tap_last_commit"></th>
					<td id="homebrew_info-core_tap_last_commit"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.head"></th>
					<td id="homebrew_info-head"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.last_commit"></th>
					<td id="homebrew_info-last_commit"></td>
				</tr>	
				<tr>
					<th data-i18n="homebrew_info.origin"></th>
					<td id="homebrew_info-origin"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.homebrew_bottle_domain"></th>
					<td id="homebrew_info-homebrew_bottle_domain"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.homebrew_cellar"></th>
					<td id="homebrew_info-homebrew_cellar"></td>
				</tr>
                				<tr>
					<th data-i18n="homebrew_info.homebrew_prefix"></th>
					<td id="homebrew_info-homebrew_prefix"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.homebrew_repository"></th>
					<td id="homebrew_info-homebrew_repository"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.homebrew_git_config_file"></th>
					<td id="homebrew_info-homebrew_git_config_file"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.homebrew_version"></th>
					<td id="homebrew_info-homebrew_version"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.homebrew_ruby"></th>
					<td id="homebrew_info-homebrew_ruby"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.homebrew_noanalytics_this_run"></th>
					<td id="homebrew_info-homebrew_noanalytics_this_run"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.command_line_tools"></th>
					<td id="homebrew_info-command_line_tools"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.cpu"></th>
					<td id="homebrew_info-cpu"></td>
				</tr>
                <tr>
					<th data-i18n="homebrew_info.git"></th>
					<td id="homebrew_info-git"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.clang"></th>
					<td id="homebrew_info-clang"></td>
				</tr>
                <tr>
					<th data-i18n="homebrew_info.curl"></th>
					<td id="homebrew_info-curl"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.java"></th>
					<td id="homebrew_info-java"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.perl"></th>
					<td id="homebrew_info-perl"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.python"></th>
					<td id="homebrew_info-python"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.ruby"></th>
					<td id="homebrew_info-ruby"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.x11"></th>
					<td id="homebrew_info-x11"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.xcode"></th>
					<td id="homebrew_info-xcode"></td>
				</tr>
				<tr>
					<th data-i18n="homebrew_info.macos"></th>
					<td id="homebrew_info-macos"></td>
				</tr>
			</table>
		</div>
	</div>

<script>
$(document).on('appReady', function(e, lang) {
	
	// Get homebrew_info data
	$.getJSON( appUrl + '/module/homebrew_info/get_data/' + serialNumber, function( data ) {
		if( ! data.homebrew_version){
			$('#homebrew_info-msg').text(i18n.t('no_data'));
		}
		else{
			
			// Hide
			$('#homebrew_info-msg').text('');
			$('#homebrew_info-view').removeClass('hide');
			            
            // Add data
			$('#homebrew_info-core_tap_head').text(data.core_tap_head);
			$('#homebrew_info-core_tap_origin').text(data.core_tap_origin);
			$('#homebrew_info-core_tap_last_commit').text(data.core_tap_last_commit);
			$('#homebrew_info-head').text(data.head);
			$('#homebrew_info-last_commit').text(data.last_commit);
			$('#homebrew_info-origin').text(data.origin);
			$('#homebrew_info-homebrew_bottle_domain').text(data.homebrew_bottle_domain);  
			$('#homebrew_info-homebrew_cellar').text(data.homebrew_cellar);  
			$('#homebrew_info-homebrew_prefix').text(data.homebrew_prefix);  
			$('#homebrew_info-homebrew_repository').text(data.homebrew_repository);  
			$('#homebrew_info-homebrew_version').text(data.homebrew_version);  
			$('#homebrew_info-homebrew_git_config_file').text(data.homebrew_git_config_file);  
			$('#homebrew_info-homebrew_ruby').text(data.homebrew_ruby);  
			$('#homebrew_info-command_line_tools').text(data.command_line_tools);  
			$('#homebrew_info-cpu').text(data.cpu);  
			$('#homebrew_info-curl').text(data.curl);  
			$('#homebrew_info-git').text(data.git);  
			$('#homebrew_info-clang').text(data.clang);  
			$('#homebrew_info-java').text(data.java);  
			$('#homebrew_info-perl').text(data.perl);  
			$('#homebrew_info-python').text(data.python);  
			$('#homebrew_info-ruby').text(data.ruby);  
			$('#homebrew_info-x11').text(data.x11);  
			$('#homebrew_info-xcode').text(data.xcode);  
			$('#homebrew_info-macos').text(data.macos);  
            
            if(data.homebrew_noanalytics_this_run === "1" || data.homebrew_noanalytics_this_run === 1) {
				 $('#homebrew_info-homebrew_noanalytics_this_run').text(i18n.t('yes'));
			} else if(data.homebrew_noanalytics_this_run === "0" || data.homebrew_noanalytics_this_run === 0) {
				 $('#homebrew_info-homebrew_noanalytics_this_run').text(i18n.t('no'));
			} else{
				 $('#homebrew_info-homebrew_noanalytics_this_run').text("");
			}
		}

	});
     
});
    
</script>