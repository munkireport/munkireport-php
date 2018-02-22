
<div id="temps"></div>


<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/fan_temps/get_temp_tab_data/' + serialNumber, function(data){
		var skipThese = ['temperature_unit'];
		$.each(data, function(i,d){
			
			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
					if (d[prop] == null){
					   // Do nothing for nulls to blank them
                    } else if (d[prop] == "true"){
					   rows = rows + '<tr><th>'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } else if (d[prop] == "false"){
					   rows = rows + '<tr><th>'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } else if ((i18n.t('fan_temps.'+prop).length) == 4){
					   // Hide rows that are only the sensor code
                    } else {
					   temperature_f = parseFloat(((d[prop] * 9/5 ) + 32 ).toFixed(2));
					   if (d['temperature_unit'] == "F"){
					        rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</span></th><td><span title="'+d[prop]+'°C">'+temperature_f+'°F</span></td></tr>';
					   } else {
					        rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</span></th><td><span title="'+temperature_f+'°F">'+d[prop]+'°C</span></td></tr>';
					   }
					}
				}
			}
            
			$('#temps-tab')
				.append($('<h2>')
					.append($('<i>')
						.addClass('fa fa-thermometer-three-quarters '))
					.append(' '+i18n.t('fan_temps.tabtitle_temps')))
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
