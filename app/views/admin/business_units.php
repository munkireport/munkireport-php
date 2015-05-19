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

			var fields = {name:'', 'address':''},
				dataTemplate = {unitid:'new', users:['#'], managers:['#']};

			// Clone unit data
			var data = $.extend(true, {}, $(this).closest('.unit').data() || dataTemplate);

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
							.val(fields.address))));
			
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

			console.log($('#myModal button.ok').data())

			$('#myModal').modal('show');
		},
			editGroups = function(){

				// Get unit data
				var data = $(this).closest('.unit').data();

  				var groupList = $('<div>').addClass('form-group');

				$.each(machineGroups, function(index, obj){
					groupList
						.append($('<div>')
							.addClass('checkbox')
							.append($('<label>')
								.append($('<input>')
									.attr('type', 'checkbox')
									.attr('name', 'machine_group')
									.attr('data-id', obj.groupid)
									.prop('checked', function(){return data.machine_groups.indexOf(obj.groupid) != -1}))
								.append(obj.name || 'No name')))

				});

				$('#myModal .modal-body')
					.empty()
					.append($('<form>')
						.submit(save)
						.append(groupList));

				$('#myModal .modal-title').text(i18n.t("admin.edit_mg.title"));

				$('#myModal button.ok')
					.data(data)
					.text(i18n.t("dialog.save"))
					.off()
					.click(save);

				$('#myModal').modal('show');
			},
			editUsers = function(){

				// users or managers
				var who = $(this).data('type');

				// Clone unit data
				var data = $.extend(true, {}, $(this).closest('.unit').data());

				var groupList = $('<ul>').addClass('user-list');

				var renderUserList = function(){
					groupList
						.empty()

					$.each(data[who], function(index, name){
						groupList
							.append($('<li>')
								.text(name)
								.append(' ')
								.append($('<button>')
									.addClass('btn btn-default btn-xs')
									.click(function(){removeUser(name)})
									.append($('<i>')
										.addClass('fa fa-times'))))

					});

				},
				newUser = function(e){
					e.preventDefault()
					if($('input.new-user').val().trim())
					{
						addUser($('input.new-user').val())
						$('input.new-user').val('');
					}
				},
				addUser = function(name){
					data[who].push(name.trim());
					renderUserList();
				},
				removeUser = function(name){
					var index = data[who].indexOf(name);
					if (index > -1) {
					    data[who].splice(index, 1);
					}
					renderUserList();
				}

				$('#myModal .modal-body')
					.empty()
					.append($('<form>')
						.submit(newUser)
						.append($('<input>')
							.attr('type', 'submit')
							.addClass('invisible'))				
						.append(groupList)
						.append($('<div>')
						.addClass('input-group')
						.append($('<input>')
							.addClass('form-control new-user')
							.attr('placeholder', i18n.t("admin.edit_user.add_user")))
							.append($('<span>')
								.addClass('input-group-btn')
								.append($('<button>')
									.addClass('btn btn-default')
									.click(newUser)
								.text('+')))));
				console.log('ok')
				renderUserList()
				console.log('ok?')

				$('#myModal .modal-title').text(i18n.t("admin.edit_mg.title"));

				$('#myModal button.ok')
					.data(data)
					.text(i18n.t("dialog.save"))
					.off() // Delete all event handlers
					.click(save);

				$('#myModal').modal('show');
			},
			save = function(e){
				// In case we get called by submit
				e.preventDefault();

				var data = $('#myModal button.ok').data(),
					mgroups_found = false,
					mgroups = ['#'];

				// Collect values from inputs
				$('#myModal input').each(function(){
					if($(this).attr('name'))
					{
						if($(this).attr('name') == 'machine_group'){

							// Flag found mgroups
							mgroups_found = true;

							if($(this).prop('checked')){
								mgroups.push($(this).data('id'))
							}
						}
						else
						{
							data[$(this).attr('name')] = $(this).val();
						}
					}
				});

				// If machine_group entries found, update machine_groups
				if(mgroups_found){
					data.machine_groups = mgroups;
				}

				// Store object in database
				var jqxhr = $.post( baseUrl + "admin/save_business_unit", data)
				.done(function(data){

					// Dismiss modal
					$('#myModal').modal('hide');

					// If unit does not exist, add it
					if( ! $('.unitid-' + data.unitid).length)
					{
						$('#bu_units')
							.append($('<div>')
								.addClass('col-lg-12 unit unitid-' + data.unitid));
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
					users = ''
					managers = '';
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
							.text(groupname + ' '))
					});
				}

				if(data.users)
				{
					users = $('<ul>');
					$.each(data.users, function(index, val){
						users.append($('<li>')
							.text(val))
					});
					
				}
				if(data.managers)
				{
					managers = $('<ul>');
					$.each(data.managers, function(index, val){
						managers.append($('<li>')
							.text(val))
					});
				}

				var editButton = $('<button>')
										.addClass('btn btn-default btn-xs pull-right')
										.attr('title', 'edit')
										.append($('<i>')
											.addClass('fa fa-edit'));

				$(this)
					.empty()
					.append($('<div>')
						.addClass('panel panel-default')
						.append($('<div>')
							.addClass('panel-heading')
							.append($('<h3>')
								.addClass('name panel-title')
								.text(data.name)
								.append(editButton.clone()
										.click(edit))))
						.append($('<div>')
							.addClass('panel-body row')
							.append($('<div>')
								.addClass('col-lg-12')
								.text(data.address))
							.append($('<div>')
								.addClass('col-md-4')
								.append($('<h4>')
									.text('Machine Groups ')
										.append(editButton.clone()
											.click(editGroups)))
									.append(machine_groups))
							.append($('<div>')
								.addClass('col-md-4')
								.append($('<h4>')
									.text('Managers ')
										.append(editButton.clone()
											.attr('data-type', 'managers')
											.click(editUsers)))
									.append(managers))
							.append($('<div>')
								.addClass('col-md-4')
								.append($('<h4>')
									.text('Users ')
										.append(editButton.clone()
											.attr('data-type', 'users')
											.click(editUsers)))
									.append(users)))
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
						.addClass('col-lg-12 unit unitid-' + value.unitid)
						.each(render)
					);
			});

		});



		// Add + button
		$('#bu_title')
			.append(' ')
			.append($('<a>')
				.addClass("btn btn-default btn-xs")
				.click(edit)
				.append($('<i>')
					.addClass('fa fa-plus')))
		
	} );
</script>

<?php $this->view('partials/foot'); ?>