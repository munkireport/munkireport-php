$(document).on('appReady', function(e, lang) {

	// ------------------------ Datatables defaults
	$.extend( true, $.fn.dataTable.defaults, {
		dom: "<'row'<'col-xs-6 col-md-8'l r><'col-xs-6 col-md-4'f>>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
		stateSave: true,
		processing: true,
		serverSide: false,
		stateSaveCallback: function (oSettings, oData) {
		    state( oSettings.sTableId, oData);
		},
		stateLoadCallback: function (oSettings) {
		    return state(oSettings.sTableId);
		},
        language: {
        	url: baseUrl + "assets/locales/dataTables/"+lang+".json"
        },
		initComplete: function(oSettings, json) {

			// Save the parent
		 	var outer = $(this).parent();

			// Wrap table in responsive div
			$(this).wrap('<div class="table-responsive" />');


		  // Customize search box (add clear search field button)
		  placeholder = $(outer).find('.dataTables_filter label').contents().filter(function() {return this.nodeType === 3;}).text();
		  $(outer).find('.dataTables_filter label').addClass('input-group').contents().filter(function(){
		    return this.nodeType === 3;
		  }).remove();
		  $(outer).find('.dataTables_filter input').addClass('form-control input-sm')
		  	.attr('placeholder', placeholder)
		  	.after($('<span style="cursor: pointer; color: #999" class="input-group-addon"><i class="fa fa-times"></i></span>')
		  	.click(function(e){

		  		// Erase and trigger datatables filter
		  		$(outer).find('.dataTables_filter input').val('');
		  		$(outer).find('table').dataTable().fnFilter('');

		  	}));

		  // Customize select
		  $(outer).find('select').addClass('form-control input-sm');

		},
        drawCallback: function( oSettings ) {
			$('#total-count').html(oSettings.fnRecordsTotal());

		},
		language: {
		 processing: ' <i class="fa fa-refresh fa fa-spin"></i>'
		}
	});

	// Modify lengthmenu
	$.fn.dataTable.defaults.lengthMenu = [[10,25,50,100,-1], [10,25,50,100,i18n.t("all")]];

	// -------------------------------- end datatables

	// Set table classes
	$('table').addClass('table table-condensed table-striped');

	// Set h4 classes on headings
	$('#summary h4').addClass('alert alert-info');

	// Fix for using a regular dropdown for tabs
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

		// Remove 'active' class from all li's
		$(e.target).closest('ul').children().removeClass('active');

		// Add 'active' to current li
		$(e.target).parent().addClass('active');

	});

	// Get client data
	$.getJSON( appUrl + '/clients/get_data/' + serialNumber, function( data ) {

		machineData = data[0];

		// Set properties based on id
		$.each(machineData, function(prop, val){
			$('.mr-'+prop).html(val);
		});

		// Convert computer_model to link to everymac.com TODO: make this optional/configurable
		var mmodel = $('.mr-machine_model').html();
		$('.mr-machine_model').html('<a target="_blank" href="https://www.everymac.com/ultimate-mac-lookup/?search_keywords='+mmodel+'">'+mmodel+'</a>');

		// Set computer name value and title
		$('.mr-computer_name_input')
			.val(machineData.computer_name)
			.attr('title', machineData.computer_name)
			.data('placement', 'bottom')
			.tooltip();

		// Add computername to pagetitle
		var title = $('title').text();
		$('title').text(machineData.computer_name + ' | ' + title)

		// Format OS Version
		$('.mr-os_version').html(mr.integerToVersion(machineData.os_version));


		// Format filesizes
		$('.mr-TotalSize').html(fileSize(machineData.totalsize, 1));
		$('.mr-UsedSize').html(fileSize(machineData.totalsize - machineData.freespace, 1));
		$('.mr-freespace').html(fileSize(machineData.freespace, 1));

		// Smart status
		$('.mr-smartstatus').html(machineData.smartstatus);
		if(machineData.smartstatus == 'Failing'){
			$('.mr-smartstatus').addClass('label label-danger');
		}

		// Warranty status
		var cls = 'text-danger',
			msg = machineData.status
		switch (machineData.status) {
			case 'Supported':
				cls = 'text-success';
				msg = i18n.t("warranty.supported_until", {date:machineData.end_date});
				break;
			case 'No Applecare':
				cls = 'text-warning';
				msg = i18n.t("warranty.supported_no_applecare", {date:machineData.end_date});
				break;
			case 'Unregistered serialnumber':
				cls = 'text-warning';
				msg = i18n.t("warranty.unregistered");
				msg = msg + ' <a target="_blank" href="https://selfsolve.apple.com/RegisterProduct.do?productRegister=Y&amp;country=USA&amp;id='+machineData.serialNumber+'">Register</a>'
				break;
			case 'Expired':
				cls = 'text-danger';
				msg = i18n.t("warranty.expired", {date:machineData.end_date});
				break;
			default:
				msg = machineData.status;
		}


		$('.mr-warranty_status').addClass(cls).html(msg);

		// Uptime
		if(machineData.uptime > 0){
			var uptime = moment((machineData.timestamp - machineData.uptime) * 1000);
			$('.mr-uptime').html('<time title="'+i18n.t('boot_time')+': '+uptime.format('LLLL')+'">'+uptime.fromNow(true)+'</time>');
		}else{
			$('.mr-uptime').html(i18n.t('unavailable'));
		}

		// Registration date
		var msecs = moment(machineData.reg_timestamp * 1000);
		$('.mr-reg_date').append('<time title="'+msecs.format('LLLL')+'" >'+msecs.fromNow()+'</time>');

		// Check-in date
		var msecs = moment(machineData.timestamp * 1000);
		$('.mr-check-in_date').append('<time title="'+msecs.format('LLLL')+'" >'+msecs.fromNow()+'</time>');

		// Firewall
		var fw_states = [i18n.t('disabled'), i18n.t('enabled'), i18n.t('security.block_all')]
		var firewall_state = parseInt(machineData.firewall_state);
		$('.mr-firewall_state').text(fw_states[firewall_state] || i18n.t('unknown'));

        // SKEL status
        var skel_states = [i18n.t('security.skel.all-allowed'), i18n.t('security.skel.user-approved')]
        var skel_state = parseInt(machineData.skel_state);
        $('.mr-skel_state').text(skel_states[skel_state] || i18n.t('unknown'));

		// Set tooltips
		$( "time" ).each(function( index ) {
				$(this).tooltip().css('cursor', 'pointer');
		});
		
		var username=$('nav i.fa-user')[0].nextSibling.nodeValue.trim();
		// Remote control links
		$.getJSON( appUrl + '/clients/get_links', function( links ) {
			$.each(links, function(prop, val){
				$('#client_links').append('<li><a href="'+(val.replace(/%s/, machineData.remote_ip).replace(/%remote_ip/, machineData.remote_ip).replace(/%u/, username).replace(/%network_ip_v4/, machineData.ipv4ip).replace(/%network_ip_v6/, machineData.ipv6ip))+'">'+i18n.t('remote_control')+' ('+prop+')</a></li>');
			});
		});


	});


	// -------------------------- BACKUP

	// Get backup data
	var timeMachineReq = $.getJSON( appUrl + '/module/timemachine/get_data/' + serialNumber),
		crashPlanReq = $.getJSON( appUrl + '/module/crashplan/get_data/' + serialNumber);

	// When data is loaded, create tab
	$.when( timeMachineReq, crashPlanReq ).done(function ( tmResp, cpResp ) {

		// Get the data out of the response array
		var tmData = tmResp[0],
			cpData = cpResp[0];

		// Draw Time Machine unit
		if(tmData.id !== '')
		{
			$('table.mr-timemachine-table')
				.empty()
				.append($('<tr>')
					.append($('<th>')
						.text(i18n.t('backup.last_success')))
					.append($('<td>')
						.text(function(){
							if(tmData.last_success){
								return moment(tmData.last_success + 'Z').fromNow();
							}
						})))
				.append($('<tr>')
					.append($('<th>')
						.text(i18n.t('backup.duration')))
					.append($('<td>')
						.text(function() {
						    var duration = tmData.duration
						    if(!duration){
							return "";
						    } else {
							return moment.duration(tmData.duration, "seconds").humanize();
						    }
						})))
							.append($('<tr>')
								.append($('<th>')
									.text(i18n.t('backup.last_failure_msg')))
								.append($('<td>')
						.text(function() {
						    var message = tmData.last_failure_msg
						    if(! message.startsWith("Backup failed with error ", 0) && message !== ""){
							return i18n.t('timemachine.'+message);
						    } else if (message.startsWith("Backup failed with error ", 0)) {
							return message.replace("Backup failed with error ", "Error ");
						    }
						})))
				.append($('<tr>')
					.append($('<th>')
						.text(i18n.t('backup.last_failure')))
					.append($('<td>')
						.text(function(){
							if(tmData.last_failure){
								return moment(tmData.last_failure + 'Z').fromNow();
							}
						})))
				.append($('<tr>')
					.append($('<th>')
						.text(i18n.t('backup.location_name')))
					.append($('<td>')
						.text(tmData.alias_volume_name)))
				.append($('<tr>')
					.append($('<th>')
						.text(i18n.t('backup.destinations')))
					.append($('<td>')
						.text(tmData.destinations)))
				.append($('<tr>')
					.append($('<th>')
						.text(i18n.t('timemachine.result')))
					.append($('<td>')
						.text(i18n.t('timemachine.'+tmData.result))));
		}
		else{
			$('table.mr-timemachine-table')
				.empty()
				.append('<tr><td>'+i18n.t('no_data')+'</td></tr>');
		};

		// Draw Crashplan Unit
		var mr_table_data = '<tr><td>'+i18n.t('no_data')+'</td></tr>';
		if(cpData){
			mr_table_data = '';
			$.each(cpData, function(index, item){
				mr_table_data = mr_table_data + '<tr class="info"><th>'+i18n.t('backup.destination')+'</th><td>'+item.destination+'<td></tr>';
				mr_table_data = mr_table_data + '<tr><th>'+i18n.t('backup.last_success')+'</th><td>'+moment(item.last_success * 1000).fromNow()+'<td></tr>';
				mr_table_data = mr_table_data + '<tr><th>'+i18n.t('backup.duration')+'</th><td>'+moment.duration(item.duration, "seconds").humanize()+'<td></tr>';
				mr_table_data = mr_table_data + '<tr><th>'+i18n.t('backup.last_failure')+'</th><td>'+moment(item.last_failure * 1000).fromNow()+'<td></tr>';
				mr_table_data = mr_table_data + '<tr><th>'+i18n.t('backup.last_failure_msg')+'</th><td>'+item.reason+'<td></tr>';
			});
		}
		$('table.mr-crashplan-table')
			.empty()
			.append(mr_table_data)

	});


	// -------------------------- END BACKUP



	// ------------------------------------ Hotkeys
	// Use arrows to switch between tabs in client view

	$(document).bind('keydown', 'right', function(){

		var activeTab = $('.client-tabs').find('li.active')
		if(activeTab.length < 1){
			activeTab = $('.client-tabs li:first');
		}
		var nextTab = activeTab.next('li:not(.divider)')
		if(nextTab.length < 1){
			nextTab = $('.client-tabs li:first');
		}

		$(nextTab).find('a').click();
		return true;
	});

	$(document).bind('keydown', 'left', function(){

		var activeTab = $('.client-tabs').find('li.active')
		if(activeTab.length < 1){
			activeTab = $('.client-tabs li:first');
		}
		var prevTab = activeTab.prev('li:not(.divider)')
		if(prevTab.length < 1){
			prevTab = $('.client-tabs li:not(.divider):last');
		}

		$(prevTab).find('a').click();
		return true;
	});


	// ------------------------------------ End Hotkeys


	// ------------------------------------ Tags
	var currentTags = {}
		tagsRetrieved = false;
	$('.mr-refresh-desc')
		.after($('<div>').append($('<select>')
			.addClass('tags')
			.attr("data-role", "tagsinput")
			.attr("multiple", "multiple"))
			);

	// instantiate the bloodhound suggestion engine
	var hotDog = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.whitespace,
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		prefetch: {
			url: appUrl + '/module/tag/all_tags',
			cache: false
		}
	});

	// initialize the bloodhound suggestion engine
	hotDog.initialize();

	// Activate tags input
	$('select.tags').tagsinput({
		typeaheadjs: {
		  source: hotDog.ttAdapter()
		}
	});

	// Fix bug in tagsinput/typeahead that shows the last tag on blur
	$('input.tt-input').on('blur', function(){$(this).val('')})

	// Get current tags
	$.getJSON( appUrl + '/module/tag/retrieve/' + serialNumber, function( data ) {
		// Set item value
		if(data.length == 0){
			// Show 'Add tags button'
		}
		else{
			// Show 'Edit tags button'
		}
		$.each(data, function(index, item){
			$('select.tags').tagsinput("add", item.tag)
			// Store tag id
			currentTags[item.tag] = item.id;
		});
		// Signal tags retrieved
		tagsRetrieved = true;
	});

	// Now add event handlers
	$('select.tags')
		.on('itemAdded', function(event) {
			// Check if tags are retrieved
			if(!tagsRetrieved){
				return;
			}

			// Save tag
			formData = {serial_number: serialNumber, tag: event.item};
			var jqxhr = $.post( appUrl + "/module/tag/save", formData);

			jqxhr.done(function(data){
				if(data.error){
					alert(data.error)
				}
				else {
					// Store id in currentTags
					currentTags[data.tag] = data.id;
				}

			})
		}).on('itemRemoved', function(event) {
			var id = currentTags[event.item]
			var jqxhr = $.post( appUrl + "/module/tag/delete/"+serialNumber+"/"+id);

			jqxhr.done(function(data){
				if(data.error){
					alert(data.error)
				}
				else {
					// remove tag from currentTags
					delete currentTags[event.item];
				}
			})
		});

		// ------------------------------------ End Tags

	loadHash();

	// Update hash when changing tab
	$('a[data-toggle="tab"]').on('shown.bs.tab', updateHash);


});
