<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="detectx-widget">

		<div class="panel-heading" data-container="body" title="DetectX status">

	    	<h3 class="panel-title"><i class="fa fa-sitemap"></i>
				<span data-i18n="detectx.widget.title"></span>
				<list-link data-url="/show/listing/detectx/detectx"></list-link>
			</h3>


		</div>
		  <div class="panel-body text-center"></div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/detectx/get_stats', function( data ) {
	if(data.error){
		//alert(data.error);
		return;
	}

		var panel = $('#detectx-widget div.panel-body'),
			baseUrl = appUrl + '/show/listing/detectx/detectx/#';
		panel.empty();
		// Set statuses
    console.log(data)
		panel.append(' <a href="'+baseUrl+'clean" class="btn btn-success"><span class="bigger-150">'+data.Clean+'</span><br>'+'Machines Clean'+'</a>');
		panel.append(' <a href="'+baseUrl+'infected" class="btn btn-danger"><span class="bigger-150">'+data.Infected+'</span><br>'+'Machines Infected'+'</a>');

    });
});


</script>
