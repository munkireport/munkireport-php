<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="jamf-checkin-widget">
		<div class="panel-heading" data-container="body">
			<h3 class="panel-title"><i class="fa fa-hand-peace-o"></i>
			    <span data-i18n="jamf.last_contact_time_epoch"></span>
			    <list-link data-url="/show/listing/jamf/jamf"></list-link>
			</h3>
		</div>
		<div class="panel-body text-center"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/jamf/get_last_checkin', function( data ) {

    	if(data.error){
//    		alert(data.error);
    		return;
    	}

		var panel = $('#jamf-checkin-widget div.panel-body'),
			baseUrl = appUrl + '/show/listing/jamf/jamf';
		panel.empty();

        if(data[0].red != "0"){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-danger"><span class="bigger-150">'+data[0].red+'</span><br>> 1 '+i18n.t('jamf.month')+'</a>');
		}
		if(data[0].yellow != "0"){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-warning"><span class="bigger-150">'+data[0].yellow+'</span><br>> 1 '+i18n.t('jamf.week')+'</a>');
		}
		if(data[0].green != "0"){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-success"><span class="bigger-150">'+data[0].green+'</span><br>'+i18n.t('jamf.today')+'</a>');
		}

    });
});
</script>