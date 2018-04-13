<div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="user-approved-status-widget">
        <div class="panel-heading" data-container="body" title="">
            <h3 class="panel-title"><i class="fa fa-cogs"></i>
                <span data-i18n="mdm_status.mdm_user_approved"></span>
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
		
		var panel = $('#user-approved-status-widget div.panel-body'),
			baseUrl = appUrl + '/show/listing/mdm_status/mdm_status#';
		panel.empty();

		// Set statuses
        if(data.mdm_no || data.non_uamdm){
        	var mdm_no_ints = parseInt(data.mdm_no) + parseInt(data.non_uamdm);
			panel.append(' <a href="'+baseUrl+'" class="btn btn-danger"><span class="bigger-150">'+mdm_no_ints+'</span><br>'+i18n.t('mdm_status.not_uamdm')+'</a>');
		}
		if(data.dep_enrolled || data.uamdm){
        	var mdm_ints = parseInt(data.dep_enrolled) + parseInt(data.uamdm);
			panel.append(' <a href="'+baseUrl+'Approved" class="btn btn-success"><span class="bigger-150">'+mdm_ints+'</span><br>'+i18n.t('mdm_status.user_approved')+'</a>');
		}
    });
});
</script>
