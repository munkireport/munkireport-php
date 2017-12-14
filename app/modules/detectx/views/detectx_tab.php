<div id="detectx-tab"></div>
<h2 data-i18n="detectx.clienttab"></h2>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/detectx/get_data/' + serialNumber, function(data){
		// Set count to detectx's number of issues
    $('#detectx-cnt').text(data[0].numberofissues);
		var skipThese = ['id','serial_number'];
		$.each(data, function(i,d){

			// Generate rows from data
			var rows = ''
			for (var prop in d){
        console.log(d[prop]);
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
           if(prop == 'searchdate' && d[prop] == 1){
					    rows = rows + '<tr><th>'+i18n.t('detectx.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
             }
					    else {
                if(prop == 'issues'){
                  var issue = '';
                  issue = d[prop].split(';').join('<br />');
                  rows = rows + '<tr><th>'+i18n.t('detectx.'+prop)+'</th><td>'+issue+'</td></tr>';
                  }
                else{
                rows = rows + '<tr><th>'+i18n.t('detectx.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
              }
           }
				}
			}
			$('#detectx-tab')
				.append($('<table style="max-width: 900px">')
						.addClass('table table-responsive table-striped table-condensed')
						.append($('<tbody>')
							.append(rows)))
		})
	});
});
</script>
