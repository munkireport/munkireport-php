<div class="col-lg-4 col-md-6">

    <div class="panel panel-default" id="pending-apple-widget">

		<div class="panel-heading" data-container="body" title="Pending Apple Updates for this week">

			<h3 class="panel-title"><i class="fa fa-apple"></i> Pending Apple Updates</h3>
		
		</div>

		<div class="list-group scroll-box"></div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>

$(document).on('appUpdate', function(e, lang) {
	
	
	$.getJSON( appUrl + '/module/munkireport/get_pending_installs/apple', function( data ) {
		
        var box = $('#pending-apple-widget div.scroll-box').empty();

		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>',
                    url = appUrl+'/module/munkireport/pending#'+d.name;
                
				box.append('<a href="'+url+'" class="list-group-item">'+d.name+badge+'</a>');
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('widget.munki.no_updates_pending')+'</span>');
		}
	});
});	
</script>
