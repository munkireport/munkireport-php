<div id="homebrew-tab"></div>
<h2 data-i18n="homebrew.clienttab"></h2>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/homebrew/get_data/' + serialNumber, function(data){
		// Set count of homebrew items
		$('#homebrew-cnt').text(data.length);
		var skipThese = ['id','name','serial_number'];
		$.each(data, function(i,d){
			
			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
					if(prop == 'built_as_bottle' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    }
					else if(prop == 'built_as_bottle' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } 
                    else if(prop == 'installed_as_dependency' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } 
                    else if(prop == 'installed_as_dependency' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    }
                    else if(prop == 'installed_on_request' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } 
                    else if(prop == 'installed_on_request' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    }
                    else if(prop == 'poured_from_bottle' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } 
                    else if(prop == 'poured_from_bottle' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    }
                    else if(prop == 'versions_bottle' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } 
                    else if(prop == 'versions_bottle' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    }
                    else if(prop == 'keg_only' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } 
                    else if(prop == 'keg_only' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    }
                    else if(prop == 'outdated' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } 
                    else if(prop == 'outdated' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    }
                    else if(prop == 'pinned' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } 
                    else if(prop == 'pinned' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    }
                    else if(prop == 'versions_devel' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } 
                    else if(prop == 'versions_devel' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    }
                    else if(prop == 'versions_head' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } 
                    else if(prop == 'versions_head' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    }
                    else if(prop == 'homepage'){
					   rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td><a href="'+d[prop]+'">'+d[prop]+'</a></td></tr>';
                    } 
                    else if(d[prop] == ''){
					   // Do nothing for a blank entry
                    } 
                    else {
                        rows = rows + '<tr><th>'+i18n.t('homebrew.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
					}
				}
			}
			$('#homebrew-tab')
				.append($('<h4>')
					.append($('<i>')
						.addClass('fa fa-beer'))
					.append(' '+d.name))
				.append($('<div style="max-width:900px;">')
					.addClass('table-responsive')
					.append($('<table>')
						.addClass('table table-striped table-condensed')
						.append($('<tbody>')
							.append(rows))))
		})
	});
});
</script>
