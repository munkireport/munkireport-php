<div class="col-lg-8 col-md-6">

	<div class="panel panel-default" id="events-widget">

		<div class="panel-heading" data-container="body" data-i18n="[title]events.widget_title">

			<h3 class="panel-title"><i class="fa fa-bullhorn"></i>
			    <span data-i18n="event_plural"></span>
			</h3>

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
			success: 'fa-check-circle'
		};
	
	<?php
	//Get time from conf file --Rhon Fitzwater
	if (conf('events_history')) {
		$historyTime = conf('events_history');
		echo "$.getJSON( appUrl + '/module/event/get/$historyTime/all/all/50')";
	}
	?>
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
		
		var tab = '#tab_summary',
			msg = item.msg,
			date = new Date(item.timestamp * 1000);

		if(item.module == 'munkireport' || item.module == 'managedinstalls'){
			tab = '#tab_munki';
			item.module = '';
			item.data = item.data || '{}';
			msg = i18n.t(item.msg, JSON.parse(item.data));
		}
		else if(item.module == 'diskreport'){
			tab = '#tab_storage-tab';
			item.module = '';
			item.data = item.data || '{}';
			msg = i18n.t(item.msg, JSON.parse(item.data));
		}
		else if(item.module == 'reportdata'){
			msg = i18n.t(item.msg);
		}
		else if(item.module == 'certificate'){
			tab = '#tab_certificate-tab';
			item.module = '';
			item.data = item.data || '{}';
			var parsedData = JSON.parse(item.data);
			// Convert unix timestamp to relative time
			parsedData.moment = moment(parsedData.timestamp * 1000).fromNow();
			console.log(parsedData)
			msg = i18n.t(item.msg, parsedData);
		}
		
		// Get appropriate icon
		var icon = '<i class="text-'+type+' fa '+icons[item.type]+'"></i> ',
			url = appUrl+'/clients/detail/'+item.serial_number+tab;
		
		return '<a class="list-group-item" href="'+url+'">'+
			'<span class="pull-right" style="padding-left: 10px">'+moment(date).fromNow()+'</span>'+
			icon+item.computer_name+'<span class="hidden-xs"> | </span><br class="visible-xs-inline">'+
			item.module + ' '+msg+'</a>'
			
	
	}

});
</script>
