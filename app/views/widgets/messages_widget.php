<div class="col-lg-8 col-md-6">

	<div class="panel panel-default" id="events-widget">

		<div class="panel-heading" data-container="body" title="Messages generated the last 24 h">

			<h3 class="panel-title"><i class="fa fa-bullhorn"></i> <span data-i18n="event_plural"></span></h3>

		</div>

		<div class="list-group scroll-box" style="max-height: 308px"></div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(){
	
	var list = $('#events-widget div.scroll-box'),
		icons = {
			danger:'fa-times-circle',
			warning: 'fa-warning',
			info: 'fa-info-circle',
			success: 'fa-thumbs-up'
		};
	
	$.getJSON( appUrl + '/module/event/get/60/all/all')
	.done(function( data ) {

		if(data.error)
		{
			alert(data.error)
			if(data.reload)
			{
				location.reload();
			}
		}
		list.empty();

		var arrayLength = data.items.length
		if (arrayLength)
		{
			for (var i = 0; i < arrayLength; i++) {
				serial=data.items[i].serial_number
				type = data.items[i].type
				list.append(get_module_item(data.items[i]));
			}

			update_time();
		}
		else
		{
			list.append('<span class="list-group-item">No messages</span>');
		}


	}).fail(function( jqxhr, textStatus, error ) {
		list.empty();
		var err = textStatus + ", " + error;
		list.append('<span class="list-group-item list-group-item-danger">'+
			"Request Failed: " + err+'</span>')
	});

	function update_time()
	{
		$( "time" ).each(function( index ) {
			var date = new Date($(this).attr('datetime') * 1000);
			$(this).html(moment(date).fromNow());
		});
	}
	
	// Get module specific item
	function get_module_item(item){
		
		var url = appUrl + '/clients/detail/' + item.serial_number,
			msg = item.msg,
			date = new Date(item.timestamp * 1000);

		if(item.module == 'munkireport'){
			url = appUrl + '/clients/detail/' + item.serial_number + '#tab_munki';
			if(item.type == 'warning'){
				msg = item.msg + ' ' + i18n.t('warning', {count: +item.msg})
			}
			else if(item.type == 'error'){
				msg = item.msg + ' ' + i18n.t('error', {count: +item.msg})
			}
		}
		else if(item.module == 'disk_report'){
			url = appUrl + '/clients/detail/' + item.serial_number + '#tab_storage-tab';
			msg = i18n.t( i18n.t('free_disk_space') + ' < ' + item.msg + 'GB');
			item.module = '';
		}
		else if(item.module == 'reportdata'){
			msg = i18n.t(item.msg);
		}
		
		// Get appropriate icon
		var icon = '<span class="text-'+type+' fa '+icons[item.type]+'"></span> ';
		
		return '<a class="list-group-item" '+ 'href="'+url+'">'+icon+
		item.serial_number+': '+item.module + ' '+msg+
		'<span class="pull-right">'+moment(date).fromNow()+'</span></a>'
	
	}

});
</script>
