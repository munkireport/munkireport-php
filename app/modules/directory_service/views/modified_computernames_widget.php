<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="modified-computernames-widget">

		<div id="modified-computer-names" class="panel-heading" data-container="body" data-i18n="[title]directory_service.modified_computernames_tooltip">

			<div class="panel-title"><i class="fa fa-code-fork"></i>
			    <span data-i18n="directory_service.modified_computernames_title"></span> 
			    <span class="counter badge"></span>
			    <list-link data-url="/show/listing/reportdata/clients"></list-link>
			</div>

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
				var url = appUrl+'/clients/detail/'+d.serial_number+'#tab_directory-tab';

				box.append('<a href="'+url+'" class="list-group-item">'+d.computer_name+' != '+d.computeraccount.replace(/\$/, '')+'</a>');
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('no_clients')+'</span>');
		}

		$('#modified-computernames-widget .counter').html(data.length);
	});
});
</script>
