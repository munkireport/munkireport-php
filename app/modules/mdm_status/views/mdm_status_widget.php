<div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="mdm-status-widget">
        <div class="panel-heading" data-container="body" title="">
            <h3 class="panel-title"><i class="fa fa-cogs"></i>
                <span data-i18n="mdm_status.mdm_enrollment"></span>
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
			panel.append(' <a href="'+baseUrl+'No" class="btn btn-danger"><span class="bigger-150">'+data.mdm_no+'</span><br>'+i18n.t('mdm_status.not_enrolled')+'</a>');
		}
		if(data.non_uamdm){
			panel.append(' <a href="'+baseUrl+'Yes" class="btn btn-warning"><span class="bigger-150">'+data.non_uamdm+'</span><br>'+i18n.t('mdm_status.non_uamdm')+'</a>');
		}
        if(data.uamdm){
			panel.append(' <a href="'+baseUrl+'Approved" class="btn btn-success"><span class="bigger-150">'+data.uamdm+'</span><br>'+i18n.t('mdm_status.uamdm')+'</a>');
		}
        if(data.dep_enrolled){
			panel.append(' <a href="'+baseUrl+'Approved" class="btn btn-success"><span class="bigger-150">'+data.dep_enrolled+'</span><br>'+i18n.t('mdm_status.dep_enrolled')+'</a>');
		}
    });
});
</script>
