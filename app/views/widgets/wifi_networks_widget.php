	<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="wifi-networks-widget">
		<div class="panel-heading" data-container="body" >
			<h3 class="panel-title"><i class="fa fa-wifi"></i> <span data-i18n="wifi.networks"></span></h3>
		</div>
		<div class="list-group scroll-box"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
	
	var box = $('#wifi-networks-widget div.scroll-box');
	
	$.getJSON( appUrl + '/module/wifi/get_wifi_name', function( data ) {
		
		box.empty();
		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>';
                box.append('<a href="'+appUrl+'/show/listing/wifi/#'+d.ssid+'" class="list-group-item">'+d.ssid+badge+'</a>')
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('wifi.nonetworks')+'</span>');
		}
	});
});	
</script>