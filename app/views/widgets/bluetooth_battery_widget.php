<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="bluetooth-battery-widget">
		
		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-bolt"></i> 
				<span data-i18n="bluetooth.bluetooth_battery_widget"></span>
			</h3>

		</div>

		<div class="list-group scroll-box"></div>
		
	<script>
	$(document).on('appReady appUpdate', function(){
		
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
						.append(obj.computer_name)
						.append($('<span>')
							.addClass('pull-right')
							.append($('<span>')
								.addClass('label')
								.addClass(function(){
									if(obj.keyboard_battery > 39){
										return 'label-success';
									}
									if(obj.keyboard_battery > 14){
										return 'label-warning';
									}
									if(obj.keyboard_battery > -1){
										return 'label-danger';
									}
									return 'label-default';
								})
								.append('<i class="fa fa-keyboard-o"></i>'))
							.append(" ")
							.append($('<span>')
								.addClass('label')
								.addClass(function(){
									if(obj.mouse_battery > 39){
										return 'label-success';
									}
									if(obj.mouse_battery > 14){
										return 'label-warning';
									}
									if(obj.mouse_battery > -1){
										return 'label-danger';
									}
									return 'label-default';
								})
							.append(' <i class="fa fa-hand-o-up"></i>'))
							.append(" ")
							.append($('<span>')
								.addClass('label')
								.addClass(function(){
									if(obj.trackpad_battery > 39){
										return 'label-success';
									}
									if(obj.trackpad_battery > 14){
										return 'label-warning';
									}
									if(obj.trackpad_battery > -1){
										return 'label-danger';
									}
									return 'label-default';
								})
								.append('<i class="fa fa-square-o"></i>'))));

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
