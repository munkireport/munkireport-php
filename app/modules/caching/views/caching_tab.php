
<div id="caching-tab"></div>
<h2 data-i18n="caching.client_tab_title"></h2>


<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/caching/get_client_tab_data/' + serialNumber, function(data){
		var skipThese = ['groupdate'];
		$.each(data, function(i,d){
			
			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
                    if (d[prop] == '' && d[prop] !== 0){
					   // Do nothing for empty values to blank them
                    
                    } else if(prop.indexOf('bytes') > -1 || prop == 'personalcacheused' || prop == 'personalcachelimit' || prop == 'personalcachefree' || prop == 'cacheused' || prop == 'cachelimit' || prop == 'cachefree' || prop == 'otherdata' || prop == 'musicdata' || prop == 'moviesdata' || prop == 'itunesudata' || prop == 'booksdata' || prop == 'iossoftware' || prop == 'iclouddata' || prop == 'macsoftware' || prop == 'appletvsoftware'){
					   rows = rows + '<tr><th>'+i18n.t('caching.'+prop)+'</th><td>'+fileSize(d[prop], 2)+'</td></tr>';
                        
                    } else if(prop == 'activated' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('caching.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } else if(prop == 'activated' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('caching.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';        
                    
                    } else if(prop == 'active' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('caching.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } else if(prop == 'active' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('caching.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';   
                        
                    } else if(prop == 'registrationstatus' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('caching.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } else if(prop == 'registrationstatus' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('caching.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';   
                        
                    } else if(prop == 'restrictedmedia' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('caching.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } else if(prop == 'restrictedmedia' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('caching.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';   
                        
                    } else if(prop == 'registrationerror' && d[prop] == "NOT_ACTIVATED"){
					   rows = rows + '<tr><th>'+i18n.t('caching.'+prop)+'</th><td>'+i18n.t('caching.not_activated')+'</td></tr>';
                        
                    } else if(prop == 'startupstatus' && d[prop] == "FAILED"){
					   rows = rows + '<tr><th>'+i18n.t('caching.'+prop)+'</th><td>'+i18n.t('failed')+'</td></tr>'; 
                        
                    } else {
					   rows = rows + '<tr><th>'+i18n.t('caching.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
					}
				}
			}
			$('#caching-tab')
				.append($('<h4>')
					.append($('<i>')
						.addClass('fa fa-database'))
					.append(' '+d.groupdate))
				.append($('<div style="max-width:550px;">')
					.addClass('table-responsive')
					.append($('<table>')
						.addClass('table table-striped table-condensed')
						.append($('<tbody>')
							.append(rows))))
		})
	});
});
</script>
