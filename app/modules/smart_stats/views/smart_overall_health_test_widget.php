<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="smart-overall-health-test">

		<div class="panel-heading" data-container="body">

			<h3 class="panel-title"><i class="fa fa-user-md"></i>
			    <span data-i18n="smart_stats.smart_overall_health_test"></span>
			    <list-link data-url="/show/listing/smart_stats/smart_stats"></list-link>
			</h3>

		</div>

		<div class="panel-body text-center"></div>

	</div><!-- /panel -->

</div><!-- /col -->



<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/smart_stats/get_smart_stats', function( data ) {

    	if(data.error){
    		//alert(data.error);
    		return;
    	}

		var panel = $('#smart-overall-health-test div.panel-body'),
			baseUrl = appUrl + '/show/listing/smart_stats/smart_stats';
		panel.empty();

		// Set statuses
		if(data.failed){
			panel.append('<a href="'+baseUrl+'#failed" class="btn btn-danger"><span class="bigger-150">'+data.failed+'</span><br>'+i18n.t("smart_stats.failed")+'</a>');
		}
		if(data.unknown){
			panel.append(' <a href="'+baseUrl+'#unknown" class="btn btn-warning"><span class="bigger-150">'+data.unknown+'</span><br>'+i18n.t("smart_stats.unknown")+'</a>');
		}
		if(data.passed){
			panel.append(' <a href="'+baseUrl+'#passed" class="btn btn-success"><span class="bigger-150">'+data.passed+'</span><br>'+i18n.t("smart_stats.passed")+'</a>');
		}


    });
});


</script>
