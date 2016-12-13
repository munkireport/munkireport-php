<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="caching-widget">
		<div class="panel-heading" data-container="body" title="Data that has been served from OS X Server's Caching Server">
			<h3 class="panel-title"><i class="fa fa-database"></i> <span data-i18n="widget.caching"></h3>
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
		baseUrl = appUrl + '/show/listing/clients';
		panel.empty();
		
		// Set statuses
		if(data.fromorigin){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-danger"><span class="bigger-150">'+data.fromorigin+'</span><br>&nbsp;&nbsp;'+i18n.t('caching.from_origin')+'&nbsp;&nbsp;</a>');
		}
		if(data.fromcache){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-success"><span class="bigger-150">'+data.fromcache+'</span><br>'+i18n.t('caching.from_cache')+'</a>');
		}
    });
});
</script>