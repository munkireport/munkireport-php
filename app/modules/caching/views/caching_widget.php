<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="caching-widget">
		<div class="panel-heading" data-container="body" data-i18n="[title]caching.widget_tooltip">
			<h3 class="panel-title"><i class="fa fa-database"></i>
			    <span data-i18n="caching.widget_title"></span>
			    <list-link data-url="/show/listing/caching/caching"></list-link>
			</h3>
		</div>
		<div class="panel-body text-center"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/caching/caching_widget', function( data ) {

    	if(data.error){
    		//alert(data.error);
    		return;
    	}

		var panel = $('#caching-widget div.panel-body'),
		baseUrl = appUrl + '/show/listing/caching/caching';
		panel.empty();

		// Set statuses
		if(data.fromcache){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-success"><span class="bigger-150">'+fileSize(data.fromcache, 2)+'</span><br>&nbsp;&nbsp;'+i18n.t('caching.from_cache')+'&nbsp;&nbsp;</a>');
		}
		if(data.fromorigin){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-info"><span class="bigger-150">'+fileSize(data.fromorigin, 2)+'</span><br>&nbsp;&nbsp;'+i18n.t('caching.from_origin')+'&nbsp;&nbsp;</a>');
		}
    });
});
</script>
