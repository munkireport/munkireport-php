<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="caching-software-widget">
		<div class="panel-heading" data-container="body">
			<h3 class="panel-title"><i class="fa fa-cubes"></i>
			    <span data-i18n="caching.widget_software_title"></span>
			    <list-link data-url="/show/listing/caching/caching"></list-link>
			</h3>
		</div>
		<div class="panel-body text-center"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/caching/caching_software_widget', function( data ) {

    	if(data.error){
    		//alert(data.error);
    		return;
    	}

		var panel = $('#caching-software-widget div.panel-body'),
		baseUrl = appUrl + '/show/listing/caching/caching';
		panel.empty();

		// Set statuses
		if(data.appletv != "0"){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-info"><span class="bigger-150">'+fileSize(data.appletv, 2)+'</span><br>'+i18n.t('caching.appletvsoftware')+'</a>');
		} else if(data.appletv) {
            panel.append(' <a href="'+baseUrl+'" class="btn btn-info disabled"><span class="bigger-150">'+fileSize(data.appletv, 2)+'</span><br>'+i18n.t('caching.appletvsoftware')+'</a>');
        }
        
		if(data.mac != "0"){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-info"><span class="bigger-150">'+fileSize(data.mac, 2)+'</span><br>'+i18n.t('caching.macsoftware')+'</a>');
		} else if(data.mac) {
            panel.append(' <a href="'+baseUrl+'" class="btn btn-info disabled"><span class="bigger-150">'+fileSize(data.mac, 2)+'</span><br>'+i18n.t('caching.macsoftware')+'</a>');
        }
        
		if(data.ios != "0"){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-info"><span class="bigger-150">'+fileSize(data.ios, 2)+'</span><br>'+i18n.t('caching.iossoftware')+'</a>');
		} else if(data.ios) {
            panel.append(' <a href="'+baseUrl+'" class="btn btn-info disabled"><span class="bigger-150">'+fileSize(data.ios, 2)+'</span><br>'+i18n.t('caching.iossoftware')+'</a>');
        }

    });
});
</script>
