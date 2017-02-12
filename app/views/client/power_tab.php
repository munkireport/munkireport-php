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
			$('#power-manufacture_date').text(data.manufacture_date);
			$('#power-design_capacity').text(data.design_capacity);
			$('#power-max_capacity').text(data.max_capacity);
			$('#power-max_percent').text(data.max_percent);
			$('#power-current_capacity').text(data.current_capacity);
			$('#power-current_percent').text(data.current_percent);
			$('#power-cycle_count').text(data.cycle_count);
			$('#power-condition').text(data.condition); 
            
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
		}
	});
});

</script>