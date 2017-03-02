<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="smart-status-widget">

		<div class="panel-heading" data-container="body">

			<h3 class="panel-title"><i class="fa fa-exclamation-circle"></i> <span data-i18n="storage.smartstatus"></span></h3>

		</div>

		<div class="panel-body text-center"></div>

	</div><!-- /panel -->

</div><!-- /col -->



<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/disk_report/get_smart_stats', function( data ) {

    	if(data.error){
    		//alert(data.error);
    		return;
    	}

		var panel = $('#smart-status-widget div.panel-body'),
			baseUrl = appUrl + '/show/listing/disk_report/disk';
		panel.empty();

		// Set statuses
		if(data.failing){
			panel.append('<a href="'+baseUrl+'#failing" class="btn btn-danger"><span class="bigger-150">'+data.failing+'</span><br>'+i18n.t('failing')+'</a>');
		}
		if(data.verified){
			panel.append(' <a href="'+baseUrl+'#verified" class="btn btn-success"><span class="bigger-150">'+data.verified+'</span><br>'+i18n.t('verified')+'</a>');
		}
		if(data.unsupported){
			panel.append(' <a href="'+baseUrl+'#not supported" class="btn btn-info"><span class="bigger-150">'+data.unsupported+'</span><br>'+i18n.t('unsupported')+'</a>');
		}


    });
});


</script>
