<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="notification-widget">

		<div class="panel-heading" data-container="body">

            <span data-i18n="notification.widget.title"></span>

		</div>

		<div class="list-group scroll-box">
			<span class="list-group-item" data-i18n="loading"></span>
		</div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(){
	
	$.getJSON( appUrl + '/module/notification/get_list', function( data ) {

		var scrollBox = $('#notification-widget .scroll-box').empty();

		$.each(data, function(index, obj){

			scrollBox
				.append($('<a>')
					.addClass('list-group-item')
					.attr('href', appUrl + '/clients/detail/' + obj.serial_number)
					.append(obj.title)
					.append($('<span>')
						.addClass('pull-right')
						.text(function(){
								return 'some text';
							})))

		});

		$('#notification-widget .counter').html(data.length);

		if( ! data.length){
			scrollBox
				.append($('<span>')
					.addClass('list-group-item')
					.text(i18n.t('no_data')))
		}

	});				
});
</script>
