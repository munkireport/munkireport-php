<div id="timemachine-tab"></div>
<h2 data-i18n="timemachine.timemachine"></h2>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/timemachine/get_tab_data/' + serialNumber, function(data){
		var skipThese = ['id','serial_number','destinations','localized_disk_image_volume_name','alias_volume_name'];
		$.each(data, function(i,d){

			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
                    if (d[prop] == '' || d[prop] == null){
					   // Do nothing for empty values to blank them
                    } else if(prop.indexOf('bytes') > -1){
					   rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+fileSize(d[prop], 2)+'</td></tr>';

                    } else if(prop == "duration"){
					   rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td><span title="'+d[prop]+' '+i18n.t('power.seconds')+'">'+moment.duration(+d[prop], "seconds").humanize()+'</span></td></tr>';

                    } else if(prop == "last_success" || prop == "last_failure"){
					   rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td><span title="' + moment(d[prop]).format('llll') + '">'+moment(d[prop] + 'Z').fromNow()+'</span></td></tr>';

                    } else if(prop == "earliest_snapshot_date" || prop == "latest_snapshot_date"){
					   rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td><span title="' + moment(d[prop]).fromNow() + '">'+moment(d[prop] + 'Z').format('llll')+'</span></td></tr>';

                    } else if(prop == "consistency_scan_date" || prop == "date_of_latest_warning" || prop == "last_configuration_trace_date"){
					   var date = new Date(d[prop] * 1000);
					   rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td><span title="'+moment(date).fromNow()+'">'+moment(date).format('llll')+'</span></td></tr>';

                    } else if(prop == 'result'){
					   rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('timemachine.'+d[prop])+'</td></tr>';

                    } else if(d[prop] == 'NotEncrypted'){
					   rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('unencrypted')+'</td></tr>';
                    } else if(d[prop] == 'Encrypted'){
					   rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('encrypted')+'</td></tr>';

                    } else if(prop == 'always_show_deleted_backups_warning' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } else if(prop == 'always_show_deleted_backups_warning' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    }

                    else if(prop == 'auto_backup' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } else if(prop == 'auto_backup' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    }

                    else if(prop == 'mobile_backups' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } else if(prop == 'mobile_backups' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    }

                    else if(prop == 'skip_system_files' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } else if(prop == 'skip_system_files' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    }

                    else if(prop == 'is_network_destination' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } else if(prop == 'is_network_destination' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';

                    } else if(prop == "snapshot_dates"){
					   var snapdates = d[prop].split(", ");
					   var outsnaps = "";
					   snapdates.forEach(function(snapdate) {
                           var date = new Date(snapdate * 1000);
                           outsnaps = outsnaps + '<span title="'+moment(date).fromNow()+'">'+moment(date).format('llll')+'</span><br>'
					   });
					   rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+outsnaps+'</td></tr>';

                    } else {
                        rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
					}
				}
			}
			$('#timemachine-tab')
				.append($('<h4>')
					.append($('<i>')
						.addClass('fa fa-clock-o'))
					.append(' '+d.alias_volume_name))
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
