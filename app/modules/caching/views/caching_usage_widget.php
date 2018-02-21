<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="caching-usage-widget">
		<div class="panel-heading" data-container="body">
			<h3 class="panel-title"><i class="fa fa-database"></i>
			    <span data-i18n="caching.widget_usage_title"></span>
			    <list-link data-url="/show/listing/caching/caching"></list-link>
			</h3>
		</div>
		<div class="panel-body text-center"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/caching/caching_usage_widget', function( data ) {

    	if(data.error){
    		//alert(data.error);
    		return;
    	}

		var panel = $('#caching-usage-widget div.panel-body'),
		baseUrl = appUrl + '/show/listing/caching/caching';
		panel.empty();

		// Set statuses
        if(data.cachefree != "0" && data.cachefree >= 10000000000){
        // Set cache free box to yellow if under 10GB free
			panel.append(' <a href="'+baseUrl+'" class="btn btn-info"><span class="bigger-150">'+fileSize(data.cachefree, 2)+'</span><br>'+i18n.t('caching.cachefree')+'</a>');
		} else if(data.cachefree != "0" ){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-warning"><span class="bigger-150">'+fileSize(data.cachefree, 2)+'</span><br>'+i18n.t('caching.cachefree')+'</a>');
		} else if(data.cachefree) {
            panel.append(' <a href="'+baseUrl+'" class="btn btn-info disabled"><span class="bigger-150">'+fileSize(data.cachefree, 2)+'</span><br>'+i18n.t('caching.cachefree')+'</a>');
        }
        
		if(data.cacheused != "0"){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-info"><span class="bigger-150">'+fileSize(data.cacheused, 2)+'</span><br>'+i18n.t('caching.cacheused')+'</a>');
		} else if(data.cacheused) {
            panel.append(' <a href="'+baseUrl+'" class="btn btn-info disabled"><span class="bigger-150">'+fileSize(data.cacheused, 2)+'</span><br>'+i18n.t('caching.cacheused')+'</a>');
        }
        
		if(data.cachelimit != "0"){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-info"><span class="bigger-150">'+fileSize(data.cachelimit, 2)+'</span><br>'+i18n.t('caching.cachelimit')+'</a>');
		} else if(data.cachelimit) {
            panel.append(' <a href="'+baseUrl+'" class="btn btn-info disabled"><span class="bigger-150">'+fileSize(data.cachelimit, 2)+'</span><br>'+i18n.t('caching.cachelimit')+'</a>');
        }

    });
});
</script>
