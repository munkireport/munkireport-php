
<div id="smc"></div>


<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/fan_temps/get_smc_tab_data/' + serialNumber, function(data){
		var skipThese = ['temperature_unit'];
		$.each(data, function(i,d){
			
			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
					if (d[prop] == null){
					   // Do nothing for nulls to blank them
                        
                    } else if ((prop == "aupo" || prop == "mstm" || prop == "spht" || prop == "sght") && d[prop] == "1"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } else if ((prop == "aupo" || prop == "mstm" || prop == "spht" || prop == "sght") && d[prop] == "0"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                        
                    } else if (prop == "discin" && d[prop] == "true"){
					   rows = rows + '<tr><th>'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } else if (prop == "discin" && d[prop] == "false"){
					   rows = rows + '<tr><th>'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                        
                    } else if (prop == "lsof" && d[prop] == "1"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('off')+'</td></tr>';
                    } else if (prop == "lsof" && d[prop] == "0"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('on')+'</td></tr>';
                        
                    } else if (prop == "msld" && d[prop] == "1"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('fan_temps.closed')+'</td></tr>';
                    } else if (prop == "msld" && d[prop] == "0"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('fan_temps.open')+'</td></tr>';
                        
                    } else if (prop == "hdbs" && d[prop] == "1"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('enabled')+'</td></tr>';
                    } else if (prop == "hdbs" && d[prop] == "0"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('disabled')+'</td></tr>';
                        
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "5"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown5')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "3"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown3')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "2"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown2')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "1"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown1')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "0"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown0')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-1"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_1')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-2"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_2')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-3"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_3')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-4"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_4')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-30"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_30')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-40"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_40')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-50"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_50')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-60"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_60')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-61"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_61')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-62"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_62')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-70"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_70')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-71"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_71')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-72"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_teps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_72')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-74"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_74')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-75"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_teps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_75')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-76"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_76')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-78"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_78')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-79"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_79')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-82"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_82')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-83"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_83')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-84"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_84')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-86"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_86')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-95"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_95')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-100"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_100')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-101"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_101')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-102"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_102')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-103"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_103')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-127"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_127')+'</td></tr>';
                    } else if ((prop == "mssd" || prop == "mssp") && d[prop] == "-128"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' - '+i18n.t('fan_temps.shutdown_128')+'</td></tr>';
                        
                    } else if (prop == "alsl"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' lux</td></tr>';
                    } else if (prop == "nati"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+' '+i18n.t('fan_temps.seconds')+'</td></tr>';
                        
                    } else if (prop == "natj" && d[prop] == "0"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('fan_temps.ninja_0')+'</td></tr>';
                    } else if (prop == "natj" && d[prop] == "1"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('fan_temps.ninja_1')+'</td></tr>';
                    } else if (prop == "natj" && d[prop] == "2"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('fan_temps.ninja_2')+'</td></tr>';
                    } else if (prop == "natj" && d[prop] == "3"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('fan_temps.ninja_3')+'</td></tr>';
                    } else if (prop == "natj" && d[prop] == "4"){
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+i18n.t('fan_temps.ninja_4')+'</td></tr>';
                        
                    } else if (prop == "sgtt" || prop == "sctg" || prop == "sgtg" || prop == "shtg" || prop == "sltg" || prop == "sltp" || prop == "sotg" || prop == "sptg" || prop == "slpt" || prop == "slst" || prop == "sppt" || prop == "spst"){
                        temperature_f = parseFloat(((d[prop] * 9/5 ) + 32 ).toFixed(2));
					   if (d['temperature_unit'] == "F"){
					        rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</span></th><td><span title="'+d[prop]+'°C">'+temperature_f+'°F</span></td></tr>';
					   } else {
					        rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</span></th><td><span title="'+temperature_f+'°F">'+d[prop]+'°C</span></td></tr>';
					   }
                    
                    } else {
					   rows = rows + '<tr><th><span title="'+i18n.t('fan_temps.sensorname')+": "+prop+'">'+i18n.t('fan_temps.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    }
				}
			}
            
			$('#smc-tab')
				.append($('<h2>')
					.append($('<i>')
						.addClass('fa fa-microchip '))
					.append(' '+i18n.t('fan_temps.tabtitle_smc')))
				.append($('<div style="max-width:575px;">')
					.addClass('table-responsive')
					.append($('<table>')
						.addClass('table table-striped table-condensed')
						.append($('<tbody>')
							.append(rows))))
		})
	});
});
</script>
