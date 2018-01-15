<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="munki-facts-group-widget">
		<div class="panel-heading" data-container="body" >
			<h3 class="panel-title"><i class="fa fa-check-square"></i>
    			<span data-i18n="munki_facts.reporttitle"></span>
    			<list-link data-url="/show/listing/munki_facts/munki_facts"></list-link>
			</h3>
		</div>
		<div class="list-group scroll-box"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
	
	var box = $('#munki-facts-group-widget div.scroll-box');
	
	$.getJSON( appUrl + '/module/munki_facts/get_facts_report', function( data ) {
		
		box.empty();
		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>';
                box.append('<a href="'+appUrl+'/show/listing/munki_facts/munki_facts/#'+d.fact_key+'" class="list-group-item">'+d.fact_key+badge+'</a>')
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('munki_facts.reporttitle')+'</span>');
		}
	});
});	
</script>

