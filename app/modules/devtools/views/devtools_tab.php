<h2 data-i18n="devtools.clienttabtitle"></h2>
	
<div id="devtools-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>
	<div id="devtools-view" class="row hide">
		<div class="col-md-4">
			<table class="table table-striped">
				<tr>
					<th data-i18n="devtools.xcode_version"></th>
					<td id="devtools-xcode_version"></td>
				</tr>
				<tr>
					<th data-i18n="devtools.devtools_version"></th>
					<td id="devtools-devtools_version"></td>
				</tr>
				<tr>
					<th data-i18n="devtools.cli_tools"></th>
					<td id="devtools-cli_tools"></td>
				</tr>
				<tr>
					<th data-i18n="devtools.instruments_version"></th>
					<td id="devtools-instruments_version"></td>
				</tr>
				<tr>
					<th data-i18n="devtools.dashcode_version"></th>
					<td id="devtools-dashcode_version"></td>
				</tr>	
				<tr>
					<th data-i18n="devtools.interface_builder_version"></th>
					<td id="devtools-interface_builder_version"></td>
				</tr>
				<tr>
					<th data-i18n="devtools.devtools_path"></th>
					<td id="devtools-devtools_path"></td>
				</tr>
				<tr>
					<th data-i18n="devtools.xquartz"></th>
					<td id="devtools-xquartz"></td>
				</tr>
				<tr>
					<th data-i18n="devtools.ios_sdks"></th>
					<td id="devtools-ios_sdks"></td>
				</tr>
				<tr>
					<th data-i18n="devtools.ios_simulator_sdks"></th>
					<td id="devtools-ios_simulator_sdks"></td>
				</tr>
				<tr>
					<th data-i18n="devtools.macos_sdks"></th>
					<td id="devtools-macos_sdks"></td>
				</tr>
				<tr>
					<th data-i18n="devtools.tvos_sdks"></th>
					<td id="devtools-tvos_sdks"></td>
				</tr>
				<tr>
					<th data-i18n="devtools.tvos_simulator_sdks"></th>
					<td id="devtools-tvos_simulator_sdks"></td>
				</tr>
				<tr>
					<th data-i18n="devtools.watchos_sdks"></th>
					<td id="devtools-watchos_sdks"></td>
				</tr>
				<tr>
					<th data-i18n="devtools.watchos_simulator_sdks"></th>
					<td id="devtools-watchos_simulator_sdks"></td>
				</tr>
			</table>
		</div>
	</div>
<script>
$(document).on('appReady', function(e, lang) {
	
	// Get devtools data
	$.getJSON( appUrl + '/module/devtools/get_data/' + serialNumber, function( data ) {
		if( ! data.devtools_version){
            $('#devtools-msg').text(i18n.t('no_data'));
		} else {
			
            // Hide
            $('#devtools-msg').text('');
            $('#devtools-view').removeClass('hide');

            // Add data
            $('#devtools-cli_tools').text(data.cli_tools);
            $('#devtools-dashcode_version').text(data.dashcode_version);
            $('#devtools-devtools_path').text(data.devtools_path);
            $('#devtools-devtools_version').text(data.devtools_version);
            $('#devtools-instruments_version').text(data.instruments_version);
            $('#devtools-interface_builder_version').text(data.interface_builder_version);
            $('#devtools-ios_sdks').text(data.ios_sdks);  
            $('#devtools-ios_simulator_sdks').text(data.ios_simulator_sdks);
            $('#devtools-macos_sdks').text(data.macos_sdks);
            $('#devtools-tvos_sdks').text(data.tvos_sdks);
            $('#devtools-tvos_simulator_sdks').text(data.tvos_simulator_sdks);
            $('#devtools-watchos_sdks').text(data.watchos_sdks);
            $('#devtools-watchos_simulator_sdks').text(data.watchos_simulator_sdks);
            $('#devtools-xcode_version').text(data.xcode_version);
            $('#devtools-xquartz').text(data.xquartz);  
		}
	});    
});
    
</script>