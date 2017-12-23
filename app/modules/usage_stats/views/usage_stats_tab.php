	<h2 data-i18n="usage_stats.usage_stats"></h2>
	
	<div id="usage_stats-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>
	
	<div id="usage_stats-view" class="row hide">
		<div class="col-md-4">
			<table>
			<table class="table table-striped">
				<tr>
					<th data-i18n="usage_stats.timestamp"></th>
					<td id="usage_stats-timestamp"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.thermal_pressure"></th>
					<td id="usage_stats-thermal_pressure"></td>
				</tr>
                <tr>
					<th data-i18n="usage_stats.kern_bootargs"></th>
					<td id="usage_stats-kern_bootargs"></td>
				</tr>
			</table>
                <h2 data-i18n="usage_stats.network_actvity"></h2>
			<table class="table table-striped">
				<tr>
					<th data-i18n="usage_stats.ibyte_rate"></th>
					<td id="usage_stats-ibyte_rate"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.ibytes"></th>
					<td id="usage_stats-ibytes"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.ipacket_rate"></th>
					<td id="usage_stats-ipacket_rate"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.ipackets"></th>
					<td id="usage_stats-ipackets"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.obyte_rate"></th>
					<td id="usage_stats-obyte_rate"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.obytes"></th>
					<td id="usage_stats-obytes"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.opacket_rate"></th>
					<td id="usage_stats-opacket_rate"></td>
				</tr>
                <tr>
					<th data-i18n="usage_stats.opackets"></th>
					<td id="usage_stats-opackets"></td>
				</tr>
			</table>
                <h2 data-i18n="usage_stats.disk_activity"></h2>
			<table class="table table-striped">
				<tr>
					<th data-i18n="usage_stats.rbytes_per_s"></th>
					<td id="usage_stats-rbytes_per_s"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.rbytes_diff"></th>
					<td id="usage_stats-rbytes_diff"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.rops_per_s"></th>
					<td id="usage_stats-rops_per_s"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.rops_diff"></th>
					<td id="usage_stats-rops_diff"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.wbytes_per_s"></th>
					<td id="usage_stats-wbytes_per_s"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.wbytes_diff"></th>
					<td id="usage_stats-wbytes_diff"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.wops_per_s"></th>
					<td id="usage_stats-wops_per_s"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.wops_diff"></th>
					<td id="usage_stats-wops_diff"></td>
				</tr>
			</table>
                <h2 data-i18n="usage_stats.processor_usage"></h2>
			<table class="table table-striped">
				<tr>
					<th data-i18n="usage_stats.freq_hz"></th>
					<td id="usage_stats-freq_hz"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.freq_ratio"></th>
					<td id="usage_stats-freq_ratio"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.package_watts"></th>
					<td id="usage_stats-package_watts"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.package_joules"></th>
					<td id="usage_stats-package_joules"></td>
				</tr>
			</table>
                <h2 data-i18n="usage_stats.gpu_usage"></h2>
			<table class="table table-striped">
				<tr>
					<th data-i18n="usage_stats.gpu_name"></th>
					<td id="usage_stats-gpu_name"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.gpu_freq_hz"></th>
					<td id="usage_stats-gpu_freq_hz"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.gpu_freq_ratio"></th>
					<td id="usage_stats-gpu_freq_ratio"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.gpu_busy"></th>
					<td id="usage_stats-gpu_busy"></td>
				</tr>
			</table>
                <h2 data-i18n="usage_stats.backlights"></h2>
			<table class="table table-striped">
				<tr>
					<th data-i18n="usage_stats.lcd_backlight"></th>
					<td id="usage_stats-backlight"></td>
				</tr>
				<tr>
					<th data-i18n="usage_stats.keyboard_backlight"></th>
					<td id="usage_stats-keyboard_backlight"></td>
				</tr>
			</table>
	</div>
