<div id="launchdaemons-tab"></div>
<h2 data-i18n="launchdaemons.launchdaemons"></h2>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/launchdaemons/get_tab_data/' + serialNumber, function(data){
        // Set count of launchdaemons
		$('#launcdaemons-cnt').text(data.length);
		var skipThese = ['id','serial_number','label'];
		$.each(data, function(i,d){

			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
                    // Do nothing for empty values to blank them
                    if (d[prop] == '' || d[prop] == null){
                        rows = rows

                    // Format seconds
                    } else if((prop == "timeout" || prop == "exittimeout" || prop == "throttleinterval" || prop == "startinterval" || prop == "softresourcelimitscpu" || prop == "hardresourcelimitscpu") && +d[prop] >= 60){
					   rows = rows + '<tr><th>'+i18n.t('launchdaemons.'+prop)+'</th><td><span title="'+d[prop]+' '+i18n.t('launchdaemons.seconds')+'">'+moment.duration(+d[prop], "seconds").humanize()+'</span></td></tr>';
                        
                    // Format data dize (bytes)
                    } else if(prop == 'hardresourcelimitscore' || prop == 'hardresourcelimitsdata' || prop == 'hardresourcelimitsfilesize' || prop == 'hardresourcelimitsmemorylock' || prop == 'hardresourcelimitsresidentsetsize' || prop == 'hardresourcelimitsstack' || prop == 'softresourcelimitscore' || prop == 'softresourcelimitsdata' || prop == 'softresourcelimitsfilesize' || prop == 'softresourcelimitsmemorylock' || prop == 'softresourcelimitsresidentsetsize' || prop == 'softresourcelimitsstack'){
					   rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+fileSize(d[prop], 2)+'</td></tr>';

                    // Format Yes booleans
                    } else if((prop == 'disabled' || prop == 'enableglobbing' || prop == 'enabletransactions' || prop == 'ondemand' || prop == 'runatload' || prop == 'initgroups' || prop == 'startonmount' || prop == 'debug' || prop == 'waitfordebugger' || prop == 'abandonprocessgroup' || prop == 'lowpriorityio' || prop == 'lowprioritybackgroundio' || prop == 'enablepressuredexit' || prop == 'launchonlyonce' || prop == 'inetdcompatibility' || prop == 'sessioncreate' || prop == 'legacytimers' || prop == 'keepalive' || prop == 'networkstate' || prop == 'successfulexit') && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('launchdaemons.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    // Format No booleans
                    } else if((prop == 'disabled' || prop == 'enableglobbing' || prop == 'enabletransactions' || prop == 'ondemand' || prop == 'runatload' || prop == 'initgroups' || prop == 'startonmount' || prop == 'debug' || prop == 'waitfordebugger' || prop == 'abandonprocessgroup' || prop == 'lowpriorityio' || prop == 'lowprioritybackgroundio' || prop == 'enablepressuredexit' || prop == 'launchonlyonce' || prop == 'inetdcompatibility' || prop == 'sessioncreate' || prop == 'legacytimers' || prop == 'keepalive' || prop == 'networkstate' || prop == 'successfulexit') && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('launchdaemons.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                        
                    // Format returns
                    } else if(prop == 'limitloadtosessiontype' || prop == 'limitloadtohosts' || prop == 'limitloadfromhosts' || prop == 'watchpaths' || prop == 'queuedirectories' || prop == 'pathstate' || prop == 'otherjobenabled' || prop == 'environmentvariables' || prop == 'machservices' || prop == 'limitloadtohardware'){
					   rows = rows + '<tr><th>'+i18n.t('launchdaemons.'+prop)+'</th><td>'+d[prop].replace(/\n/g,'<br>')+'</td></tr>';
                        
                    
                    // Else, build out rows from items
                    } else {
                        rows = rows + '<tr><th>'+i18n.t('launchdaemons.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
					}
				}
			}
			$('#launchdaemons-tab')
				.append($('<h4>')
					.append($('<i>')
						.addClass('fa fa-paper-plane'))
					.append(' '+d.label))
				.append($('<div>')
//					.addClass('table-responsive')
					.append($('<table>')
						.addClass('table table-striped table-condensed')
						.append($('<tbody>')
							.append(rows))))
		})
	});
});
</script>
