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
			$('#total-count').text(oSettings.fnRecordsTotal());

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

		// Set computer name value and title
		$('.mr-computer_name_input')
			.val(machineData.computer_name)
			.attr('title', machineData.computer_name)
			.data('placement', 'bottom')
			.tooltip();

		// Add computername to pagetitle
		var title = $('title').text();
		$('title').text(machineData.computer_name + ' | ' + title)

		// Set tooltips
		$( "time" ).each(function( index ) {
				$(this).tooltip().css('cursor', 'pointer');
		});
		
		var username = 'admin';
		if($('nav i.fa-user')[0]){
			username=$('nav i.fa-user')[0].nextSibling.nodeValue.trim();
		}

		// Remote control links
		$.getJSON( appUrl + '/clients/get_links', function( links ) {
			$.each(links, function(prop, val){
				$('#client_links').append($('<li>')
					.append($('<a>')
						.attr('href', (
							val.replace(/%s/, machineData.remote_ip)
							.replace(/%remote_ip/, machineData.remote_ip)
							.replace(/%u/, username)
							.replace(/%network_ip_v4/, machineData.ipv4ip)
							.replace(/%network_ip_v6/, machineData.ipv6ip)))
						.text(i18n.t('remote_control')+' ('+prop+')'))
				)
			});
		});

		// get archive status
		var getArchiveStatus = function(){
			return $
		}
		//set archive status
		var setArchiveStatus = function(status){
			if(status == 1){
				$('#archive_button span').text(i18n.t('unarchive'));
			}else{
				$('#archive_button span').text(i18n.t('archive'));
			}
		}

		setArchiveStatus(machineData.status);

		$('#archive_button').data('status', machineData.status)
			.click(function(){
				var status = $('#archive_button').data('status') == 1 ? 0 : 1;
				var settings = { status: status}
				$.post(appUrl + '/archiver/update_status/' + serialNumber, settings, function(){
					// Change button text
					setArchiveStatus(status);
					// store new status
					$('#archive_button').data('status', status)
					// Update all
					$(document).trigger('appUpdate');
				})
			});
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


	loadHash();

	// Update hash when changing tab
	$('a[data-toggle="tab"]').on('shown.bs.tab', updateHash);


});
