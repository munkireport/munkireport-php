	<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="profile-group-widget">
		<div class="panel-heading" data-container="body" >
			<h3 class="panel-title"><i class="fa fa-certificate"></i>
    			<span data-i18n="profile.profile"></span>
    			<list-link data-url="/show/listing/profile/profile"></list-link>
			</h3>
		</div>
		<div class="list-group scroll-box"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
	
	var box = $('#profile-group-widget div.scroll-box');
	
	$.getJSON( appUrl + '/module/profile/get_profiles', function( data ) {
		
		box.empty();
		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>';
                box.append('<a href="'+appUrl+'/show/listing/profile/profile/#&quot;'+d.profile_name+'&quot;" class="list-group-item">'+d.profile_name+badge+'</a>')
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('profile.noprofile')+'</span>');
		}
	});
});	
</script>

