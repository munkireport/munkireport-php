<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="bound-to-ds-widget">

	  <div class="panel-heading" data-container="body" data-i18n="[title]directory_service.info">

	    <h3 class="panel-title"><i class="fa fa-bullseye"></i>
	        <span data-i18n="directory_service.bound_title"></span>
	        <list-link data-url="/show/listing/directory_service/directoryservice"></list-link>
	    </h3>

	  </div>

	  <div class="panel-body text-center"></div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/directory_service/get_bound_stats', function( data ) {

    	if(data.error){
    		//alert(data.error);
    		return;
    	}

		var notbound = data.total - data.arebound;

		var panel = $('#bound-to-ds-widget div.panel-body'),
			baseUrl = appUrl + '/show/listing/directory_service/directoryservice';
		panel.empty();

		// Set statuses
		panel.append(' <a href="'+baseUrl+'#false" class="btn btn-danger"><span class="bigger-150">'+notbound+'</span><br>'+i18n.t('directory_service.notbound')+'</a>');
		panel.append(' <a href="'+baseUrl+'#true" class="btn btn-success"><span class="bigger-150">'+data.arebound+'</span><br>'+i18n.t('directory_service.bound')+'</a>');


    });
});


</script>
