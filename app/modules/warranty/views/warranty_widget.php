<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="warranty-widget">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-umbrella"></i> <span data-i18n="warranty.status"></span></h3>

		</div>

		<div class="list-group scroll-box"></div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

	var box = $('#warranty-widget div.scroll-box'),
		days = 30,
		expires = i18n.t('warranty.expires_in', {date: moment.duration(days, "days").humanize()});

    $.getJSON( appUrl + '/module/warranty/get_stats/1', function( data ) {

		// Clear box
		box.empty();

		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>',
					url = appUrl+'/show/listing/warranty/warranty#'+d.status;

				box.append('<a href="'+url+'" class="list-group-item">'+expires+' ('+d.status+')'+badge+'</a>')
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('warranty.no_alerts')+'</span>');
		}

    });
});


</script>
