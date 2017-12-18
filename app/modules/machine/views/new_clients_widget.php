<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="new-clients-widget">

		<div class="panel-heading" data-container="body" data-i18n="[title]machine.new_clients.tooltip">

			<div class="panel-title"><i class="fa fa-star-o"></i>
			    <span data-i18n="machine.new_clients.title"></span>
			    <span class="counter badge"></span>
			    <list-link data-url="/show/listing/reportdata/clients"></list-link>
			</div>

		</div>

		<div class="list-group scroll-box">
			<span class="list-group-item"><span data-i18n="machine.new_clients.no_new_clients"></span></span>
		</div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(){

	$.getJSON( appUrl + '/module/machine/new_clients', function( data ) {

		var scrollBox = $('#new-clients-widget .scroll-box').empty();

		$.each(data, function(index, obj){

			scrollBox
				.append($('<a>')
					.addClass('list-group-item')
					.attr('href', appUrl + '/clients/detail/' + obj.serial_number)
					.append(obj.computer_name)
					.append($('<span>')
						.addClass('pull-right')
						.text(function(){
								return moment(obj.reg_timestamp * 1000).fromNow();
							})))

		});

		$('#new-clients-widget .counter').html(data.length);

		if( ! data.length){
			scrollBox
				.append($('<span>')
					.addClass('list-group-item')
					.text(i18n.t('no_clients')))
		}

	});
});
</script>
