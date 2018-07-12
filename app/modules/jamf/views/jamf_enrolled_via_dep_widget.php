<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="enrolled-via-dep-widget">
		<div class="panel-heading" data-container="body">
			<h3 class="panel-title"><i class="fa fa-plus-circle"></i>
			    <span data-i18n="jamf.enrolled_via_dep"></span>
			    <list-link data-url="/show/listing/jamf/jamf"></list-link>
			</h3>
		</div>
		<div class="panel-body text-center"></div>
	</div><!-- /panel -->
</div><!-- /col -->


<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/jamf/get_enrolled_via_dep', function( data ) {

    	if(data.error){
    		//alert(data.error);
    		return;
    	}

		var panel = $('#enrolled-via-dep-widget div.panel-body'),
			baseUrl = appUrl + '/show/listing/jamf/jamf';
		panel.empty();
                
		// Set statuses
		panel.append(' <a href="'+baseUrl+'enrolled_via_dep = 1" class="btn btn-success"><span class="bigger-150">'+data.Yes+'</span><br>&nbsp;&nbsp;'+i18n.t('yes')+'&nbsp;&nbsp;</a>');
		panel.append(' <a href="'+baseUrl+'enrolled_via_dep = 0" class="btn btn-info"><span class="bigger-150">'+data.No+'</span><br>&nbsp;&nbsp;&nbsp;'+i18n.t('no')+'&nbsp;&nbsp;&nbsp;</a>');

    });
});

</script>