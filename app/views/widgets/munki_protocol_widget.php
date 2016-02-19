<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="munki-protocol-widget">

	  <div class="panel-heading" data-container="body" title="Munki Protocol among all clients">

	    <h3 class="panel-title"><i class="fa fa-magic"></i> Munki Protocol</h3>

	  </div>

	  <div class="panel-body text-center"></div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/munkiprotocol/get_stats', function( data ) {
    //$.getJSON( appUrl + '/module/directory_service/get_bound_stats', function( data ) {
	if(data.error){
		//alert(data.error);
		return;
	}

		var panel = $('#munki-protocol-widget div.panel-body'),
			baseUrl = appUrl + '/show/listing/munki';
		panel.empty();

		// Set statuses
		panel.append(' <a href="'+baseUrl+'" class="btn btn-danger"><span class="bigger-150">'+data.http+'</span><br>'+i18n.t('widget.munki_protocol.http')+'</a>');
		panel.append(' <a href="'+baseUrl+'" class="btn btn-success"><span class="bigger-150">'+data.https+'</span><br>'+i18n.t('widget.munki_protocol.https')+'</a>');

    });
});


</script>