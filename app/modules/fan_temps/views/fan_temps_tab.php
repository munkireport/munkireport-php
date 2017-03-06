
<div id="fan_temps"></div>


<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/fan_temps/get_client_tab_data/' + serialNumber, function(data){
		var skipThese = ['id','serial_number','temperature_unit','fanlabel0','fanlabel1','fanlabel2','fanlabel3','fanlabel4','fanlabel5','fanlabel6','fanlabel7','fanlabel8','fanmin0','fanmin1','fanmin2','fanmin3','fanmin4','fanmin5','fanmin6','fanmin7','fanmin8','fanmax0','fanmax1','fanmax2','fanmax3','fanmax4','fanmax5','fanmax6','fanmax7','fanmax8'];
		$.each(data, function(i,d){
			
			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
					if(prop.indexOf('fan_') > -1){
					   if (d[prop] != -9876540){
					        rows = rows + '<tr><th>'+d[(prop.replace('fan_', 'fanlabel'))]+' '+i18n.t('fans.fan')+'</th><td><span title="'+i18n.t('fans.minfan')+': '+d[(prop.replace('fan_', 'fanmin'))]+' '+i18n.t('fans.rpm')+'\x0A'+i18n.t('fans.maxfan')+': '+d[(prop.replace('fan_', 'fanmax'))]+' '+i18n.t('fans.rpm')+'">'+d[prop]+' '+i18n.t('fans.rpm')+'</span></td></tr>';
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
					   temperature_f = parseFloat(((d[prop] * 9/5 ) + 32 ).toFixed(2));
					   if (d['temperature_unit'] == "F"){
					        rows = rows + '<tr><th><span title="'+i18n.t('fans.sensorname')+": "+prop+'">'+i18n.t('fans.'+prop)+'</span></th><td><span title="'+d[prop]+'°C">'+temperature_f+'°F</span></td></tr>';
					   } else {
					        rows = rows + '<tr><th><span title="'+i18n.t('fans.sensorname')+": "+prop+'">'+i18n.t('fans.'+prop)+'</span></th><td><span title="'+temperature_f+'°F">'+d[prop]+'°C</span></td></tr>';
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
