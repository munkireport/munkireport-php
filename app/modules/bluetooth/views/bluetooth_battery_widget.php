<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="bluetooth-battery-widget">
		
		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-bolt"></i> 
				<span data-i18n="bluetooth.bluetooth_battery_widget"></span><span class="counter badge pull-right"></span>
			</h3>

		</div>

		<div class="list-group scroll-box"></div>
		
	<script>
	$(document).on('appUpdate', function(){
		
		// Add tooltip
		$('#bluetooth-battery-widget>div.panel-heading')
			.attr('title', i18n.t('bluetooth.panel_title'))
			.tooltip();
		
		$.getJSON( appUrl + '/module/bluetooth/get_low', function( data ) {

			var scrollBox = $('#bluetooth-battery-widget .scroll-box').empty();

			$.each(data, function(index, obj){
				scrollBox
					.append($('<a>')
						.addClass('list-group-item')
						.attr('href', appUrl + '/clients/detail/' + obj.serial_number + '#tab_summary')
				.append(obj.computer_name));

			});

			$('#bluetooth-battery-widget .counter').html(data.length);

			if( ! data.length){
				scrollBox
					.append($('<span>')
						.addClass('list-group-item')
						.text(i18n.t('bluetooth.all_ok')))
			}

		});				
	});
	</script>

	</div><!-- /panel -->

</div><!-- /col -->
