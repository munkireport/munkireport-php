<div id="memory-tab"></div>
<h2 data-i18n="memory.clienttab"></h2>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/memory/get_data/' + serialNumber, function(data){
		// Set count of memory devices
		var skipThese = ['name'];
        var memorycapacity = 0
        var infokeep = ''

		$.each(data, function(i,d){
			
			// Generate rows from data
			var rows = ''
			var inforows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
					if(prop == 'global_ecc_state' && d[prop] == 1 && infokeep.indexOf("global_ecc_state") === -1){
					   inforows = inforows + '<div style="max-width:500px;"><table class="table-striped table-condensed"><tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+i18n.t('memory.ecc_enabled')+'</td></tr>';
					   infokeep = infokeep + 'global_ecc_state'
                    }
					else if(prop == 'global_ecc_state' && d[prop] == 0 && infokeep.indexOf("global_ecc_state") === -1){
					   inforows = inforows + '<div style="max-width:500px;"><table class="table-striped table-condensed"><tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+i18n.t('memory.ecc_disabled')+'</td></tr>';
					   infokeep = infokeep + 'global_ecc_state'
                    } 
					else if(prop == 'global_ecc_state' && d[prop] == 2 && infokeep.indexOf("global_ecc_state") === -1){
					   inforows = inforows + '<div style="max-width:500px;"><table class="table-striped table-condensed"><tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+i18n.t('memory.ecc_errors')+'</td></tr>';
					   infokeep = infokeep + 'global_ecc_state'
                    } 
                    else if(prop == 'global_ecc_state'&& infokeep.indexOf("global_ecc_state") !== -1){
					   rows = rows  
                    }
                    else if(prop == 'is_memory_upgradeable' && d[prop] == 1 && infokeep.indexOf("is_memory_upgradeable") === -1){
					   inforows = inforows + '<tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr></table></br></div>';
					   infokeep = infokeep + 'is_memory_upgradeable'
                    }
                    else if(prop == 'is_memory_upgradeable' && d[prop] == 0 && infokeep.indexOf("is_memory_upgradeable") === -1){
					   inforows = inforows + '<tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr></table></br></div>';
					   infokeep = infokeep + 'is_memory_upgradeable'
                    } 
                    else if(prop == 'is_memory_upgradeable' && infokeep.indexOf("is_memory_upgradeable") !== -1){
					   rows = rows  
                    } 
                    else if(prop == 'dimm_size'){
					   rows = rows + '<tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
					   if(d[prop].indexOf("GB") !== -1){
					        memorycapacity = memorycapacity + +d[prop].replace(/\D/g,'');
					   }                    
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

            .append(inforows)

            .append($('<h4>')
                .append($('<i>')
                    .addClass('fa fa-microchip'))
            .append(' '+d.name))
            .append($('<div style="max-width:450px;">')
                .append($('<table>')
                    .addClass('table table-striped table-condensed')
                    .append($('<tbody>')
                        .append(rows))))
		})
	});
});
</script>
