<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="modified-computernames-widget">

		<div id="modified-computer-names" class="panel-heading" data-container="body" title="Computers where the computer name doesn't match the AD name">

			<h3 class="panel-title"><i class="fa fa-code-fork"></i> Not matching AD Names</h3>

		</div>

		<div class="list-group scroll-box"></div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>

$(document).on('appUpdate', function(e, lang) {
	
	var box = $('#modified-computernames-widget div.scroll-box');
		
	$.getJSON( appUrl + '/module/directory_service/get_modified_computernames', function( data ) {
		
		box.empty();

		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">1</span>',
                    url = appUrl+'/clients/detail/'+d.serial_number+'#tab_directory-tab';
                
				box.append('<a href="'+url+'" class="list-group-item">'+d.computer_name+' != '+d.computeraccount.replace(/\$/, '')+badge+'</a>');
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('no_clients')+'</span>');
		}
	});
});	
</script>
