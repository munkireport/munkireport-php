<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="hardware-warranty-widget" onclick="location.href=appUrl+'/show/listing/warranty/warranty/'">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-umbrella"></i> <span data-i18n="warranty.widget_title"></span></h3>

		</div>

		<div class="list-group scroll-box"></div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
	
	var box = $('#hardware-warranty-widget div.scroll-box');
	
	$.getJSON( appUrl + '/module/warranty/get_stats', function( data ) {
		
		// Clear previous content
		box.empty();
		
		// Add entries
		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>';
                box.append('<a href="'+appUrl+'/show/listing/warranty/warranty/#'+d.status+'" class="list-group-item">'+d.status+badge+'</a>')
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('no_clients')+'</span>');
		}
	});
});	
</script>