</div>
<script>
$(document).on('appReady', function(e, lang) {
	
	// Get directory_service data
	$.getJSON( appUrl + '/module/usage_stats/get_data/' + serialNumber, function( data ) {
		if( ! data.timestamp){
			$('#usage_stats-msg').text(i18n.t('no_data'));
		}
		else{
			
			// Hide
			$('#usage_stats-msg').text('');
			$('#usage_stats-view').removeClass('hide');

			// Add strings
			$('#usage_stats-thermal_pressure').text(data.thermal_pressure);
			$('#usage_stats-kern_bootargs').text(data.kern_bootargs);
            
            // Format timestamp
            $('#usage_stats-timestamp').html('<span title=" '+moment((+data.timestamp)*1000).format('llll')+'">'+moment((+data.timestamp)*1000).fromNow()+'</span>');

            // Backlights
			if(data.keyboard_backlight >= 0 && data.keyboard_backlight != null) {
				 $('#usage_stats-keyboard_backlight').text((data.keyboard_backlight));
			} else{
				 $('#usage_stats-keyboard_backlight').text('');
			}
                       
			if(data.backlight >= 0 && data.backlight != null) {
				 $('#usage_stats-backlight').text((((+data.backlight)/(+data.backlight_max))*100).toFixed(2)+'%');
			} else{
				 $('#usage_stats-backlight').text('');
			}
                 
            // Disk
			if(data.rbytes_diff) {
				 $('#usage_stats-rbytes_diff').text(fileSize(data.rbytes_diff, 2));
			} else{
				 $('#usage_stats-rbytes_diff').text('');
			}
                        
			if(data.rbytes_per_s) {
				 $('#usage_stats-rbytes_per_s').text(fileSize(data.rbytes_per_s, 2)+'/s');
			} else{
				 $('#usage_stats-rbytes_per_s').text('');
			}
                        
			if(data.rops_diff) {
				 $('#usage_stats-rops_diff').text(data.rops_diff);
			} else{
				 $('#usage_stats-rops_diff').text('');
			}

			if(data.rops_per_s) {
				 $('#usage_stats-rops_per_s').text((+data.rops_per_s).toFixed(2)+'/s');
			} else{
				 $('#usage_stats-rops_per_s').text('');
			} 
            
            if(data.wbytes_diff) {
				 $('#usage_stats-wbytes_diff').text(fileSize(data.wbytes_diff, 2));
			} else{
				 $('#usage_stats-wbytes_diff').text('');
			} 
            
            if(data.wbytes_per_s) {
				 $('#usage_stats-wbytes_per_s').text(fileSize(data.wbytes_per_s, 2)+'/s');
			} else{
				 $('#usage_stats-wbytes_per_s').text('');
			} 
            
            if(data.wops_diff) {
				 $('#usage_stats-wops_diff').text(data.wops_diff);
			} else{
				 $('#usage_stats-wops_diff').text('');
			} 
            
            if(data.wops_per_s) {
				 $('#usage_stats-wops_per_s').text((+data.wops_per_s).toFixed(2)+'/s');
			} else{
				 $('#usage_stats-wops_per_s').text('');
			} 
            
            
            // Network              
			if(data.ibyte_rate) { // in bytes per second
				 $('#usage_stats-ibyte_rate').text(fileSize(data.ibyte_rate, 2)+'/s');
			} else{
				 $('#usage_stats-ibyte_rate').text('');
			}
                        
			if(data.ibytes) {
				 $('#usage_stats-ibytes').text(fileSize(data.ibytes, 2));
			} else{
				 $('#usage_stats-ibytes').text('');
			}
                        
			if(data.ipacket_rate) { // in packets per second
				 $('#usage_stats-ipacket_rate').text((+data.ipacket_rate).toFixed(2)+'/s');
			} else{
				 $('#usage_stats-ipacket_rate').text('');
			}

			if(data.ipackets) {
				 $('#usage_stats-ipackets').text(data.ipackets);
			} else{
				 $('#usage_stats-ipackets').text('');
			} 
            
            if(data.obyte_rate) {
				 $('#usage_stats-obyte_rate').text(fileSize(data.obyte_rate, 2)+'/s');
			} else{
				 $('#usage_stats-obyte_rate').text('');
			} 
            
            if(data.obytes) {
				 $('#usage_stats-obytes').text(fileSize(data.obytes, 2));
			} else{
				 $('#usage_stats-obytes').text('');
			} 
            
            if(data.opacket_rate) {
				 $('#usage_stats-opacket_rate').text((+data.opacket_rate).toFixed(2)+'/s');
			} else{
				 $('#usage_stats-opacket_rate').text('');
			} 
            
            if(data.opackets) {
				 $('#usage_stats-opackets').text(data.opackets);
			} else{
				 $('#usage_stats-opackets').text('');
			} 

            // GPU
			if(data.gpu_name) {
				 $('#usage_stats-gpu_name').text(data.gpu_name);
			} else{
				 $('#usage_stats-gpu_name').text('');
			} 
            
            if(data.gpu_freq_hz) {
				 $('#usage_stats-gpu_freq_hz').text((+data.gpu_freq_hz/1000000).toFixed(2)+'Mhz');
			} else{
				 $('#usage_stats-gpu_freq_hz').text('');
			} 
            
            if(data.gpu_freq_ratio) {
				 $('#usage_stats-gpu_freq_ratio').text((+data.gpu_freq_ratio*100).toFixed(2)+'%');
			} else{
				 $('#usage_stats-gpu_freq_ratio').text('');
			} 
            
            if(data.gpu_busy) {
				 $('#usage_stats-gpu_busy').text((+data.gpu_busy*100).toFixed(2)+'%');
			} else{
				 $('#usage_stats-gpu_busy').text('');
			} 
            
            
            // CPU
			if(data.freq_hz) {
				 $('#usage_stats-freq_hz').text((+data.freq_hz/1000000000).toFixed(2)+'Ghz');
			} else{
				 $('#usage_stats-freq_hz').text('');
			} 
            
            if(data.freq_ratio) {
				 $('#usage_stats-freq_ratio').text((+data.freq_ratio*100).toFixed(2)+'%');
			} else{
				 $('#usage_stats-freq_ratio').text('');
			} 
            
            if(data.package_joules) {
				 $('#usage_stats-package_joules').text((+data.package_joules).toFixed(2)+' Joules');
			} else{
				 $('#usage_stats-package_joules').text('');
			} 
            
            if(data.package_watts) {
				 $('#usage_stats-package_watts').text((+data.package_watts).toFixed(2)+' Watts');
			} else{
				 $('#usage_stats-package_watts').text('');
			} 
		}
	});
});

</script>
