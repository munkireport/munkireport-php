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
                    else if(prop == 'metal' && d[prop] == 4){
					   rows = rows + '<tr><th>'+i18n.t('gpu.'+prop)+'</th><td>'+i18n.t('gpu.metal4')+'</td></tr>';
                    }  
                    else if(prop == 'metal' && d[prop] == 3){
					   rows = rows + '<tr><th>'+i18n.t('gpu.'+prop)+'</th><td>'+i18n.t('gpu.metal3')+'</td></tr>';
                    }  
                    else if(prop == 'metal' && d[prop] == 2){
					   rows = rows + '<tr><th>'+i18n.t('gpu.'+prop)+'</th><td>'+i18n.t('gpu.metal2')+'</td></tr>';
                    }  
                    else if(prop == 'metal' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('gpu.'+prop)+'</th><td>'+i18n.t('gpu.metal1')+'</td></tr>';
                    } 
                    else if(prop == 'metal' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('gpu.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
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
