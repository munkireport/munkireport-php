<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="purchased-leased-widget">
		<div class="panel-heading" data-container="body">
			<h3 class="panel-title"><i class="fa fa-money"></i>
			    <span data-i18n="jamf.purchased_or_leased"></span>
			    <list-link data-url="/show/listing/jamf/jamf"></list-link>
			</h3>
		</div>
		<div class="panel-body text-center"></div>
	</div><!-- /panel -->
</div><!-- /col -->


<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/jamf/get_purchased_leased', function( data ) {

    	if(data.error){
    		//alert(data.error);
    		return;
    	}

		var panel = $('#purchased-leased-widget div.panel-body'),
			baseUrl = appUrl + '/show/listing/jamf/jamf';
		panel.empty();
                
		// Set statuses
		panel.append(' <a href="'+baseUrl+'purchased = 1" class="btn btn-info"><span class="bigger-150">'+data.Purchased+'</span><br>'+i18n.t('jamf.is_purchased')+'</a>');
		panel.append(' <a href="'+baseUrl+'purchased = 0" class="btn btn-info"><span class="bigger-150">'+data.Leased+'</span><br>'+i18n.t('jamf.is_leased')+'</a>');

    });
});

</script>