
<div id="fan_temps"></div>


<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/fan_temps/get_client_tab_data/' + serialNumber, function(data){
		var skipThese = ['id','serial_number','temperature_unit','fanlable0','fanlable1','fanlable2','fanlable3','fanlable4','fanlable5','fanlable6','fanlable7','fanlable8'];
		$.each(data, function(i,d){
			
			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
					if(prop.indexOf('fan_') > -1){
					   if (d[prop] != -9876540){
					        rows = rows + '<tr><th>'+d[(prop.replace('fan_', 'fanlable'))]+' '+i18n.t('fans.fan')+'</th><td>'+d[prop]+' '+i18n.t('fans.rpm')+'</td></tr>';
					   }
                    } else if (d[prop] == -9876540){
					   // Do nothing for the fake nulls to blank them
                    } else if (d[prop] == "true"){
					   rows = rows + '<tr><th>'+i18n.t('fans.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } else if (d[prop] == "false"){
					   rows = rows + '<tr><th>'+i18n.t('fans.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } else if ((i18n.t('fans.'+prop).length) == 4){
					   // Hide rows that are only the sensor code
                    } else {
					   temperature_f = ((d[prop] * 9/5 ) + 32 ).toFixed(2);
					   if (d['temperature_unit'] == "F"){
					        rows = rows + '<tr><th>'+i18n.t('fans.'+prop)+'</th><td><span title="'+d[prop]+'°C">'+temperature_f+'°F</span></td></tr>';
					   } else {
					        rows = rows + '<tr><th>'+i18n.t('fans.'+prop)+'</th><td><span title="'+temperature_f+'°F">'+d[prop]+'°C</span></td></tr>';
					   }
					}
				}
			}
            
			$('#fan_temps-tab')
				.append($('<h2>')
					.append($('<i>')
						.addClass('fa fa-thermometer-three-quarters '))
					.append(' '+i18n.t('fans.tabtitle')))
				.append($('<div style="max-width:370px;">')
					.addClass('table-responsive')
					.append($('<table>')
						.addClass('table table-striped table-condensed')
						.append($('<tbody>')
							.append(rows))))
		})
	});
});
</script>
