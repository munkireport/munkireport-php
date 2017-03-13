<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="findmymac-widget">

		<div class="panel-heading" data-container="body" title="FindMyMac status">

	    	<h3 class="panel-title"><i class="fa fa-sitemap"></i> 
					<span data-i18n="findmymac.widget.title"></span>
				</h3>


		</div>
		  <div class="panel-body text-center"></div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/findmymac/get_stats', function( data ) {
	if(data.error){
		//alert(data.error);
		return;
	}

		var panel = $('#findmymac-widget div.panel-body'),
			baseUrl = appUrl + '/show/listing/findmymac/findmymac/#';
		panel.empty();

		// Set statuses
		panel.append(' <a href="'+baseUrl+'enabled" class="btn btn-danger"><span class="bigger-150">'+data.Enabled+'</span><br>'+i18n.t('findmymac.widget.enabled')+'</a>');
		panel.append(' <a href="'+baseUrl+'disabled" class="btn btn-success"><span class="bigger-150">'+data.Disabled+'</span><br>'+i18n.t('findmymac.widget.disabled')+'</a>');

    });
});


</script>