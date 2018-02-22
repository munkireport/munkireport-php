	<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="hardware-model-widget">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-laptop"></i>
			    <span data-i18n="machine.hardware_widget_title"></span>
			    <list-link data-url="/show/listing/machine/hardware"></list-link>
			</h3>

		</div>

		<div class="list-group scroll-box"></div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
	
	var box = $('#hardware-model-widget div.scroll-box');
	
	$.getJSON( appUrl + '/module/machine/get_model_stats', function( data ) {
		
		box.empty();

		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>';
                box.append('<a href="'+appUrl+'/show/listing/machine/hardware/#'+d.label+'" class="list-group-item">'+d.label+badge+'</a>')
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('no_clients')+'</span>');
		}
	});
});	
</script>
