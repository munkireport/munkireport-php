
<div id="printer-tab"></div>
<h2 data-i18n="printer.tab_title"></h2>


<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/printer/get_data/' + serialNumber, function(data){
		// Set count of printers
		$('#printer-cnt').text(data.length);
		var skipThese = ['id', 'serial_number', 'name'];
		$.each(data, function(i,d){
			
			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
					rows = rows + '<tr><th>'+i18n.t('printer.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
				}
			}
			$('#printer-tab')
				.append($('<h4>')
					.append($('<i>')
						.addClass('fa fa-print'))
					.append(' '+d.name))
				.append($('<div>')
					.addClass('table-responsive')
					.append($('<table>')
						.addClass('table table-striped table-condensed')
						.append($('<tbody>')
							.append(rows))))
		})
	});
});
</script>
