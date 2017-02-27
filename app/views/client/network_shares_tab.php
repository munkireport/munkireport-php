<div id="network_shares-tab"></div>
<h2 data-i18n="network_shares.clienttab"></h2>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/network_shares/get_data/' + serialNumber, function(data){
		// Set count of network shares
		$('#network_shares-cnt').text(data.length);
		var skipThese = ['id','name','serial_number'];
		$.each(data, function(i,d){
			
			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
					if(prop == 'automounted' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('network_shares.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    }
					else if(prop == 'automounted' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('network_shares.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } 
                    else {
                        rows = rows + '<tr><th>'+i18n.t('network_shares.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
					}
				}
			}
			$('#network_shares-tab')
				.append($('<h4>')
					.append($('<i>')
						.addClass('fa fa-folder-open'))
					.append(' '+d.name))
				.append($('<div style="max-width:400px;">')
					.addClass('table-responsive')
					.append($('<table>')
						.addClass('table table-striped table-condensed')
						.append($('<tbody>')
							.append(rows))))
		})
	});
});
</script>
