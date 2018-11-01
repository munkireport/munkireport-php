<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="ibridge-widget">
		<div class="panel-heading" data-container="body" >
			<h3 class="panel-title"><i class="fa fa-link"></i>
			    <span data-i18n="ibridge.widgettitle"></span>
			    <list-link data-url="/show/listing/ibridge/ibridge"></list-link>
			</h3>
		</div>
		<div class="list-group scroll-box"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
	
	var box = $('#ibridge-widget div.scroll-box');
	
	$.getJSON( appUrl + '/module/ibridge/get_ibridge', function( data ) {
		
		box.empty();
		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>';
                box.append('<a href="'+appUrl+'/show/listing/ibridge/ibridge/#'+d.ibridge_model_name+'" class="list-group-item">'+d.ibridge_model_name+badge+'</a>')
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('ibridge.noibridge')+'</span>');
		}
	});
});	
</script>
