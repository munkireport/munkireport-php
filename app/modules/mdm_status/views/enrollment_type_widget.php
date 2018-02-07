<div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="mdm-enrollment-type-widget">
        <div class="panel-heading" data-container="body" title="">
            <h3 class="panel-title"><i class="fa fa-cogs"></i>
                <span data-i18n="mdm_status.mdm_enrollment_type"></span>
                <list-link data-url="/show/listing/mdm_status/mdm_status"></list-link>
            </h3>
        </div>
		<div class="panel-body text-center"></div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/mdm_status/get_mdm_enrollment_type_stats', function( data ) {
        
    	if(data.error){
    		//alert(data.error);
    		return;
    	}
		
		var panel = $('#mdm-enrollment-type-widget div.panel-body'),
			baseUrl = appUrl + '/show/listing/mdm_status/mdm_status#';
		panel.empty();

		// Set statuses
        if(data.non_dep){
			panel.append(' <a href="'+baseUrl+'Non-DEP" class="btn btn-warning"><span class="bigger-150">'+data.non_dep+'</span><br>'+i18n.t('mdm_status.non_dep')+'</a>');
		}
		if(data.dep){
			panel.append(' <a href="'+baseUrl+'DEP" class="btn btn-success"><span class="bigger-150">'+data.dep+'</span><br>'+i18n.t('mdm_status.dep')+'</a>');
		}
    });
});
</script>
