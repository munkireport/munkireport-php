<div id="memory-tab"></div>
<h2 data-i18n="memory.clienttab"></h2>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/memory/get_memory_data/' + serialNumber, function(data){
		// Set count of memory devices
		var skipThese = [''];
        var memorycapacity = 0
        var mempressure = ''

		$.each(data, function(i,d){
			
			// Generate rows from data
			var inforows = ''
			var swaprows = ''
            
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
                    // Blank empty rows
                    if(d[prop] == '' || d[prop] == null){
                        inforows = inforows
                    }
                    else if(prop == 'free' || prop == 'active' || prop == 'inactive' || prop == 'speculative' || prop == 'throttled' || prop == 'wireddown' || prop == 'purgeable' || prop == 'zerofilled' || prop == 'reactivated' || prop == 'purged' || prop == 'filebacked' || prop == 'anonymous' || prop == 'storedincompressor' || prop == 'occupiedbycompressor' || prop == 'decompressions' || prop == 'compressions'){
					   inforows = inforows + '<tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+fileSize(d[prop], 2)+'</td></tr>';
                    }
                    else if(prop == 'translationfaults' || prop == 'copyonwrite'){
					   inforows = inforows + '<tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    }
                    else if(prop == 'memorypressure'){
					   inforows = inforows + '<tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+d[prop]+'% </td></tr>';
					   mempressure = d[prop]+'%';
                    }
                    else if(prop == 'swaptotal' || prop == 'swapused' || prop == 'swapfree' || prop == 'swapins' || prop == 'swapouts'){
					   swaprows = swaprows + '<tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+fileSize(d[prop], 2)+'</td></tr>';
                    }
                    else if(prop == 'pageins' || prop == 'pageouts'){
					   swaprows = swaprows + '<tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    }
                    else if(prop == 'swapencrypted' && d[prop] == 1){
					   swaprows = swaprows + '<tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    }
                    else if(prop == 'swapencrypted' && d[prop] == 0){
					   swaprows = swaprows + '<tr><th>'+i18n.t('memory.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    }
				}
			}
            
            $('#memory-cnt').text(mempressure);

            $('#memory-tab')
            
            .append($('<div style="max-width:450px;">')
                .append($('<table>')
                    .addClass('table table-striped table-condensed')
                    .append($('<tbody>')
                        .append(inforows))))
            
            .append($('<h4>')
                .append(i18n.t('memory.swap')))
            .append($('<div style="max-width:450px;">')
                .append($('<table>')
                    .addClass('table table-striped table-condensed')
                    .append($('<tbody>')
                        .append(swaprows))))
            return false;
		})
	});
});
</script>
