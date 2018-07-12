<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="pending-failed-widget">
		<div class="panel-heading" data-container="body">
			<h3 class="panel-title"><i class="fa fa-warning"></i>
			    <span data-i18n="jamf.pending_failed"></span>
			    <list-link data-url="/show/listing/jamf/jamf"></list-link>
			</h3>
		</div>
		<div class="panel-body text-center"></div>
	</div><!-- /panel -->
</div><!-- /col -->


<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/jamf/get_pending_failed', function( data ) {

    	if(data.error){
    		//alert(data.error);
    		return;
    	}

		var panel = $('#pending-failed-widget div.panel-body'),
			baseUrl = appUrl + '/show/listing/jamf/jamf';
		panel.empty();
                
		// Set statuses
		//panel.append(' <a href="'+baseUrl+'" class="btn btn-success"><span class="bigger-150">'+data.Completed+'</span><br>'+i18n.t('jamf.comands_completed')+'</a>');
		panel.append(' <a href="'+baseUrl+'" class="btn btn-warning"><span class="bigger-150">'+data.Pending+'</span><br>'+i18n.t('jamf.comands_pending')+'</a>');
		panel.append(' <a href="'+baseUrl+'" class="btn btn-danger"><span class="bigger-150">'+data.Failed+'</span><br>'+i18n.t('jamf.comands_failed')+'</a>');

    });
});

</script>