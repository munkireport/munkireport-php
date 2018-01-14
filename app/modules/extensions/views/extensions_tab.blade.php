<div id="extensions-tab"></div>
<h2 data-i18n="extensions.clienttab"></h2>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/extensions/get_data/' + serialNumber, function(data){
		// Set count of extensions
		$('#extensions-cnt').text(data.length);
		var skipThese = ['name'];
		$.each(data, function(i,d){
			
			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
                    rows = rows + '<tr><th>'+i18n.t('extensions.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
				}
			}
			$('#extensions-tab')
				.append($('<h4>')
					.append($('<i>')
						.addClass('fa fa-puzzle-piece'))
					.append(' '+d.name))
				.append($('<div style="max-width:750px;">')
					.addClass('table-responsive')
					.append($('<table>')
						.addClass('table table-striped table-condensed')
						.append($('<tbody>')
							.append(rows))))
		})
	});
});
</script>
