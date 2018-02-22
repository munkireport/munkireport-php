
<div id="fans"></div>


<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/fan_temps/get_fan_tab_data/' + serialNumber, function(data){
		var skipThese = ['fanlabel0','fanlabel1','fanlabel2','fanlabel3','fanlabel4','fanlabel5','fanlabel6','fanlabel7','fanlabel8'];
		$.each(data, function(i,d){
			
			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
                    if (d[prop] == null){
					   // Do nothing for nulls to blank them
                        
                    } else if (prop == "fnfd" && d[prop] > "0"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } else if (prop == "fnfd" && d[prop] == "0"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                        
                    } else if (prop == "mssf" && d[prop] == "1"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } else if (prop == "mssf" && d[prop] == "0"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
              
                    } else if (prop == "fnum"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                        
                    } else if (prop.startsWith("fanmin")){
					   rows = rows + '<tr><th>&nbsp;&nbsp;&nbsp;&nbsp;'+i18n.t('fan_temps.fanmin')+'</th><td>'+d[prop]+' '+i18n.t('fan_temps.rpm')+'</td></tr>';
                    } else if (prop.startsWith("fanmax")){
					   rows = rows + '<tr><th>&nbsp;&nbsp;&nbsp;&nbsp;'+i18n.t('fan_temps.fanmax')+'</th><td>'+d[prop]+' '+i18n.t('fan_temps.rpm')+'</td></tr>';
                    } else if (prop.startsWith("fan_")){
                        if (d[(prop.replace('fan_', 'fanlabel'))]) {
                           rows = rows + '<tr><th>'+d[(prop.replace('fan_', 'fanlabel'))]+' '+i18n.t('fan_temps.fan')+' '+i18n.t('fan_temps.current_speed')+'</th><td>'+d[prop]+' '+i18n.t('fan_temps.rpm')+'</td></tr>';
                        } else {
                           rows = rows + '<tr><th>'+i18n.t('fan_temps.fan')+' '+(prop.replace('fan_', ''))+' '+i18n.t('fan_temps.current_speed')+'</th><td>'+d[prop]+' '+i18n.t('fan_temps.rpm')+'</td></tr>';
                        }
                    } else if (prop == "dbah" || prop == "dbat"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop+'_short')+'</th><td>'+d[prop]+' dBDA</td></tr>';
                        
                    } else if (prop.startsWith("dba")){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">&nbsp;&nbsp;&nbsp;&nbsp;'+i18n.t('fan_temps.noise')+'</th><td>'+d[prop]+' dBA</td></tr>';
                        
                    } else {
                        rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    }
				}
			}
            
			$('#fans-tab')
				.append($('<h2>')
					.append($('<i>')
						.addClass('fa fa-asterisk '))
					.append(' '+i18n.t('fan_temps.tabtitle_fans')))
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
