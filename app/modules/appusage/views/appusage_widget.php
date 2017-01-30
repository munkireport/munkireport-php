<div class="col-md-4">
		<div class="panel panel-default"  id="appusage-widget">
            <div class="panel-heading" data-container="body" data-i18n="[title]widget.appusage.tooltip">
                <h3 class="panel-title"><i class="fa fa-rocket"></i> <span data-i18n="listing.appusage.appusage"></span></h3>
            </div>
    <div class="list-group scroll-box"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
	
	var box = $('#appusage-widget div.scroll-box');
	
	$.getJSON( appUrl + '/module/appusage/get_applaunch', function( data ) {
		
		box.empty();
		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>';
                box.append('<a href="'+appUrl+'/show/listing/appusage/#'+d.app_name+'" class="list-group-item">'+d.app_name+badge+'</a>')
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('no_data')+'</span>');
		}
	});
});	
</script>
