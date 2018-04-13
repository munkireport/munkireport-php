<div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="mdm-status-widget">
        <div class="panel-heading" data-container="body" title="">
            <h3 class="panel-title"><i class="fa fa-cogs"></i>
                <span data-i18n="mdm_status.mdm_enrolled"></span>
                <list-link data-url="/show/listing/mdm_status/mdm_status"></list-link>
            </h3>
        </div>
		<div class="panel-body text-center"></div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/mdm_status/get_mdm_stats', function( data ) {
        
    	if(data.error){
    		//alert(data.error);
    		return;
    	}
		
		var panel = $('#mdm-status-widget div.panel-body'),
			baseUrl = appUrl + '/show/listing/mdm_status/mdm_status#';
		panel.empty();

		// Set statuses
        if(data.mdm_no){
			panel.append(' <a href="'+baseUrl+'No" class="btn btn-danger"><span class="bigger-150">'+data.mdm_no+'</span><br>'+i18n.t('mdm_status.no')+'</a>');
		}
		if(data.mdm_yes){
			panel.append(' <a href="'+baseUrl+'Yes" class="btn btn-warning"><span class="bigger-150">'+data.mdm_yes+'</span><br>'+i18n.t('mdm_status.yes')+'</a>');
		}
		if(data.mdm_yes_ua){
			panel.append(' <a href="'+baseUrl+'Yes (User Approved)" class="btn btn-success"><span class="bigger-150">'+data.mdm_yes_ua+'</span><br>'+i18n.t('mdm_status.user_approved')+'</a>');
		}
    });
});
</script>
