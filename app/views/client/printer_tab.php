
<div id="printer-tab"></div>
<h2 data-i18n="printer.tab_title"></h2>


<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/printer/get_data/' + serialNumber, function(data){
		// Set count of printers
		$('#printer-cnt').text(data.length);
		var skipThese = ['id', 'serial_number', 'name'];
		$.each(data, function(i,d){
			
			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
                    if(prop == 'default_set' && d[prop] == "yes"){
					   rows = rows + '<tr><th>'+i18n.t('printer.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    }
					else if(prop == 'default_set' && d[prop] == "no"){
					   rows = rows + '<tr><th>'+i18n.t('printer.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } 
                    else if(prop == 'printer_sharing' && d[prop] == "yes"){
					   rows = rows + '<tr><th>'+i18n.t('printer.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } 
                    else if(prop == 'printer_sharing' && d[prop] == "no"){
					   rows = rows + '<tr><th>'+i18n.t('printer.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } 
                    else if(prop == 'printer_status' && d[prop] == "idle"){
					   rows = rows + '<tr><th>'+i18n.t('printer.'+prop)+'</th><td>'+i18n.t('printer.idle')+'</td></tr>';
                    } 
                    else if(prop == 'printer_status' && d[prop] == "offline"){
					   rows = rows + '<tr><th>'+i18n.t('printer.'+prop)+'</th><td>'+i18n.t('printer.offline')+'</td></tr>';
                    }     
                    else if(prop == 'printer_status' && d[prop] == "in use"){
					   rows = rows + '<tr><th>'+i18n.t('printer.'+prop)+'</th><td>'+i18n.t('printer.in_use')+'</td></tr>';
                    }
                    else if(prop == 'printer_status' && d[prop] == "error"){
					   rows = rows + '<tr><th>'+i18n.t('printer.'+prop)+'</th><td>'+i18n.t('printer.error')+'</td></tr>';
                    } 
                    else {
					rows = rows + '<tr><th>'+i18n.t('printer.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    }
				}
			}
			$('#printer-tab')
				.append($('<h4>')
					.append($('<i>')
						.addClass('fa fa-print'))
					.append(' '+d.name))
				.append($('<div>')
					.addClass('table-responsive')
					.append($('<table>')
						.addClass('table table-striped table-condensed')
						.append($('<tbody>')
							.append(rows))))
		})
	});
});
</script>
