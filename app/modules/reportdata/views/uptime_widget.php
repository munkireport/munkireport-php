<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="uptime-widget">

		<div class="panel-heading" data-container="body">

			<h3 class="panel-title"><i class="fa fa-power-off"></i>
			    <span data-i18n="machine.uptime.title"></span>
			    <list-link data-url="/show/listing/reportdata/clients"></list-link>
			</h3>

		</div>

		<div class="panel-body text-center">

			<a tag="oneweekplus" class="btn btn-danger disabled">
				<span class="bigger-150"> 0 </span><br>
				7 <span data-i18n="date.day_plural"></span> +
			</a>
			<a tag="oneweek" class="btn btn-warning disabled">
				<span class="bigger-150"> 0 </span><br>
				< 7 <span data-i18n="date.day_plural"></span>
			</a>
			<a tag="oneday" class="btn btn-success disabled">
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
			$.each(['oneday', 'oneweek', 'oneweekplus'], function(i, tag){
				panelBody.find('a[tag="'+tag+'"]')
					.toggleClass('disabled', !data[tag])
					.find('span.bigger-150').text(data[tag] || 0)
			})

	    });
	});
});

</script>
