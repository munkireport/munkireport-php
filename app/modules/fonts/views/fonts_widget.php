	<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="fonts-widget">
		<div class="panel-heading" data-container="body" >
			<h3 class="panel-title"><i class="fa fa-font"></i>
			    <span data-i18n="fonts.widgettitle"></span>
			    <list-link data-url="/show/listing/fonts/fonts"></list-link>
			</h3>
		</div>
		<div class="list-group scroll-box"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
	
	var box = $('#fonts-widget div.scroll-box');
	
	$.getJSON( appUrl + '/module/fonts/get_fonts', function( data ) {
		
		box.empty();
		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>';
                box.append('<a href="'+appUrl+'/show/listing/fonts/fonts/#'+d.type_name+'" class="list-group-item">'+d.type_name+badge+'</a>')
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('font.nofonts')+'</span>');
		}
	});
});	
</script>
