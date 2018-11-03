
<div id="displays-tab"></div>
<h2 data-i18n="displays_info.displays"></h2>

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
					   rows = rows + '<tr><th>'+i18n.t('displays_info.'+prop)+'</th><td>'+i18n.t('displays_info.external')+'</td></tr>';
                    }
					else if(prop == 'type' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('displays_info.'+prop)+'</th><td>'+i18n.t('displays_info.internal')+'</td></tr>';
                    }
                    else if(prop == 'vendor'){
					   rows = rows + '<tr><th>'+i18n.t('displays_info.vendor')+'</th><td>'+(mr.display_vendors[d[prop]] || d[prop])+'</td></tr>';
                    }
                    else if(prop == 'timestamp'){
					   rows = rows + '<tr><th>'+i18n.t('displays_info.'+prop)+'</th><td><span title="'+moment.unix(d[prop]).fromNow()+'">'+moment.unix(d[prop]).format("YYYY/MM/DD - HH:MM:SS")+'</td></tr>';
                    }
                    else {
					   rows = rows + '<tr><th>'+i18n.t('displays_info.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
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
