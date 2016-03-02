<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="wifi-state-widget">
		<div class="panel-heading" data-container="body">
			<h3 class="panel-title"><i class="fa fa-wifi"></i>  <span data-i18n="wifi.state"></span></h3>
		</div>
		<div class="panel-body text-center"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/wifi/get_wifi_state', function( data ) {

    	if(data.error){
    		//alert(data.error);
    		return;
    	}
		
		var panel = $('#wifi-state-widget div.panel-body'),
			baseUrl = appUrl + '/show/listing/wifi';
		panel.empty();
		
		// Set statuses
		if(data.off){
			panel.append(' <a href="'+baseUrl+'#off" class="btn btn-danger"><span class="bigger-150">'+data.off+'</span><br>&nbsp;&nbsp;'+i18n.t('off')+'&nbsp;&nbsp;</a>');
		}
		if(data.on_not_connected){
			panel.append(' <a href="'+baseUrl+'#init" class="btn btn-warning"><span class="bigger-150">'+data.on_not_connected+'</span><br>&nbsp;&nbsp;'+i18n.t('on')+'&nbsp;&nbsp;</a>');
		}
		if(data.connected){
			panel.append(' <a href="'+baseUrl+'#running" class="btn btn-success"><span class="bigger-150">'+data.connected+'</span><br>'+i18n.t('connected')+'</a>');
		}

    });
});
</script>