<div class="col-lg-4 col-md-6">

	<div class="panel panel-default">

		<div class="panel-heading" data-container="body" title="Messages generated the last 24 h">

			<h3 class="panel-title"><i class="fa fa-comment"></i> Messages <span id="messages-danger" class="badge pull-right"></span></h3>

		</div>

		<div id="messages" class="list-group scroll-box"></div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(){

	$.getJSON( appUrl + '/module/messages/get')
	.done(function( data ) {

		if(data.error)
		{
			alert(data.error)
			if(data.reload)
			{
				location.reload();
			}
		}
		$('#messages').empty();

		var arrayLength = data.items.length
		if (arrayLength)
		{
			for (var i = 0; i < arrayLength; i++) {
				serial=data.items[i].serial_number
				type = data.items[i].type
				$('#messages').append(
					'<a class="list-group-item list-group-item-'+type+'" '+
					'href="<?=url("clients/detail/")?>'+serial+'">'+data.items[i].msg+
					'<span class="pull-right"><time datetime="'+data.items[i].timestamp+'">...</time></span></a>'
				)
			}

			update_time();
		}
		else
		{
			$('#messages').append('<span class="list-group-item">No messages</span>');
		}


	}).fail(function( jqxhr, textStatus, error ) {
		$('#messages').empty();
		var err = textStatus + ", " + error;
		$('#messages').append('<span class="list-group-item list-group-item-danger">'+
			"Request Failed: " + err+'</span>')
	});

	function update_time()
	{
		$( "time" ).each(function( index ) {
			var date = new Date($(this).attr('datetime') * 1000);
			$(this).html(moment(date).fromNow());
		});
	}

});
</script>
