<div id="gpu-tab"></div>
<h2 data-i18n="gpu.clienttab"></h2>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/gpu/get_data/' + serialNumber, function(data){
		var skipThese = ['id','serial_number','model'];
		$.each(data, function(i,d){
			
			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
                    if (d[prop] == ''){
					   // Do nothing for empty values to blank them
                    } 
                    else {
                        rows = rows + '<tr><th>'+i18n.t('gpu.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
					}
				}
			}
			$('#gpu-tab')
				.append($('<h4>')
					.append($('<i>')
						.addClass('fa fa-desktop'))
					.append(' '+d.model))
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
