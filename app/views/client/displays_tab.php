
<div id="displays-tab"></div>
<h2 data-i18n="displays.displays"></h2>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/displays_info/get_data/' + serialNumber, function(data){
		// Set count of displays
		$('#displays-cnt').text(data.length);
		var skipThese = ['id', 'serial_number', 'model'];
		$.each(data, function(i,d){
			
			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
                    if(prop == 'type' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>'+i18n.t('displays.internal')+'</td></tr>';
                    }
					else if(prop == 'type' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>'+i18n.t('displays.external')+'</td></tr>';
                    } 
                    else if(prop == 'vendor' && d[prop] == "610"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Apple</td></tr>';
                    } 
                    else if(prop == 'vendor' && d[prop] == "10ac"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Dell</td></tr>';
                    } 
                    else if(prop == 'vendor' && d[prop] == "5c23"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Wacom</td></tr>';
                    } 
                    else if(prop == 'vendor' && d[prop] == "4d10"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Sharp</td></tr>';
                    } 
                    else if(prop == 'vendor' && d[prop] == "1e6d"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>LG</td></tr>';
                    } 
                    else if(prop == 'vendor' && d[prop] == "38a3"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>NEC</td></tr>';
                    } 
                    else if(prop == 'vendor' && d[prop] == "4c49"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>SMART Technologies</td></tr>';
                    } 
                    else if(prop == 'vendor' && d[prop] == "9d1"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>BenQ</td></tr>';
                    } 
                    else if(prop == 'vendor' && d[prop] == "4dd9"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Sony</td></tr>';
                    } 
                    else if(prop == 'vendor' && d[prop] == "472"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Acer</td></tr>';
                    } 
                    else if(prop == 'vendor' && d[prop] == "22f0"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>HP</td></tr>';
                    } 
                    else if(prop == 'vendor' && d[prop] == "34ac"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Mitsubishi</td></tr>';
                    } 
                    else if(prop == 'vendor' && d[prop] == "5a63"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>ViewSonic</td></tr>';
                    } 
                    else if(prop == 'vendor' && d[prop] == "4c2d"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Samsung</td></tr>';
                    } 
                    else if(prop == 'vendor' && d[prop] == "593a"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Vizio</td></tr>';
                    } 
                    else if(prop == 'vendor' && d[prop] == "d82"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>CompuLab</td></tr>';
                    } 
                     else if(prop == 'vendor' && d[prop] == "3023"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>LaCie</td></tr>';
                    } 
                     else if(prop == 'vendor' && d[prop] == "3698"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Matrox</td></tr>';
                    } 
                     else if(prop == 'vendor' && d[prop] == "4ca3"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Epson</td></tr>';
                    } 
                     else if(prop == 'vendor' && d[prop] == "170e"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Extron</td></tr>';
                    } 
                     else if(prop == 'vendor' && d[prop] == "e11"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Compaq</td></tr>';
                    } 
                     else if(prop == 'vendor' && d[prop] == "24d3"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>ASK Proxima</td></tr>';
                    } 
                     else if(prop == 'vendor' && d[prop] == "410c"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Philips</td></tr>';
                    } 
                     else if(prop == 'vendor' && d[prop] == "15c3"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Eizo</td></tr>';
                    } 
                     else if(prop == 'vendor' && d[prop] == "26cd"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>iiyama</td></tr>';
                    } 
                     else if(prop == 'vendor' && d[prop] == "7fff"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Haier</td></tr>';
                    } 
                     else if(prop == 'vendor' && d[prop] == "3e8d"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Optoma</td></tr>';
                    } 
                     else if(prop == 'vendor' && d[prop] == "5262"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Toshiba</td></tr>';
                    } 
                     else if(prop == 'vendor' && d[prop] == "34a9"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Panasonic</td></tr>';
                    } 
                     else if(prop == 'vendor' && d[prop] == "5e3"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Flanders Scientific</td></tr>';
                    } 
                     else if(prop == 'vendor' && d[prop] == "30ae"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Lenovo</td></tr>';
                    } 
                     else if(prop == 'vendor' && d[prop] == "469"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Asus</td></tr>';
                    }
                    else if(prop == 'vendor' && d[prop] == "4249"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Insignia</td></tr>';
                    }
                    else if(prop == 'vendor' && d[prop] == "5c85"){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>Westinghouse</td></tr>';
                    } 
                    else if(prop == 'timestamp'){
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td><span title="'+moment.unix(d[prop]).fromNow()+'">'+moment.unix(d[prop]).format("YYYY/MM/DD - HH:MM:SS")+'</td></tr>';
                    } 
                    else {
					   rows = rows + '<tr><th>'+i18n.t('displays.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
					}
				}
			}
			$('#displays-tab')
				.append($('<h4>')
					.append($('<i>')
						.addClass('fa fa-desktop'))
					.append(' '+d.model))
				.append($('<div style="max-width:370px;">')
					.addClass('table-responsive')
					.append($('<table>')
						.addClass('table table-striped table-condensed max-width: 200px')
						.append($('<tbody>')
							.append(rows))))
		})
	});
});
</script>
