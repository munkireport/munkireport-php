<div id="usb-tab"></div>
<h2 data-i18n="usb.clienttab"></h2>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/usb/get_data/' + serialNumber, function(data){
		// Set count of USB devices
		$('#usb-cnt').text(data.length);
		var skipThese = ['id','name','printer_id'];
		$.each(data, function(i,d){
			
			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
					if(prop == 'internal' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('usb.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    }
					else if(prop == 'internal' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('usb.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } 
                    else if(prop == 'media' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('usb.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } 
                    else if(prop == 'media' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('usb.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    }
                    else if(prop == 'usb_serial_number' && d[prop] == ''){
					   // Do nothing for a blank device serial number
                    } 
                    else {
                        rows = rows + '<tr><th>'+i18n.t('usb.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
					}
				}
			}
			$('#usb-tab')
				.append($('<h4>')
					.append($('<i>')
						.addClass('fa fa-usb'))
					.append(' '+d.name))
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
