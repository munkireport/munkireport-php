<?php $this->view('partials/head'); ?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		<h3 id="bu_title" data-i18n="admin.bu_overview"></h3>

		<div id="bu_units"></div>
		<div data-i18n="listing.loading" id="loading"></div>

    </div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button data-i18n="dialog.cancel" type="button" class="btn btn-default" data-dismiss="modal"></button>
        <button type="button" class="btn btn-primary ok"></button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

	$(document).on('appReady', function(e, lang) {

		var machineGroups = [];

		var edit = function(){

				fields = {name:'', 'address':''};

				// Get unit data
				var data = $(this).closest('.unit').data() || {unitid:'new'};

				// Add data to fields
				$.each(data, function(prop, val){
					fields[prop] = val;
				});


				$('#myModal .modal-body')
					.empty()
					.append($('<form>')
						.submit(save)
						.append($('<input>')
							.attr('type', 'submit')
							.addClass('invisible'))
						.append($('<div>')
							.addClass('form-group')
							.append($('<label>')
								.attr('for', 'modalInputName')
								.text(i18n.t("admin.edit_bu.name")))
							.append($('<input>')
								.addClass('form-control')
								.attr('id', 'modalInputName')
								.attr('name', 'name')
								.val(fields.name))
							.append($('<input>')
								.attr('type', 'submit')
								.addClass('hide')))
						.append($('<div>')
							.addClass('form-group')
							.append($('<label>')
								.attr('for', 'modalInputAddress')
								.text(i18n.t("admin.edit_bu.address")))
							.append($('<input>')
								.addClass('form-control')
								.attr('id', 'modalInputAddress')
								.attr('name', 'address')
								.val(fields.address)))

					);
				
				// Set title
				if( fields.unitid == 'new')
				{
					$('#myModal .modal-title').text(i18n.t("admin.new_bu.title"));
				}
				else
				{
					$('#myModal .modal-title').text(i18n.t("admin.edit_bu.title"));
				}

				$('#myModal button.ok')
					.data(data)
					.text(i18n.t("dialog.save"))
					.off()
					.click(save);

				$('#myModal').modal('show');
			},
			save = function(e){
				// In case we get called by submit
				e.preventDefault();

				var data = $('#myModal button.ok').data();

				// Collect values from inputs
				$('#myModal input').each(function(){
					if($(this).attr('name'))
					{
						data[$(this).attr('name')] = $(this).val();
					}
				});

				// Store object in database
				var jqxhr = $.post( baseUrl + "admin/save_business_unit", data)
				.done(function(data){
					console.log(data.unitid)
					// Dismiss modal
					$('#myModal').modal('hide');

					// If unit does not exist, add it
					if( ! $('.unitid-' + data.unitid).length)
					{
						$('#bu_units')
							.append($('<div>')
								.addClass('col-lg-4 col-md-6 unit unitid-' + data.unitid));
					}

					// Update unit
					$('.unitid-' + data.unitid)
						.data(data)
						.each(render);

				})
				.fail(function() {
					alert( "Could not save" );
				});

			},
			remove_dialog = function(){

				// Get unit data
				var data = $(this).closest('.unit').data();

				// Set texts
				$('#myModal .modal-body').text(i18n.t("admin.remove_bu.content"));
				$('#myModal .modal-title').text(i18n.t("admin.remove_bu.title"));
				$('#myModal button.ok').text(i18n.t("dialog.ok_remove"));

				// Add unitid to ok button
				$('#myModal button.ok')
					.data({unitid: data.unitid})
					.off()
					.click(remove);

				// Show modal
				$('#myModal').modal('show');

			},
			remove = function(){
				var unitid = $(this).data().unitid;
				var url = baseUrl + 'admin/remove_business_unit/' + unitid;
				$.getJSON(url, function(data){
					if(data.success == true)
					{
						// Dismiss modal
						$('#myModal').modal('hide');
						// Update listing
						$('.unitid-' + unitid).remove();
					}
				});	
			},
			render = function(){
				var data = $(this).data(),
					machine_groups = '',
					groupname = '',
					users = '';
				if(data.machine_groups)
				{
					machine_groups = $('<ul>');
					$.each(data.machine_groups, function(index, val){
						groupname = 'No name'
						$.each(machineGroups, function(index, group){
							if(group.groupid == val)
							{
								groupname = group.name || groupname;
							}
						})
						machine_groups.append($('<li>')
							.text(groupname + ' ')
							.append($('<button>')
								.addClass('btn btn-default btn-xs')
								.text('delete')))
					});
				}
				else
				{
					machine_groups = $('<i>');
				}

				if(data.users)
				{
					users = $('<ul>');
					$.each(data.users, function(index, val){
						users.append($('<li>')
							.text(val))
					});
					users = $('<div>')
						.append($('<h4>')
							.text('Users'))
						.append(users);				}

				$(this)
					.empty()
					.append($('<div>')
						.addClass('panel panel-default')
						.append($('<div>')
							.addClass('panel-heading')
							.append($('<h3>')
								.addClass('name panel-title')
								.text(data.name)
								.append($('<a>')
									.addClass('btn btn-xs btn-default pull-right')
									.click(edit)
									.attr('title', 'edit')
									.append($('<i>')
										.addClass('fa fa-edit')))))
						.append($('<div>')
							.addClass('panel-body')
							.text(data.address)
						.append($('<div>')
							.append($('<h4>')
								.text('Machine Groups ')
									.append($('<button>')
										.addClass('btn btn-default btn-xs')
										.text('+')))
								.append(machine_groups))
						.append(users))
						.append($('<div>')
							.addClass('panel-footer')								
							.append($('<a>')
								.addClass('btn btn-xs btn-default')
								.click(remove_dialog)
								.text('delete'))))
			}


		// Get all business units and machine_groups
		var defer = $.when(
			$.getJSON(baseUrl + 'admin/get_bu_data'),
			$.getJSON(baseUrl + 'admin/get_mg_data')
			);

		// Render when all requests are successful
		defer.done(function(bu_data, mg_data){
			
			machineGroups = mg_data[0];

			// Remove Loading row
			$('#loading').hide();

			// Create business units
			$.each(bu_data[0], function(index, value){
				$('#bu_units')
					.append($('<div>')
						.data(value)
						.addClass('col-lg-4 col-md-6 unit unitid-' + value.unitid)
						.each(render)
					);
			});

		});



		// Add + button
		$('#bu_title')
			.append($('<a>')
				.addClass("btn btn-default")
				.text('+')
				.click(edit))
		
	} );
</script>

<?php $this->view('partials/foot'); ?>