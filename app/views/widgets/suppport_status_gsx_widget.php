<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="warranty-support-gsx-widget">
		<div class="panel-heading" data-container="body" data-i18n="[title]widget.warrantysupport.tooltip">
			<h3 class="panel-title"><i class="fa fa-umbrella"></i> <span data-i18n="widget.warrantysupport.warrentysupportstatus"></span></h3>
		</div>

		<div class="panel-body text-center"></div>

	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/gsx/get_GSX_Support_Stats', function( data ) {

    	if(data.error){
    		//alert(data.error);
    		return;
    	}
		
		var panel = $('#warranty-support-gsx-widget div.panel-body'),
			baseUrl = appUrl + '/show/listing/gsx';
		panel.empty();
		
		// Set statuses
		if(data.obsolete){
			panel.append(' <a href="'+baseUrl+'#Obsolete" class="btn btn-danger"><span class="bigger-150">'+data.obsolete+'</span><br>'+i18n.t('gsx.obsolete')+'</a>');
		}
		if(data.vintage){
			panel.append(' <a href="'+baseUrl+'#VIN" class="btn btn-warning"><span class="bigger-150">'+data.vintage+'</span><br>'+i18n.t('gsx.vintage')+'</a>');
		}
		if(data.supported){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-success"><span class="bigger-150">'+data.supported+'</span><br>'+i18n.t('gsx.supported')+'</a>');
		}
		if(data.unknown){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-info"><span class="bigger-150">'+data.unknown+'</span><br>'+i18n.t('unknown')+'</a>');
		}

    });
});
</script>
