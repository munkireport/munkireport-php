
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
					if(prop.indexOf('bytes') > -1){
					   rows = rows + '<tr><th>'+i18n.t('caching.'+prop)+'</th><td>'+fileSize(d[prop], 2)+'</td></tr>';
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
