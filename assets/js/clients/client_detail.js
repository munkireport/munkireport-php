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
		$('.mr-TotalSize').html(fileSize(machineData.TotalSize, 1));
		$('.mr-UsedSize').html(fileSize(machineData.TotalSize - machineData.FreeSpace, 1));
		$('.mr-FreeSpace').html(fileSize(machineData.FreeSpace, 1));

		// Smart status
		$('.mr-SMARTStatus').html(machineData.SMARTStatus);
		if(machineData.SMARTStatus == 'Failing'){
			$('.mr-SMARTStatus').addClass('label label-danger');
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

		// Set tooltips
		$( "time" ).each(function( index ) {
				$(this).tooltip().css('cursor', 'pointer');
		});

		// Remote control links
		$.getJSON( appUrl + '/clients/get_links', function( links ) {
			$.each(links, function(prop, val){
				$('#client_links').append('<li><a href="'+(val.replace(/%s/, machineData.remote_ip))+'">'+i18n.t('remote_control')+' ('+prop+')</a></li>');
			});
		});


	});

	// Get estimate_manufactured_date
	$.getJSON( appUrl + '/module/warranty/estimate_manufactured_date/' + serialNumber, function( data ) {
		$('.mr-manufacture_date').html(data.date)
	});

	// Add storage plot(s)
	var plotId = 'storage-plot';
	var conf = {
		id: 'storage-tab',
		linkTitle: i18n.t('storage.storage'),
		tabTitle: i18n.t('storage.storage'),
		tabContent: $('<div>').attr('id', plotId)
	};
	addTab(conf);
	//Initialize storage plots
	drawStoragePlots(serialNumber, plotId)

	// Get certificate data
	$.getJSON( appUrl + '/module/certificate/get_data/' + serialNumber, function( data ) {
		if(data.length)
		{
			var certs = $('<table>').addClass('table table-condensed table-striped');

			// Set headers
			certs.append($('<thead>')
					.append($('<tr>')
						.append($('<th>')
							.text(i18n.t('listing.certificate.commonname')))
						.append($('<th>')
							.text(i18n.t('listing.certificate.expires')))));

			// Load data
			$.each(data, function(index, cert){
				certs.append($('<tr>')
					.attr('title', cert.rs.cert_path)
					.append($('<td>')
						.text(cert.rs.cert_cn))
					.append($('<td>')
						.html(function(){
							var date = new Date(cert.rs.cert_exp_time * 1000);
							var diff = moment().diff(date, 'days');
							var cls = diff > 0 ? 'danger' : (diff > -90 ? 'warning' : 'success');
							return('<span class="label label-'+cls+'">'+moment(date).fromNow()+'</span>');
						})));
			});

			// Add tab
			var conf = {
				id: 'certificate-tab',
				linkTitle: i18n.t('client.tab.certificates'),
				tabTitle: i18n.t('client.tab.certificates'),
				tabContent: certs
			}
			addTab(conf);

			// Add tooltips
			$('tr[title]').tooltip();

			// Set correct tab on location hash
			loadHash();

		}
	});

	// Get services data
	$.getJSON( appUrl + '/module/service/get_data/' + serialNumber, function( data ) {
		if(data.length)
		{
			var content = $('<table>').addClass('table table-condensed table-striped');

			// Set headers
			content.append($('<thead>')
					.append($('<tr>')
						.append($('<th>')
							.text(i18n.t('service.name')))
						.append($('<th>')
							.text(i18n.t('service.status')))));

			// Load data
			$.each(data, function(index, service){
				content.append($('<tr>')
					.append($('<td>')
						.text(service.rs.service_name))
					.append($('<td>')
						.html(function(){
							var cls = "success";
							if(service.rs.service_state != 'running'){
								cls = "warning";
							}
							return '<span class="label label-'+cls+'">'+service.rs.service_state+'</span>';
						})));
			});

			// Add tab
			var conf = {
				id: 'service-tab',
				linkTitle: i18n.t('service.tab.title'),
				tabTitle: i18n.t('service.tab.title'),
				tabContent: content
			}
			addTab(conf);

			// Set correct tab on location hash
			loadHash();

		}
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
		
		// Draw timemachine unit
		var mr_table_data = '<tr><td>'+i18n.t('no_data')+'</td></tr>';
		if(tmData){
			mr_table_data = '';
			$.each(tmData, function(index, item){
				var last_success = i18n.t('never'),
					last_failure = i18n.t('never');
				if(item.last_success){
					last_success = moment(item.last_success + 'Z').fromNow();
				}
				if(item.last_failure){
					last_failure = moment(item.last_failure + 'Z').fromNow();
				}
				mr_table_data = mr_table_data + '<tr class="info"><th>'+i18n.t('backup.destination')+'</th><td>'+item.location_name+'<td></tr>';
				mr_table_data = mr_table_data + '<tr><th>'+i18n.t('backup.last_success')+'</th><td>'+last_success+'<td></tr>';
				mr_table_data = mr_table_data + '<tr><th>'+i18n.t('backup.duration')+'</th><td>'+moment.duration(+item.duration, "seconds").humanize()+'<td></tr>';
				mr_table_data = mr_table_data + '<tr><th>'+i18n.t('backup.last_failure')+'</th><td>'+last_failure+'<td></tr>';
				mr_table_data = mr_table_data + '<tr><th>'+i18n.t('backup.last_failure_msg')+'</th><td>'+item.last_failure_msg+'<td></tr>';
				mr_table_data = mr_table_data + '<tr><th>'+i18n.t('backup.kind')+'</th><td>'+item.kind+'<td></tr>';
				mr_table_data = mr_table_data + '<tr><th>'+i18n.t('backup.backup_location')+'</th><td>'+item.backup_location+'<td></tr>';
			});
		}
		$('table.mr-timemachine-table')
		.empty()
		.append(mr_table_data);
		
		// Draw Crashplan Unit
		var mr_table_data = '<tr><td>'+i18n.t('no_data')+'</td></tr>';
		$.each(cpData, function(index, item){
			mr_table_data = '<tr><th>'+i18n.t('backup.destination')+'</th><td>'+item.destination+'<td></tr>';
			mr_table_data = mr_table_data + '<tr><th>'+i18n.t('backup.last_success')+'</th><td>'+moment(item.last_success * 1000).fromNow()+'<td></tr>';
			mr_table_data = mr_table_data + '<tr><th>'+i18n.t('backup.duration')+'</th><td>'+moment.duration(item.duration, "seconds").humanize()+'<td></tr>';
			mr_table_data = mr_table_data + '<tr><th>'+i18n.t('backup.last_failure')+'</th><td>'+moment(item.last_failure * 1000).fromNow()+'<td></tr>';
			mr_table_data = mr_table_data + '<tr><th>'+i18n.t('backup.last_failure_msg')+'</th><td>'+item.reason+'<td></tr>';
		});
		$('table.mr-crashplan-table')
			.empty()
			.append(mr_table_data)
		
	});

	
	// -------------------------- END BACKUP

	// Get ARD data
	$.getJSON( appUrl + '/module/ard/get_data/' + serialNumber, function( data ) {
		$.each(data, function(index, item){
			if(/^Text[\d]$/.test(index))
			{
				$('#ard-data')
					.append($('<tr>')
						.append($('<th>')
							.text(index))
						.append($('<td>')
							.text(item)));
			}
		});
	});

	// Get Bluetooth data
	$.getJSON( appUrl + '/module/bluetooth/get_data/' + serialNumber, function( data ) {
		if(data.id !== '')
		{
			$('table.mr-bluetooth-table')
				.empty()
				.append($('<tr>')
					.append($('<th>')
						.text(i18n.t('bluetooth.status')))
					.append($('<td>')
						.text(function(){
							if(data.bluetooth_status == 1){
								return i18n.t('on');
							}
							if(data.bluetooth_status == 0){
								return i18n.t('off');
							}
							return i18n.t('unknown');
						})))
				.append($('<tr>')
					.append($('<th>')
						.text(i18n.t('bluetooth.keyboard')))
					.append($('<td>')
						.text(function(){
							if(data.keyboard_battery == -1){
								return i18n.t('disconnected')
							}
							return i18n.t('battery.life_remaining', {"percent": data.keyboard_battery})
						})))
				.append($('<tr>')
					.append($('<th>')
						.text(i18n.t('bluetooth.mouse')))
					.append($('<td>')
						.text(function(){
							if(data.mouse_battery == -1){
								return i18n.t('disconnected')
							}
							return i18n.t('battery.life_remaining', {"percent": data.mouse_battery})
						})))
				.append($('<tr>')
					.append($('<th>')
						.text(i18n.t('bluetooth.trackpad')))
					.append($('<td>')
						.text(function(){
							if(data.trackpad_battery == -1){
								return i18n.t('disconnected')
							}
							return i18n.t('battery.life_remaining', {"percent": data.trackpad_battery})
						})));
		}
		else{
			$('table.mr-bluetooth-table')
				.empty()
				.append($('<tr>')
					.append($('<td>')
						.attr('colspan', 2)
						.text(i18n.t('no_data'))))
		}

	});
	
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
	$('.mr-machine_desc')
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
