<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="uptime-widget">

		<div class="panel-heading" data-container="body">

			<h3 class="panel-title"><i class="fa fa-power-off"></i> <span data-i18n="widget.uptime.title"></span></h3>

		</div>

		<div class="panel-body text-center">

			<a href="" class="btn btn-danger">
				<span class="bigger-150"> 0 </span><br>
				7 <span data-i18n="date.day_plural"></span> +
			</a>
			<a href="" class="btn btn-warning">
				<span class="bigger-150"> 0 </span><br>
				< 7 <span data-i18n="date.day_plural"></span>
			</a>
			<a href="" class="btn btn-success">
				<span class="bigger-150"> 0 </span><br>
				< 1 <span data-i18n="date.day"></span>
			</a>

		</div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appReady', function(e, lang) {
	
	var panelBody = $('#uptime-widget div.panel-body');
	panelBody.find('a.btn').attr('href', appUrl + '/show/listing/reportdata/clients');
	
	$(document).on('appUpdate', function(e, lang) {

	    $.getJSON( appUrl + '/module/reportdata/getUptimeStats', function( data ) {

	    	if(data.error){
	    		//alert(data.error);
	    		return;
	    	}

			panelBody.find('a.btn-success span.bigger-150').text(data.oneday);
			panelBody.find('a.btn-warning span.bigger-150').text(data.oneweek);
			panelBody.find('a.btn-danger span.bigger-150').text(data.oneweekplus);
			
	    });
	});
});

</script>
