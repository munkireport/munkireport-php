<div class="col-md-6">
		<div class="panel panel-default"  id="printers-widget">
            <div class="panel-heading" data-container="body">
                <h3 class="panel-title"><i class="fa fa-print"></i> <span data-i18n="nav.listings.printers"></span></h3>
            </div>
    <div class="list-group scroll-box"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
	
	var box = $('#printers-widget div.scroll-box');
	
	$.getJSON( appUrl + '/module/printer/get_printers', function( data ) {
		
		box.empty();
		if(data.length){
			// Sort on version number
			data.sort(function(a,b){
				return mr.naturalSort(a.name, b.name);
			});

			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>';
                box.append('<a href="'+appUrl+'/show/listing/printers/#'+d.name+'" class="list-group-item">'+d.name+badge+'</a>')
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('no_data')+'</span>');
		}
	});
});	
</script>
