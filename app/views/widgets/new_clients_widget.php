		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default" id="new-clients-widget">

				<div class="panel-heading" data-container="body" title="Clients registered this week">

					<h3 class="panel-title"><i class="fa fa-star-o"></i> New clients <span class="counter badge pull-right"></span></h3>

				</div>

				<div class="list-group scroll-box">
				  	

					<span class="list-group-item">No new clients</span>
				</div>
			<script>
			$(document).on('appReady appUpdate', function(){
				
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
									.append($('<time>')
										.text(function(){
											var date = new Date(obj.reg_timestamp * 1000);
											return moment(date).fromNow();
										}))))

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

			</div><!-- /panel -->

		</div><!-- /col -->