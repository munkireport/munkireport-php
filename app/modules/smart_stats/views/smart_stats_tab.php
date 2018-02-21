
<div id="smart_stats"></div>
<h2 data-i18n="smart_stats.clienttabtitle"></h2>


<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/smart_stats/get_client_tab_data/' + serialNumber, function(data){
		var skipThese = ['id','serial_number','disk_number','temperature_unit'];
		$.each(data, function(i,d){
            
			// Set the tab badge to blank
			$('#smart_stats-cnt').html("");
			
			// Generate rows from data
			var rows = ''
            var drive_health = ""
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
                    if (d[prop] == null){
					   // Do nothing for the nulls to blank them
                    } else if (d[prop] == "" && d[prop] != "0"){
					   // Do nothing for the nulls to blank them
                    } else if (d[prop] == "Enabled"){ // Localize enabled
					   rows = rows + '<tr><th>'+i18n.t('smart_stats.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } else if (d[prop] == "Disabled"){ // Localize disabled
					   rows = rows + '<tr><th>'+i18n.t('smart_stats.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } else if (d[prop] == "In smartctl database [for details use: -P show]"){ // Localize if drive is in database
					   rows = rows + '<tr><th>'+i18n.t('smart_stats.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } else if (d[prop] == "Not in smartctl database [for details use: -P showall]"){ // Localize if drive is not in database
					   rows = rows + '<tr><th>'+i18n.t('smart_stats.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } else if (prop == "error_poh" && d[prop] != 0){ // Format SMART Error Power on Hours
					   rows = rows + '<tr><th>'+i18n.t('smart_stats.'+prop)+'</th><td class="danger"><span title="'+Math.round((d[prop]/24), 2)+" "+i18n.t('date.day_plural')+'">'+d[prop]+'</span></td></tr>';
                    } else if (prop == "power_on_hours" || prop == "power_on_hours_nvme"){ // Format Power on Hours
					   rows = rows + '<tr><th>'+i18n.t('smart_stats.'+prop)+'</th><td><span title="'+Math.round((d[prop]/24), 2)+" "+i18n.t('date.day_plural')+'">'+d[prop]+'</span></td></tr>';
                    } else if (prop == "error_count" && d[prop] != 0){ // Format SMART Error count
					   rows = rows + '<tr><th>'+i18n.t('smart_stats.'+prop)+'</th><td class="danger">'+d[prop]+'</td></tr>';
                    } else if (prop == "total_lbas_written" || prop == "total_lbas_read"){ // Format LBAs Read/Written
					   rows = rows + '<tr><th>'+i18n.t('smart_stats.'+prop)+'</th><td><span title="'+fileSize(d[prop] * 512)+'">'+d[prop]+'</span></td></tr>';
                    } else if (prop == "timestamp"){ // Format timestamp
					   var timestamp = (d[prop] * 1000)
					   rows = rows + '<tr><th>'+i18n.t('smart_stats.'+prop)+'</th><td>'+moment(+timestamp).format("YYYY-MM-DD H:mm:ss")+'</td></tr>';
                    } else if (prop == "airflow_temperature_cel" || prop == "temperature_celsius" || prop == "temperature_nvme"){ // Format temperatures
					   temperature_f = parseFloat(((d[prop] * 9/5 ) + 32 ).toFixed(2));
					   if (d['temperature_unit'] == "F"){
					        rows = rows + '<tr><th>'+i18n.t('smart_stats.'+prop)+'</th><td><span title="'+d[prop]+'°C">'+temperature_f+'°F</span></td></tr>';
					   } else {
					        rows = rows + '<tr><th>'+i18n.t('smart_stats.'+prop)+'</th><td><span title="'+temperature_f+'°F">'+d[prop]+'°C</span></td></tr>';
					   }
                    } else if (prop == "overall_health"){
                        if (d['overall_health'] == "PASSED"){
                           var drive_health = " <span class='label label-success'>"+i18n.t('smart_stats.passed')+"</span>"
                        } else if (d['overall_health'] == "UNKNOWN!"){
                            var drive_health = " <span class='label label-warning'>"+i18n.t('unknown')+"</span>"
                        } else if (d['overall_health'] == "FAILED!"){
                           var drive_health = " <span class='label label-danger'>"+i18n.t('failing')+"</span>"
                           // Update the tab badge
                           $('#smart_stats-cnt').html(i18n.t('failing'));
                        } else { var drive_health = d['overall_health'] }
                    } else {
                        rows = rows + '<tr><th>'+i18n.t('smart_stats.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    }
				}
			}
            
            
			$('#smart_stats-tab')
				.append($('<h4>')
					.append($('<i>')
						.addClass('fa fa-hdd-o '))
					.append(" /dev/disk"+d.disk_number+drive_health))
				.append($('<div style="max-width:650px;">')
					.addClass('table-responsive')
					.append($('<table>')
						.addClass('table table-striped table-condensed')
						.append($('<tbody>')
							.append(rows))))
		})
	});
});
</script>

