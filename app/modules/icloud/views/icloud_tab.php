<div id="icloud-tab"></div>
<h2 data-i18n="icloud.icloud"></h2>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/icloud/get_tab_data/' + serialNumber, function(data){
        // Set count of iCloud logins
		$('#icloud-cnt').text(data.length);
		var skipThese = ['id','serial_number'];
		$.each(data, function(i,d){

			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
                    // Do nothing for empty values to blank them
                    if (d[prop] == '' || d[prop] == null){
                        rows = rows

                    // Format enabled/disabled
                    } else if(prop.indexOf('_enabled') > -1 && d[prop] == 0){
                        rows = rows + '<tr><th>'+i18n.t('icloud.'+prop)+'</th><td>'+i18n.t('disabled')+'</td></tr>';
                    } else if(prop.indexOf('_enabled') > -1 && d[prop] == 1){
                        rows = rows + '<tr><th>'+i18n.t('icloud.'+prop)+'</th><td>'+i18n.t('enabled')+'</td></tr>';
                    
                    // Format yes/no
                    } else if((prop == "beta" || prop == "cloud_photo_only_keep_thumbnail" || prop == "clouddesktop_declined_upgrade" || prop == "clouddesktop_first_sync_down_complete" || prop == "family_show_manage_family" || prop == "is_managed_apple_id" || prop == "logged_in" || prop == "notes_dot_mac_mail_supported" || prop == "mail_and_notes_dot_mac_mail_supported" || prop == "primary_email_verified"  || prop == "should_configure" || prop == "imessage_currently_syncing" || prop.indexOf('_beta') > -1) && d[prop] == 0){
                        rows = rows + '<tr><th>'+i18n.t('icloud.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } else if((prop == "beta" || prop == "cloud_photo_only_keep_thumbnail" || prop == "clouddesktop_declined_upgrade" || prop == "clouddesktop_first_sync_down_complete" || prop == "family_show_manage_family" || prop == "is_managed_apple_id" || prop == "logged_in" || prop == "notes_dot_mac_mail_supported" || prop == "mail_and_notes_dot_mac_mail_supported" || prop == "primary_email_verified" || prop == "should_configure" || prop == "imessage_currently_syncing" || prop.indexOf('_beta') > -1) && d[prop] == 1){
                        rows = rows + '<tr><th>'+i18n.t('icloud.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                        
                    // Else, build out rows from devices
                    } else {
                        rows = rows + '<tr><th>'+i18n.t('icloud.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
					}
				}
			}
            
            $('#icloud-tab')
                .append($('<h4>')
                    .append($('<i>')
                        .addClass('fa fa-cloud'))
                    .append(' '+d.account_id))
                .append($('<div style="max-width:600px;">')
                    .append($('<table>')
                        .addClass('table table-striped table-condensed')
                        .append($('<tbody>')
                            .append(rows))))
		})
	});
});
</script>
