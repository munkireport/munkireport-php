	<h2 data-i18n="nav.listings.power"></h2>
	
	<div id="power-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>
	
	<div id="power-view" class="row hide">
		<div class="col-md-3">
			<table class="table table-striped">
				<tr>
					<th data-i18n="power.manufacture_date"></th>
					<td id="power-manufacture_date"></td>
				</tr>
				<tr>
					<th data-i18n="power.design_capacity"></th>
					<td id="power-design_capacity"></td>
				</tr>
				<tr>
					<th data-i18n="power.max_capacity"></th>
					<td id="power-max_capacity"></td>
				</tr>
				<tr>
					<th data-i18n="power.max_percent"></th>
					<td id="power-max_percent"></td>
				</tr>
				<tr>
					<th data-i18n="power.current_capacity"></th>
					<td id="power-current_capacity"></td>
				</tr>
				<tr>
					<th data-i18n="power.current_percent"></th>
					<td id="power-current_percent"></td>
				</tr>
				<tr>
					<th data-i18n="power.cycle_count"></th>
					<td id="power-cycle_count"></td>
				</tr>
				<tr>
					<th data-i18n="power.condition"></th>
					<td id="power-condition"></td>
				</tr>
				<tr>
					<th data-i18n="power.temperature"></th>
					<td id="power-temperature"></td>
				</tr>
			</table>
		</div>
		<div class="col-md-6">
			<div style="height: 512px" id="map-canvas"></div>
		</div>
	</div>

<script>
$(document).on('appReady', function(e, lang) {
	
	// Get directory_service data
	$.getJSON( appUrl + '/module/power/get_data/' + serialNumber, function( data ) {
		if( ! data.manufacture_date){
			$('#power-msg').text(i18n.t('no_data'));
		}
		else{
			
			// Hide
			$('#power-msg').text('');
			$('#power-view').removeClass('hide');

            // Add strings
			$('#power-design_capacity').text(data.design_capacity);
			$('#power-max_capacity').text(data.max_capacity);
			$('#power-current_capacity').text(data.current_capacity);
			$('#power-cycle_count').text(data.cycle_count);

            // Format Manufacture date
            if(data.manufacture_date === '1980-00-00'){
	        		$('#power-manufacture_date').addClass('danger').html(i18n.t('widget.power.now'));
            } else {
	        	if(data.manufacture_date){
	        		a = moment(data.manufacture_date)
	        		b = a.diff(moment(), 'years', true)
	        		if(a.diff(moment(), 'years', true) < -4)
	        		{
	        			$('#power-manufacture_date').addClass('danger')
	        		}
	        		if(Math.round(b) == 4)
	        		{
	        			
	        		}
	        		$('#power-manufacture_date').html('<span title="'+data.manufacture_date+'">'+moment(data.manufacture_date).fromNow());
	        	}
            }
            
            // Format battery condition
            data.condition = data.condition == 'Normal' ? '<span class="label label-success">Normal</span>' : 
            data.condition = data.condition == 'Replace Soon' ? '<span class="label label-warning">Replace Soon</span>' : 
            data.condition = data.condition == 'Service Battery' ? '<span class="label label-warning">Service Battery</span>' : 
            data.condition = data.condition == 'Check Battery' ? '<span class="label label-warning">Check Battery</span>' : 
            data.condition = data.condition == 'Replace Now' ? '<span class="label label-danger">Replace Now</span>' : 
            (data.condition === 'No Battery' ? '<span class="label label-danger">No Battery</span>' : '')
            $('#power-condition').html(data.condition)
            
            // Format battery health
            var cls = data.max_percent > 89 ? 'success' : (data.max_percent > 79 ? 'warning' : 'danger');
            $('#power-max_percent').html('<div class="progress"><div class="progress-bar progress-bar-'+cls+'" style="width: '+data.max_percent+'%;">'+data.max_percent+'%</div></div>');
            
            // Format battery charge
            var cls = data.current_percent > 89 ? 'success' : (data.current_percent > 79 ? 'warning' : 'danger');
            $('#power-current_percent').html('<div class="progress"><div class="progress-bar progress-bar-'+cls+'" style="width: '+data.current_percent+'%;">'+data.current_percent+'%</div></div>');
            
            // Format temperature
            if (data.temperature !== 0){
            // Use the hijacked ID key in the JSON return for C/F
            if (data.id === "C"){
				var outtemp = (data.temperature / 100)+"° C";
				$('#power-temperature').text(outtemp);  
            } else if (data.id === "F"){
				var outtemp = (((data.temperature * 9/5) + 3200) / 100).toFixed(2)+"° F";
				$('#power-temperature').text(outtemp); 
            } else {
				$('#power-temperature').text(data.temperature);  
            }
            } else {
                $('#power-temperature').text("");
            }
		}
	});
});

</script>