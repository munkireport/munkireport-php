<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="pending-munki-widget">

		<div class="panel-heading" data-container="body" data-i18n="[title]managedinstalls.widget.pending_munki.tooltip" onclick="location.href=appUrl+'/show/listing/munkireport/munki/'">

			<h3 class="panel-title"><i class="fa fa-shopping-cart"></i> <span data-i18n="managedinstalls.widget.pending_munki.title"></span></h3>

		</div>

		<div class="list-group scroll-box"></div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>

$(document).on('appUpdate', function(e, lang) {


	$.getJSON( appUrl + '/module/managedinstalls/get_pending_installs/munki', function( data ) {

        var box = $('#pending-munki-widget div.scroll-box').empty();

		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>',
                    url = appUrl+'/module/managedinstalls/listing/'+d.name+'#pending_install',
					display_name = d.display_name || d.name;

				box.append('<a href="'+url+'" class="list-group-item">'+display_name+' '+d.version+badge+'</a>');
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('managedinstalls.no_updates_pending')+'</span>');
		}
	});
});
</script>
