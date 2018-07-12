	<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="jamf-version-widget">
		<div class="panel-heading" data-container="body" >
			<h3 class="panel-title"><i class="fa fa-arrows-alt"></i>
			    <span data-i18n="jamf.jamf_version"></span>
			    <list-link data-url="/show/listing/jamf/jamf"></list-link>
			</h3>
		</div>
		<div class="list-group scroll-box"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
	
	var box = $('#jamf-version-widget div.scroll-box');
	
	$.getJSON( appUrl + '/module/jamf/get_jamf_version', function( data ) {
		
		box.empty();
		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>';
                box.append('<a href="'+appUrl+'/show/listing/jamf/jamf/#'+d.jamf_version+'" class="list-group-item">'+d.jamf_version+badge+'</a>')
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('jamf.no_jamf_version')+'</span>');
		}
	});
});	
</script>
