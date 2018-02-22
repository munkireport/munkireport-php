<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="munki-widget">

	  <div class="panel-heading">

	    <h3 class="panel-title"><i class="fa fa-smile-o"></i>
	        <span data-i18n="munkireport.munki"></span>
	        <list-link data-url="/show/listing/munkireport/munki"></list-link>
	    </h3>

	  </div>

		<div class="panel-body text-center">

		  <a href="#errors" tag="error" class="btn btn-danger disabled">
			  <span class="bigger-150"> 0 </span><br>
			  <span class="count"></span>
		  </a>
		  <a href="#warnings" tag="warning" class="btn btn-warning disabled">
			  <span class="bigger-150"> 0 </span><br>
			  <span class="count"></span>
		  </a>

		</div>

	</div>

</div><!-- /col -->

<script>

$(document).on('appReady', function(){

	// Add tooltip
	$('#munki-widget>div.panel-heading')
		.attr('title', i18n.t('munkireport.panel_title'))
		.tooltip();

	var panelBody = $('#munki-widget div.panel-body');

	// Tags
	var tags = ['error', 'warning'];

	// Set url
	$.each(tags, function(i, tag){
		var hash = $('#munki-widget a[tag="'+tag+'"]').attr('href')
		$('#munki-widget a[tag="'+tag+'"]')
			.attr('href', appUrl + '/show/listing/munkireport/munki'+hash);
	});

	$(document).on('appUpdate', function(){

		var hours = 24 * 7;
		$.getJSON( appUrl + '/module/munkireport/get_stats/'+hours, function( data ) {

			$.each(tags, function(i, tag){
				// Set count
				$('#munki-widget a[tag="'+tag+'"]')
					.toggleClass('disabled', ! data[tag])
					.find('span.bigger-150')
						.text(+data[tag]);
				// Set localized label
				$('#munki-widget a[tag="'+tag+'"] span.count')
					.text(i18n.t(tag, { count: +data[tag] }));
			});

		});

	});

});

</script>
