
<div id="printer-tab"></div>
<h2 data-i18n="printer.tab_title"></h2>


<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/printer/get_data/' + serialNumber, function(data){
		// Set count of printers
		$('#printer-cnt').text(data.length)
		$.each(data, function(i,d){
			var rows = ''
			// Generate rows from data
			for (var prop in d){
				// Skip id and serial_number
				if(prop != 'id' && prop != 'serial_number'){
					rows = rows + '<tr><th>'+i18n.t('printer.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
				}
				console.log(prop)
			}
			$('#printer-tab')
				.append($('<h4>')
					.text(d.name))
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
