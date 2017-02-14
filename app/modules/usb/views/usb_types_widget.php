	<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="usb-types-widget">
		<div class="panel-heading" data-container="body" >
			<h3 class="panel-title"><i class="fa fa-usb"></i> <span data-i18n="usb.usbtype"></span></h3>
		</div>
		<div class="list-group scroll-box"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
	
	var box = $('#usb-types-widget div.scroll-box');
	
	$.getJSON( appUrl + '/module/usb/get_usb_types', function( data ) {
		
		box.empty();
		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>';
                box.append('<a href="'+appUrl+'/show/listing/usb/#'+d.type+'" class="list-group-item">'+d.type+badge+'</a>')
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('usb.nousb')+'</span>');
		}
	});
});	
</script>