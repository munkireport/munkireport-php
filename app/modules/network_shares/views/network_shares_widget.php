	<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="network-shares-widget">
		<div class="panel-heading" data-container="body" >
			<h3 class="panel-title"><i class="fa fa-folder-open"></i> <span data-i18n="network_shares.clienttab"></span></h3>
		</div>
		<div class="list-group scroll-box"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
	
	var box = $('#network-shares-widget div.scroll-box');
	
	$.getJSON( appUrl + '/module/network_shares/get_network_shares', function( data ) {
		
		box.empty();
		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>';
                box.append('<a href="'+appUrl+'/show/listing/network_shares/network_shares/#'+d.name+'" class="list-group-item">'+d.name+badge+'</a>')
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('network_shares.noshares')+'</span>');
		}
	});
});	
</script>