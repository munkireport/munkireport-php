<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="power-battery-health-widget">

		<div class="panel-heading" data-container="body" data-i18n="[title]power.widget.health.tooltip">

			<h3 class="panel-title"><i class="fa fa-medkit"></i>
			    <span data-i18n="power.widget.health.title"></span> 
			    <list-link data-url="/show/listing/power/batteries"></list-link>
			%</h3>

		</div>

		<div class="panel-body text-center"></div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

	var body = $('#power-battery-health-widget div.panel-body');

	$.getJSON( appUrl + '/module/power/get_stats', function( data ) {

		// Clear previous content
		body.empty();

		// Todo: add to config
		var entries = [
			{name: '< 80%', link: 'max_percent < 80%', count: 0, class:'btn-danger', id: 'danger'},
			{name: '80% +', link: '80% max_percent 90%', count: 0, class:'btn-warning', id: 'warning'},
			{name: '90% +', link: 'max_percent > 90%', count: 0, class:'btn-success', id: 'success'}
		]

		// Calculate entries
		if(data.length){

			// Add count to entries
			$.each(entries, function(i, o){
				o.count = data[0][o.id];
			})

			// render entries
			$.each(entries, function(i, o){
				body.append('<a href="'+appUrl+'/show/listing/power/batteries/#'+encodeURIComponent(o.link)+'" class="btn '+o.class+'"><span class="bigger-150">'+o.count+'</span><br>'+o.name+'</a> ');
			});
		}
		else{
			body.append(i18n.t('no_clients'));
		}
	});
});
</script>
