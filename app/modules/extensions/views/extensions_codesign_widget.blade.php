<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="extensions-codesign-widget">
		<div class="panel-heading" data-container="body" >
			<h3 class="panel-title"><i class="fa fa-puzzle-piece"></i>
			    <span data-i18n="extensions.codesign_id"></span>
			    <list-link data-url="/show/listing/extensions/extensions"></list-link>
			</h3>
		</div>
		<div class="list-group scroll-box"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
	
	var box = $('#extensions-codesign-widget div.scroll-box');
	
	$.getJSON( appUrl + '/module/extensions/get_codesign', function( data ) {
		
		box.empty();
		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>';
                box.append('<a href="'+appUrl+'/show/listing/extensions/extensions/#'+d.codesign+'" class="list-group-item">'+d.codesign+badge+'</a>')
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('extensions.noextensions')+'</span>');
		}
	});
});	
</script>
