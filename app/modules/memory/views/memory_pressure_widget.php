<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="memory-pressure-widget">
		<div class="panel-heading" data-container="body" >
			<h3 class="panel-title"><i class="fa fa-area-chart"></i>
			    <span data-i18n="memory.memorypressure"></span>
			    <list-link data-url="/show/listing/memory/memory"></list-link>
			</h3>
		</div>
		<div class="list-group scroll-box"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
	
	var box = $('#memory-pressure-widget div.scroll-box');
	
	$.getJSON( appUrl + '/module/memory/memory_pressure_widget', function( data ) {
		
		box.empty();
		if(data.length){
			$.each(data, function(i,d){
                if (d.memorypressure >= 80){
                    var badge = '<span class="badge pull-right alert-danger">'+d.memorypressure+'%</span>';
                } else{
                    var badge = '<span class="badge pull-right">'+d.memorypressure+'%</span>';
                }
                box.append('<a href="'+appUrl+'/show/listing/memory/memory/#'+d.computer_name+'" class="list-group-item">'+d.computer_name+badge+'</a>')
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('no_data')+'</span>');
		}
	});
});	
</script>
