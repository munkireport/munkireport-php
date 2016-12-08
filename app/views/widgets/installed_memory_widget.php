<div class="col-lg-4 col-md-6">
	
	<div class="panel panel-default" id="installed-memory-widget">
		
		<div class="panel-heading">
			
			<h3 class="panel-title"><i class="fa fa-tasks"></i> Installed Memory</h3>
			
		</div>
		
		<div class="panel-body text-center"></div>
		
	</div><!-- /panel -->
	
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
	
	var body = $('#installed-memory-widget div.panel-body');
	
	$.getJSON( appUrl + '/module/machine/get_memory_stats', function( data ) {
		
		// Clear previous content
		body.empty();
		
		// Todo: add to config
		var entries = [
			{name: '< 4GB', link: 'memory < 4GB', count: 0, class:'btn-danger', filter: function(n){return n < 4}},
			{name: '4GB +', link: '4GB memory 7GB', count: 0, class:'btn-warning', filter: function(n){return n < 8 && n >= 4}},
			{name: '8GB +', link: 'memory > 7GB', count: 0, class:'btn-success', filter: function(n){return n >= 8}}
		]
		
		// Calculate entries
		if(data.length){
			$.each(data, function(i,d){
				$.each(entries, function(i, o){
					o.count = o.count + d.count * o.filter(d.label);
				})
			});
			
			// render
			$.each(entries, function(i, o){
				body.append('<a href="'+appUrl+'/show/listing/hardware/#'+encodeURIComponent(o.link)+'" class="btn '+o.class+'"><span class="bigger-150">'+o.count+'</span><br>'+o.name+'</a> ');
			});
		}
		else{
			body.append(i18n.t('no_clients'));
		}
	});
});	
</script>