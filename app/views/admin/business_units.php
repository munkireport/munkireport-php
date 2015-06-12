<?php $this->view('partials/head'); ?>

<div class="container">

  <div class="row">


	<h3 class="col-lg-12" id="bu_title" data-i18n="admin.bu_overview"></h3>


	<div id="bu_units"></div>
	<div data-i18n="listing.loading" id="loading"></div>

    <div class="col-lg-12">
    	<div id="machine-groups"></div>
    </div>
  </div> <!-- /row -->
</div>  <!-- /container -->


<script>

	$(document).on('appReady', function(e, lang) {

		var machineGroups = [];

		var edit = function(){

			var fields = {name:'', 'address':''},
				dataTemplate = {unitid:'new', users:['#'], managers:['#'], machine_groups:['#'], iteminfo:[]};

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
		getGroup = function(groupid, what){

			var groupObj = {groupid: groupid, name: 'Group ' + groupid};

			// Find groupid in machineGroups and return name
			$.each(machineGroups, function(index, group){
				if( +group.groupid == +groupid){
					groupObj = group;
					return;
				}
			});

			if(what){

				return groupObj[what];
			}

			return groupObj;
		},
		guid = function(){

			// Generate guid
			var s4 = function() {
			    return Math.floor((1 + Math.random()) * 0x10000)
							.toString(16)
							.substring(1)
							.toUpperCase();
			}

		  return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
		},
		editMachinegroup = function(e){

			e && e.preventDefault();

			var data = $(this).data(),
			deleteMachineGroup = function(e){
				e && e.preventDefault();

				// Show a warning
				if(confirm('Are you sure?'))
				{
					var groupid = data.groupid;
					var url = appUrl + '/admin/remove_machine_group/' + groupid;
					$.getJSON(url, function(data){
						if(data.success == true)
						{
							// Dismiss modal
							$('#myModal').modal('hide');
							// Update listing
							$('.machine-group-' + groupid).remove();
						}
					});	

				}
				// Delete
			},
			saveMachineGroup = function(e){

				e && e.preventDefault();

				// Get data from form
				var formData = $('#myModal form').serializeArray();
				
				// Save machine group data
				var jqxhr = $.post( appUrl + "/admin/save_machine_group", formData);

				jqxhr.done(function(data){

					// Update name in BU view
					$('a.machine-group-' + data.groupid)
						.data(data)
						.text(data.name);

					// Update business units
					$('.unit').each(function(){
						var bu = $(this).data();
						
						// Find and remove machine_group
						var index = bu.machine_groups.indexOf(data.groupid);
						if(index > -1){
							bu.machine_groups.splice(index, 1);
						}

						// Add machine_group to new Business Unit
						if(bu.unitid == data.business_unit)
						{
							bu.machine_groups.push(data.groupid);
						}

						// Render
						$(this).each(render);
					});

					// Dismiss modal
					$('#myModal').modal('hide');
				})
			}

			// get business units
			var businessUnitSelect = $('<select>')
				.attr('name', 'business_unit')
				.addClass('form-control business-units');
			$('.unit').each(function(){
				var bu = $(this).data();
				businessUnitSelect
					.append($('<option>')
							.val(bu.unitid)
							.attr('selected', function(){
								return bu.machine_groups.indexOf(data.groupid) !== -1;
							})
							.text(bu.name));
			});
			 
			console.log(data)

			// If key is empty, generate guid
			data.keys = data.keys || []; //guid();
			
			var generate = data.keys.length ? ' hidden' : '';

			$('#myModal .modal-body')
				.empty()
				.append($('<form>')
					.submit(saveMachineGroup)
					.append($('<input>')
						.attr('type', 'submit')
						.addClass('invisible'))
					.append($('<input>')
						.attr('type', 'hidden')
						.attr('name', 'groupid')
						.val(data.groupid))
					.append($('<div>')
						.addClass('form-group')
						.append($('<label>')
							.text('Name'))
						.append($('<input>')
							.attr('name', 'name')
							.val(data.name)
							.addClass('form-control')))
					.append($('<div>')
						.addClass('form-group')
						.append($('<label>')
							.text('Machine Key')
							.append(' ')
							.append($('<button>')
								.addClass('btn btn-default btn-xs' + generate)
								.click(function(e){
									e.preventDefault();
									$('input[name="key"]').val(guid());
								})
								.text('Generate')))
						.append($('<input>')
							.attr('name', 'key')
							.val(data.keys[0])
							.addClass('form-control')))
					.append($('<div>')
						.addClass('form-group')
						.append($('<label>')
							.text('Business Unit'))
						.append(businessUnitSelect))
					.append($('<button>')
						.addClass('btn btn-danger')
						.click(deleteMachineGroup)
						.text(i18n.t('delete'))));


			$('#myModal .modal-title').text(i18n.t("admin.mg.edit_group") + ' ' + data.groupid);

			$('#myModal button.ok')
				.empty()
				.data(data)
				.text(i18n.t("dialog.save"))
				.off()
				.click(saveMachineGroup);

			//renderItemList();

			$('#myModal').modal('show');
		},
		editItems = function(){

			// Get unit data
			var data = $(this).closest('.unit').data();

			// Make sure data.machine_groups exist
			data.machine_groups = data.machine_groups || [];

			// Temp machinegroups array
			var items = [];

			// Populate items with current groups
			$.each(data.machine_groups, function(index, group){
				items.push({key: group, name:getGroup(group, 'name')});
			})

			var itemList = $('<div>').addClass('form-group'),
				addItem = function(event){

					// Disable default behaviour
					event.preventDefault();

					if($('input.new-item').val().trim())
					{
						// add to machinegroups
						items.push({key:'', name: $('input.new-item').val().trim()})

						// re-render list
						renderItemList();

						// Reset input field
						$('input.new-item').val('');
					}
						
				},
				renderItemList = function(){
					itemList
						.empty()

					$.each(items, function(index, item){
						itemList
							.append($('<li>')
								.text(item.name)
								.append(' ')
								.append($('<button>')
									.addClass('btn btn-default btn-xs')
									.click(function(){removeItem(item)})
									.append($('<i>')
										.addClass('fa fa-times'))))

					});

				},
				removeItem = function(obj){
					var index = items.indexOf(obj);
					if (index > -1) {
					    items.splice(index, 1);
					}
					renderItemList();					
				},
				saveItems = function(){
					
					// Add items to data
					$('#myModal button.ok').data('iteminfo', items);

					// Call save
					save();
				};					
		

				$('#myModal .modal-body')
					.empty()
					.append($('<form>')
						.submit(addItem)
						.append($('<input>')
							.attr('type', 'submit')
							.addClass('invisible'))
						.append(itemList)
						.append($('<div>')
						.addClass('input-group')
						.append($('<input>')
							.addClass('form-control new-item')
							.attr('placeholder', i18n.t("admin.mg.add_group")))
							.append($('<span>')
								.addClass('input-group-btn')
								.append($('<button>')
									.addClass('btn btn-default')
									.click(addItem)
								.text('+')))));

				$('#myModal .modal-title').text(i18n.t("admin.edit_mg.title"));

				$('#myModal button.ok')
					.data(data)
					.text(i18n.t("dialog.save"))
					.off()
					.click(saveItems);

				renderItemList();

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
				addUser = function(e){
					e.preventDefault()
					if($('input.new-user').val().trim())
					{
						// add to data
						data[who].push($('input.new-user').val().trim());

						// re-render list
						renderUserList();

						// Reset input field
						$('input.new-user').val('');
					}
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
						.submit(addUser)
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
									.click(addUser)
								.text('+')))));

				renderUserList();

				$('#myModal .modal-title').text(i18n.t("admin.edit_user.title"));

				$('#myModal button.ok')
					.data(data)
					.text(i18n.t("dialog.save"))
					.off() // Delete all event handlers
					.click(save);

				$('#myModal').modal('show');
			},
			save = function(e){
				// In case we get called by submit
				e && e.preventDefault();

				var data = $('#myModal button.ok').data();

				// Collect values from inputs
				$('#myModal input').each(function(){
					if($(this).attr('name'))
					{
						data[$(this).attr('name')] = $(this).val();
					}
				});

				// Check Users and Managers
				if( ! data.managers.length){
					data.managers = ['#'];
				}
				if( ! data.users.length){
					data.users = ['#'];
				}

				// Store object in database
				var jqxhr = $.post( appUrl + "/admin/save_business_unit", data)
				.done(function(data){

					// Reload Machine_groups
					$.getJSON(appUrl + '/admin/get_mg_data', function(mg_data){

						// Set machine_groups
						machineGroups = mg_data;

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
					});

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
				var url = appUrl + '/admin/remove_business_unit/' + unitid;
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
						var group = getGroup(val)
						machine_groups.append($('<li>')
							.append($('<a>')
								.attr('href', '#')
								.addClass('machine-group-' + group.groupid)
								.data(group)
								.click(editMachinegroup)
								.text(group.name + ' ')))
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
											.click(editItems)))
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
			$.getJSON(appUrl + '/admin/get_bu_data'),
			$.getJSON(appUrl + '/admin/get_mg_data'),
			$.getJSON(appUrl + '/module/reportdata/get_groups')
			);

		// Render when all requests are successful
		defer.done(function(bu_data, mg_data, gr_data){
			
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


			// Create stray machine groups
			var stray_groups = {};

			// Collect machine_groups
			$.each(machineGroups, function(index, mg){
				stray_groups[mg.groupid] = mg;
			});

			// Find actual group in machine_groups
			$.each(gr_data[0], function(index, grp){
				stray_groups[grp.computer_group] = stray_groups[grp.computer_group] || {groupid: grp.computer_group,name:''}
				stray_groups[grp.computer_group].cnt = grp.cnt;
			});

			// Remove groups that are in a Business Unit
			$.each(bu_data[0], function(index, value){
				$.each(value.machine_groups, function(index, grp){
					var index = stray_groups[grp];
					if (index) {
						delete stray_groups[grp];
					}
				});
			});

			if( ! $.isEmptyObject(stray_groups))
			{
				$('#machine-groups')
					.empty()
					.append($('<div>')
						.addClass('panel panel-default')
						.append($('<div>')
							.addClass('panel-heading')
							.append($('<h3>')
								.addClass('name panel-title')
								.text(i18n.t('admin.unassigned_groups'))))
						.append($('<div>')
							.addClass('list-group')));

				$.each(stray_groups, function(index, value){
					$('#machine-groups .list-group')
							.append($('<a>')
								.addClass('list-group-item machine-group-' + value.groupid)
								.data(value)
								.attr('href', '#')
								.click(editMachinegroup)
								.text(value.name || 'Group ' + value.groupid)
								.append($('<span>')
									.addClass('badge')
									.text(value.cnt || 0)));
				});
			}

			

		});



		// Add + button
		if(businessUnitsEnabled){
			$('#bu_title')
				.append(' ')
				.append($('<a>')
					.addClass("btn btn-default btn-xs")
					.click(edit)
					.append($('<i>')
						.addClass('fa fa-plus')))
		}
		else{
			$('#bu_title')
				.after($('<div>')
					.addClass('col-lg-12')
						.append($('<p>')
							.addClass('alert alert-warning')
							.text(i18n.t('business_unit.disabled'))));
		}
		
	} );
</script>

<?php $this->view('partials/foot'); ?>