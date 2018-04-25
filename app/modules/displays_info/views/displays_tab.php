
<div id="displays-tab"></div>
<h2 data-i18n="displays_info.displays"></h2>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/displays_info/get_data/' + serialNumber, function(data){
		// Set count of displays
		$('#displays-cnt').text(data.length);
        
        // If there are no displays, output message
        if (!data.length) {
            $('#displays-tab')
                .append($('<h4>')
                    .append(i18n.t('displays_info.no_displays')))
                
        } else {
            var skipThese = ['id', 'serial_number', 'model'];
            $.each(data, function(i,d){

                // Generate rows from data
                var rows = ''
                for (var prop in d){
                    // Skip skipThese
                    if(skipThese.indexOf(prop) == -1){
                        if (d[prop] == null || d[prop] == "" || d[prop] == "n/a"){
                           // Do nothing for the nulls to blank them
                        } 
                        else if(prop == 'type' && d[prop] == 1){
                           rows = rows + '<tr><th>'+i18n.t('displays_info.'+prop)+'</th><td>'+i18n.t('displays_info.external')+'</td></tr>';
                        }
                        else if(prop == 'type' && d[prop] == 0){
                           rows = rows + '<tr><th>'+i18n.t('displays_info.'+prop)+'</th><td>'+i18n.t('displays_info.internal')+'</td></tr>';
                        }
                        else if((prop == 'main_display' || prop == 'mirror' || prop == 'online' || prop == 'interlaced' || prop == 'television' || prop == 'display_asleep' || prop == 'ambient_brightness' || prop == 'retina' || prop == 'edr_enabled' || prop == 'dp_hdcp_capability' || prop == 'virtual_device') && d[prop] == 1){
                           rows = rows + '<tr><th>'+i18n.t('displays_info.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                        }
                        else if((prop == 'main_display' || prop == 'mirror' || prop == 'online' || prop == 'interlaced' || prop == 'television' || prop == 'display_asleep' || prop == 'ambient_brightness' || prop == 'retina' || prop == 'edr_enabled' || prop == 'dp_hdcp_capability' || prop == 'virtual_device') && d[prop] == 0){
                           rows = rows + '<tr><th>'+i18n.t('displays_info.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                        }
                        else if((prop == 'rotation_supported' || prop == 'automatic_graphics_switching' || prop == 'edr_supported') && d[prop] == 1){
                           rows = rows + '<tr><th>'+i18n.t('displays_info.'+prop)+'</th><td>'+i18n.t('displays_info.supported')+'</td></tr>';
                        }
                        else if((prop == 'rotation_supported' || prop == 'automatic_graphics_switching' || prop == 'edr_supported') && d[prop] == 0){
                           rows = rows + '<tr><th>'+i18n.t('displays_info.'+prop)+'</th><td>'+i18n.t('displays_info.not_supported')+'</td></tr>';
                        }
                        else if(prop == 'vendor'){
                           rows = rows + '<tr><th>'+i18n.t('displays_info.vendor')+'</th><td>'+(mr.display_vendors[d[prop]] || d[prop])+'</td></tr>';
                        }
                        else if(prop == 'manufactured' && moment(d[prop], 'YYYY-W', true).isValid() && d[prop].toLowerCase().indexOf("model") === -1){
                           rows = rows + '<tr><th>'+i18n.t('displays_info.manufactured')+'</th><td>'+'<time title="'+ moment(d[prop], 'YYYY-W').fromNow() + '" </time>' + moment(d[prop], 'YYYY-W').format("MMMM Do, YYYY")+'</td></tr>';
                        }
                        else if(prop == 'timestamp'){
                           rows = rows + '<tr><th>'+i18n.t('displays_info.'+prop)+'</th><td><span title="'+moment.unix(d[prop]).fromNow()+'">'+moment.unix(d[prop]).format("MMMM Do, YYYY - h:MM:SSa")+'</td></tr>';
                        }
                        else {
                           rows = rows + '<tr><th>'+i18n.t('displays_info.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                        }
                    }
                }

                // Generate table
                if (d.model.indexOf("iMac") !=-1 || d.model.indexOf("Cinema Display") !=-1){
                    $('#displays-tab')
                        .append($('<h4>')
                            .append($('<i>')
                                .addClass('fa fa-desktop'))
                            .append(' '+d.model))
                        .append($('<div style="max-width:475px;">')
                            .addClass('table-responsive')
                            .append($('<table>')
                                .addClass('table table-striped table-condensed max-width: 230px')
                                .append($('<tbody>')
                                    .append(rows))))
                } else if (d.type == 0){
                    $('#displays-tab')
                        .append($('<h4>')
                            .append($('<i>')
                                .addClass('fa fa-laptop'))
                            .append(' '+d.model))
                        .append($('<div style="max-width:475px;">')
                            .addClass('table-responsive')
                            .append($('<table>')
                                .addClass('table table-striped table-condensed max-width: 230px')
                                .append($('<tbody>')
                                    .append(rows))))
                } else {
                    $('#displays-tab')
                        .append($('<h4>')
                            .append($('<i>')
                                .addClass('fa fa-television'))
                            .append(' '+d.model))
                        .append($('<div style="max-width:475px;">')
                            .addClass('table-responsive')
                            .append($('<table>')
                                .addClass('table table-striped table-condensed max-width: 230px')
                                .append($('<tbody>')
                                    .append(rows))))
                }
            })
        }
	});
});
</script>
