<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="munki-widget">

	  <div class="panel-heading">

	    <h3 class="panel-title"><i class="fa fa-smile-o"></i>
	        <span data-i18n="munkireport.munki"></span>
	        <list-link data-url="/show/listing/munkireport/munki"></list-link>
	    </h3>

	  </div>

	  <div class="panel-body text-center"></div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>

$(document).on('appReady', function(){

	// Add tooltip
	$('#munki-widget>div.panel-heading')
		.attr('title', i18n.t('munkireport.panel_title'))
		.tooltip();

	// Entries
	var entries = [
		{
			class: 'btn-danger',
			url: appUrl + '/show/listing/munkireport/munki#errors',
			name: 'error'
		},
		{
			class: 'btn-warning',
			url: appUrl + '/show/listing/munkireport/munki#warnings',
			name: 'warning'
		}
	]

	$.each(entries, function(i, obj){
		$('#munki-widget div.panel-body')
			.append($('<a>')
				.addClass('btn '+ obj.class)
				.attr('href', obj.url)
				.append($('<span>')
					.addClass('bigger-150')
					.text(0))
				.append($('<br>'))
				.append($('<span>')
					.addClass('count')
					.text(i18n.t(obj.name, { count: 0 }))))
			.append(' ');
	})


	$(document).on('appUpdate', function(){

		var hours = 24 * 7;
		$.getJSON( appUrl + '/module/munkireport/get_stats/'+hours, function( data ) {

			$.each(entries, function(i, obj){
				// Set count
				$('#munki-widget a.'+obj.class+' span.bigger-150')
					.text(+data[obj.name]);
				// Set localized label
				$('#munki-widget a.'+obj.class+' span.count')
					.text(i18n.t(obj.name, { count: +data[obj.name] }));
			});

		});

	});

});



</script>
