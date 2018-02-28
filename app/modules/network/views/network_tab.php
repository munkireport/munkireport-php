<div id="network-tab"></div>
<h2 data-i18n="network.network"></h2>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/network/get_tab_data/' + serialNumber, function(data){
        // Update the tab badge count
        $('#network-cnt').text(data.length);
		var skipThese = ['service'];
		$.each(data, function(i,d){

			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
                    if (d[prop] == '' || d[prop] == null || d[prop] == "none"){
					   // Do nothing for empty values to blank them
                    } else if(prop == 'ipv6prefixlen' && d['ipv6ip'] == 'none'){
					   // Do nothing for IPv6 prefix length when ipv6ip is none
                    } else if(prop == 'status' && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('network.'+prop)+'</th><td>'+i18n.t('active')+'</td></tr>';
                    } else if(prop == 'status' && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('network.'+prop)+'</th><td>'+i18n.t('inactive')+'</td></tr>';
                    } else if(d[prop] == "manual"){
					   rows = rows + '<tr><th>'+i18n.t('network.'+prop)+'</th><td>'+i18n.t('network.manual')+'</td></tr>';
                    } else if(d[prop] == "Automatic"){
					   rows = rows + '<tr><th>'+i18n.t('network.'+prop)+'</th><td>'+i18n.t('network.automatic')+'</td></tr>';
                    } else if(d[prop] == "autoselect"){
					   rows = rows + '<tr><th>'+i18n.t('network.'+prop)+'</th><td>'+i18n.t('network.autoselect')+'</td></tr>';
                    } else if(d[prop] == "autoselect (half-duplex)"){
					   rows = rows + '<tr><th>'+i18n.t('network.'+prop)+'</th><td>'+i18n.t('network.autoselecthalf')+'</td></tr>';
                    } else if(d[prop] == "autoselect (full-duplex)"){
					   rows = rows + '<tr><th>'+i18n.t('network.'+prop)+'</th><td>'+i18n.t('network.autoselectfull')+'</td></tr>';
                    } else if(d[prop] == "not set"){
					   rows = rows + '<tr><th>'+i18n.t('network.'+prop)+'</th><td>'+i18n.t('network.notset')+'</td></tr>';
                    } else if(d[prop] == "dhcp"){
					   rows = rows + '<tr><th>'+i18n.t('network.'+prop)+'</th><td>DHCP</td></tr>';
                    } else if(d[prop] == "bootp"){
					   rows = rows + '<tr><th>'+i18n.t('network.'+prop)+'</th><td>BOOTP</td></tr>';
                    } else {
					   rows = rows + '<tr><th>'+i18n.t('network.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
					}
				}
			}
                        
            // Generate table
            if (d.service.indexOf("Wi-Fi") !=-1){
                $('#network-tab')
                    .append($('<h4>')
                    .append($('<a href="#tab_wifi-tab">')
                        .append($('<i>')
                            .addClass('fa fa-wifi'))
                        .append(' '+d.service)))
                    .append($('<div style="max-width:450px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows))))
            } else if (d.service.indexOf("Ethernet") !=-1){
                $('#network-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-indent fa-rotate-270'))
                        .append(' '+d.service))
                    .append($('<div style="max-width:450px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows))))
            } else if (d.service.indexOf("iPhone") !=-1 || d.service.indexOf("phone") !=-1){
                $('#network-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-mobile'))
                        .append(' '+d.service))
                    .append($('<div style="max-width:450px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows))))
            } else if (d.service.indexOf("iPad") !=-1 || d.service.indexOf("ablet") !=-1){
                $('#network-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-tablet'))
                        .append(' '+d.service))
                    .append($('<div style="max-width:450px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows))))
            
            } else if (d.service.indexOf("Bluetooth") !=-1){
                $('#network-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-bluetooth-b'))
                        .append(' '+d.service))
                    .append($('<div style="max-width:450px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows))))
            } else if (d.service.indexOf("Thunderbolt") !=-1){
                $('#network-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-bolt'))
                        .append(' '+d.service))
                    .append($('<div style="max-width:450px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows))))
            } else if (d.service.indexOf("USB") !=-1){
                $('#network-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-usb'))
                        .append(' '+d.service))
                    .append($('<div style="max-width:450px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows))))
            } else if (d.service.indexOf("FireWire") !=-1){
                $('#network-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-fire-extinguisher'))
                        .append(' '+d.service))
                    .append($('<div style="max-width:450px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows))))
            } else if (d.service.indexOf("VPN") !=-1){
                $('#network-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-building-o'))
                        .append(' '+d.service))
                    .append($('<div style="max-width:450px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows))))
            } else {
                $('#network-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-globe'))
                        .append(' '+d.service))
                    .append($('<div style="max-width:450px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows))))
            }
				
		})
	});
});
</script>
