<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="uptime-widget">

		<div class="panel-heading" data-container="body">

			<h3 class="panel-title"><i class="fa fa-power-off"></i> Uptime</h3>

		</div>

		<div class="panel-body text-center">

			<a href="" class="btn btn-danger">
				<span class="bigger-150"> 0 </span><br>
				7 Days +
			</a>
			<a href="" class="btn btn-warning">
				<span class="bigger-150"> 0 </span><br>
				< 7 Days
			</a>
			<a href="" class="btn btn-success">
				<span class="bigger-150"> 0 </span><br>
				< 1 Day
			</a>

		</div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appReady', function(e, lang) {
	
	var panelBody = $('#uptime-widget div.panel-body');
	panelBody.find('a.btn').attr('href', appUrl + '/show/listing/clients');
	
	$(document).on('appUpdate', function(e, lang) {

	    $.getJSON( appUrl + '/module/reportdata/getUptimeStats', function( data ) {

	    	if(data.error){
	    		//alert(data.error);
	    		return;
	    	}

			panelBody.find('a.btn-success span').text(data.oneday);
			panelBody.find('a.btn-warning span').text(data.oneweek);
			panelBody.find('a.btn-danger span').text(data.oneweekplus);
			
	    });
	});
});

</script>
