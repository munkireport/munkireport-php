<div id="memory-tab"></div>
<h2 data-i18n="memory.clienttab"></h2>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/memory/get_data/' + serialNumber, function(data){
		// Set count of memory devices
		var skipThese = ['name'];
        var memorycapacity = 0

		$.each(data, function(i,d){
			
			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
					if(prop == 'global_ecc_state' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+i18n.t('memory.ecc_enabled')+'</td></tr>';
                    }
					else if(prop == 'global_ecc_state' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+i18n.t('memory.ecc_disabled')+'</td></tr>';
                    } 
					else if(prop == 'global_ecc_state' && d[prop] == 2){
					   rows = rows + '<tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+i18n.t('memory.ecc_errors')+'</td></tr>';
                    } 
                    else if(prop == 'is_memory_upgradeable' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } 
                    else if(prop == 'is_memory_upgradeable' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } 
                    else if(prop == 'dimm_size'){
                        rows = rows + '<tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                        memorycapacity = memorycapacity + +d[prop].replace(/\D/g,'');
                    }
                    else if(prop == 'dimm_status' && d[prop] == "empty"){
					   rows = rows + '<tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+i18n.t('memory.empty')+'</td></tr>';
                    } 
                    else if(prop == 'dimm_status' && d[prop] == "ok"){
					   rows = rows + '<tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+i18n.t('memory.ok')+'</td></tr>';
                    } 
                    else if(prop == 'dimm_status' && d[prop] == "unknown"){
					   rows = rows + '<tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+i18n.t('unknown')+'</td></tr>';
                    } 
                    else if(d[prop] == ''){
                        rows = rows
                    }
                    else {
                        rows = rows + '<tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
					}
				}
			}
            
            $('#memory-cnt').text(memorycapacity+" GB");

			$('#memory-tab')
            .append($('<h4>')
                .append($('<i>')
                    .addClass('fa fa-microchip'))
            .append(' '+d.name))
            .append($('<div style="max-width:500px;">')
                .addClass('table-responsive')
                .append($('<table>')
                    .addClass('table table-striped table-condensed')
                    .append($('<tbody>')
                        .append(rows))))
		})
	});
});
</script>
