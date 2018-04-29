	<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="version-widget">
		<div class="panel-heading" data-container="body" >
			<h3 class="panel-title"><i class="fa fa-code"></i>
    			<span data-i18n="sentinelone.version"></span>
    			<list-link data-url="/show/listing/sentinelone/sentinelone"></list-link>
			</h3>
		</div>
		<div class="list-group scroll-box"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
	
	var box = $('#version-widget div.scroll-box');
	
	$.getJSON( appUrl + '/module/sentinelone/get_versions', function( data ) {
		
		box.empty();
		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>';
                box.append('<a href="'+appUrl+'/show/listing/sentinelone/sentinelone/#'+d.version+'" class="list-group-item">'+d.version+badge+'</a>')
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('sentinelone.noversion')+'</span>');
		}
	});
});	
</script>

