<div id="teamviewer"></div>
<h2 data-i18n="teamviewer.teamviewer"></h2>


<script>
$(document).on('appReady', function(e, lang) {
	
	// Get teamviewer data
	$.getJSON( appUrl + '/module/teamviewer/get_data/' + serialNumber, function( data ) {
		var skipThese = ['id','serial_number','midversion','moverestriction','prefpath'];
		$.each(data, function(i,d){
			
			// Generate rows from data
            var connectbutton = ''
			var rows = ''
            var drive_health = ""
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
                    // Do nothing for the nulls to blank them
                    if (d[prop] == null || d[prop] == ""){
                        
                    // Format Yes booleans
                    } else if((prop == 'always_online' || prop == 'had_a_commercial_connection' || prop == 'security_adminrights' || prop == 'update_available' || prop == 'is_not_first_run_without_connection' || prop == 'is_not_running_test_connection') && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('teamviewer.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    // Format No booleans
                    } else if((prop == 'always_online' || prop == 'had_a_commercial_connection' || prop == 'security_adminrights' || prop == 'update_available' || prop == 'is_not_first_run_without_connection' || prop == 'is_not_running_test_connection') && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('teamviewer.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                        
                    // Format returns
                    } else if(prop == 'lastmacused' || prop == 'updateversion'){
					   rows = rows + '<tr><th>'+i18n.t('teamviewer.'+prop)+'</th><td>'+d[prop].replace(/\n/g,'<br>')+'</td></tr>';
                       
                    // Make Connect button
                    } else if(prop == 'clientid') {                        
                        connectbutton = '<a class="btn btn-success" href="teamviewer10://control?device='+d[prop]+'"  target="_blank">'+i18n.t('teamviewer.connect')+'</a>'
                        rows = rows + '<tr><th>'+i18n.t('teamviewer.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                        
                    } else {
                        rows = rows + '<tr><th>'+i18n.t('teamviewer.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    }    
				}
			}
            
            prefpath = d.prefpath.replace('Library/Preferences/com.teamviewer.teamviewer.preferences.Machine.plist','').replace('Preferences/com.teamviewer.teamviewer.preferences.plist','')
            
			$('#teamviewer-tab')
                .append($('<h4>')
					.append(' '+connectbutton)
					.append(' '+prefpath+' '))
				.append($('<div style="max-width:450px;">')
					.append($('<table>')
						.addClass('table table-striped table-condensed')
						.append($('<tbody>')
							.append(rows))))
		})
	});
});
</script>
