<div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="mdm-mdm_enrolled_via_dep_widget">
        <div class="panel-heading" data-container="body" title="">
            <h3 class="panel-title"><i class="fa fa-cogs"></i>
                <span data-i18n="mdm_status.mdm_enrolled_via_dep"></span>
                <list-link data-url="/show/listing/mdm_status/mdm_status"></list-link>
            </h3>
        </div>
		<div class="panel-body text-center"></div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/mdm_status/get_mdm_enrolled_via_dep_stats', function( data ) {
        
    	if(data.error){
    		//alert(data.error);
    		return;
    	}
		
		var panel = $('#mdm-mdm_enrolled_via_dep_widget div.panel-body'),
			baseUrl = appUrl + '/show/listing/mdm_status/mdm_status#';
		panel.empty();

		// Set statuses
        if(data.not_dep_enrolled){
			panel.append(' <a href="'+baseUrl+'No" class="btn btn-danger"><span class="bigger-150">'+data.not_dep_enrolled+'</span><br>'+"  "+i18n.t('mdm_status.not_enrolled')+"  "+'</a>');
		}
		if(data.dep_enrolled){
			panel.append(' <a href="'+baseUrl+'Yes" class="btn btn-success"><span class="bigger-150">'+data.dep_enrolled+'</span><br>'+"  "+i18n.t('mdm_status.dep_enrolled')+"  "+'</a>');
		}
    });
});
</script>
