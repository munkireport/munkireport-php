<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="extensions-widget">
		<div class="panel-heading" data-container="body" >
			<h3 class="panel-title"><i class="fa fa-puzzle-piece"></i>
			    <span data-i18n="extensions.clienttab"></span>
			    <list-link data-url="/show/listing/extensions/extensions"></list-link>
			</h3>
		</div>
		<div class="list-group scroll-box"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
	
	var box = $('#extensions-widget div.scroll-box');
	
	$.getJSON( appUrl + '/module/extensions/get_bundle_ids', function( data ) {
		
		box.empty();
		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>';
                box.append('<a href="'+appUrl+'/show/listing/extensions/extensions/#'+d.name+'" class="list-group-item">'+d.name+badge+'</a>')
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('extensions.noextensions')+'</span>');
		}
	});
});	
</script>
