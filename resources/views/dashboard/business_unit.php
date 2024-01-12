<?php $this->view('partials/head'); ?>

<div class="container">

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title bu-title"></h3>
				</div>
				<form class="panel-body form-horizontal">
					<div class="machine-groups">
						<h4 data-i18n="business_unit.machine_groups"></h4>
					</div>
				</form>
			</div>
		</div>
	</div>

	<?php foreach(conf('dashboard_layout', array()) AS $row):?>

	<div class="row">

		<?php foreach($row as $item):?>

		<?php $this->view("widgets/${item}_widget"); ?>

		<?php endforeach?>

	</div> <!-- /row -->

	<?php endforeach?>

</div>	<!-- /container -->

<script>

	var updateGroup = function(){

		var checked = this.checked,
			settings = {
				filter: 'machine_group',
				value: $(this).data().groupid,
				action: checked ? 'remove' : 'add'
			}

		$.post(appUrl + '/filter/set_filter', settings, function(){
			// Update all
			$(document).trigger('appReady', [i18n.lng()]);
		})



	}
	// Get all business units and machine_groups
	var defer = $.when(
		$.getJSON(appUrl + '/unit/get_data'),
		$.getJSON(appUrl + '/unit/get_machine_groups')
		);

	// Render when all requests are successful
	defer.done(function(bu_data, mg_data){

		// Set title
		var name = bu_data[0].name ||'All Business Units'
		$('h3.bu-title').text(name);


		// Add machine groups
		$.each(mg_data[0], function(index, obj){
			if(obj.groupid){
				$('.machine-groups')
					.append($('<div>')
						.addClass('checkbox')
						.append($('<label>')
							.append($('<input>')
								.data(obj)
								.prop('checked', function(){
									return obj.checked;
								})
								.change(updateGroup)
								.attr('type', 'checkbox'))
							.append(obj.name || 'No Name')))
			}
		});
	});
	


</script>

<?php $this->view('partials/foot'); ?>
