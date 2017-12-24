
<div id="amps"></div>


<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/fan_temps/get_amps_tab_data/' + serialNumber, function(data){
		var skipThese = [];
		$.each(data, function(i,d){
			
			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
                    if (d[prop] == null){
					   // Do nothing for nulls to blank them
                        
                    } else if (prop.startsWith("i")){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' Amps</td></tr>';
                        
                    } else if (prop.startsWith("v")){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' Volts</td></tr>';
                        
                    } else if (prop.startsWith("p")){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' Watts</td></tr>';
                        
                    } else ((i18n.t('fan_temps.'+prop).length) == 4);{
					   // Hide rows that are only the sensor code
                    }
				}
			}
            
			$('#amps-tab')
				.append($('<h2>')
					.append($('<i>')
						.addClass('fa fa-bolt'))
					.append(' '+i18n.t('fan_temps.tabtitle_amps')))
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
