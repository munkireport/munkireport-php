$(document).on('appReady', function(e, lang) {

	var search = '';

	// Use hash as searchquery
	if(window.location.hash.substring(1)) {
		search = decodeURIComponent(window.location.hash.substring(1));
	}
	
	// ------------------------------------ Hotkeys
	// Use arrows to use pagination
	
	$(document).bind('keydown', 'right', function(){
		$(".paginate_button.next").click();
	});
	$(document).bind('keydown', 'left', function(){
		$(".paginate_button.previous").click();
	});

	// ------------------------------------ End Hotkeys
	
	
	// Datatables variables
	mr.dt.buttonDom = "<'row'<'col-xs-6 col-md-8'lB r><'col-xs-6 col-md-4'f>>t<'row'<'col-sm-6'i><'col-sm-6'p>>";
	mr.dt.buttons = {
		buttons: [
			'edit',
			'copyHtml5',
			'excelHtml5',
			'csvHtml5',
			'print'
		]
	};
	
	// Datatables defaults
	$.extend( true, $.fn.dataTable.defaults, {
		dom: "<'row'<'col-xs-6 col-md-8'l r><'col-xs-6 col-md-4'f>>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
		stateSave: true,
		serverSide: true,
		search: {search: search},
		stateSaveCallback: function (oSettings, oData) {
		    state( oSettings.sTableId, oData);
		},
		stateLoadCallback: function (oSettings) {
		    return state(oSettings.sTableId);
		},
		stateLoadParams: function (settings, data) {

			// If search, replace state search
			if(search) {
				data.search.search = search;
			}
		},
		initComplete: function(oSettings, json) {

			// Save the parent
		 	var outer = $(this).parent()

			// Wrap table in responsive div
			$(this).wrap('<div class="table-responsive" />');
			
			// Move the buttons
			$('#total-count')
				.after($('.dt-buttons')
					.addClass('pull-right')
				)
			
			// Register for processing event
			$('#total-count').after(' <i class="fa fa-refresh fa fa-spin hide"></i>')
			$(this).on( 'processing.dt', function ( e, settings, processing ) {
		        $('i.fa-refresh').toggleClass('hide', ! processing)
		    } )

		  // Customize search box (add clear search field button)
		  placeholder = $(outer).find('.dataTables_filter label').contents().filter(function() {return this.nodeType === 3;}).text();
		  $(outer).find('.dataTables_filter label').addClass('input-group').contents().filter(function(){
		    return this.nodeType === 3;
		  }).remove();
		  $(outer).find('.dataTables_filter input').addClass('form-control input-sm')
		  	.attr('placeholder', placeholder)
		  	.after($('<span style="cursor: pointer; color: #999" class="input-group-addon"><i class="fa fa-times"></i></span>')
		  	.click(function(e){
		  		
		  		// Clear hash
		  		var loc = window.location;
		  		if ("replaceState" in history)
			        history.replaceState("", document.title, loc.pathname + loc.search);
			    else {
			  		window.location.hash = ''
			  	}

		  		// Erase and trigger datatables filter
		  		$(outer).find('.dataTables_filter input').val('');
		  		$(outer).find('table').dataTable().fnFilter('');

		  	}));

		  // Customize select
		  $(outer).find('select').addClass('form-control input-sm');

		},
        drawCallback: function( oSettings ) {
			$('#total-count').html(oSettings.fnRecordsTotal());

			// If the edit button is active, show the remove machine buttons
			if($('a.buttons-edit.btn-danger').length > 0){
				$('div.machine').addClass('edit btn-group');
			}

			// Add callback to remove machine button
			$('div.machine a.btn-danger').click(function (e) {
				e.preventDefault();
				delete_machine($(this));
			});
		},
		language: {
			url: baseUrl + "assets/locales/dataTables/"+lang+".json"
		} 
	});

	// Modify lengthmenu
	$.fn.dataTable.defaults.aLengthMenu = [[10,25,50,100,-1], [10,25,50,100,i18n.t("all")]];

    // Add custom edit button
	$.fn.dataTable.ext.buttons.edit = {
	    className: 'buttons-edit',
	 	text: i18n.t("edit"),
	    action: function ( e, dt, node, config ) {
	        $(node).toggleClass('btn-danger');
			$('.machine').toggleClass('edit btn-group');
	    }
	};

} );

$(function(){
	var clientInfoPopovers = [],
		popoverOptions = {
			title: 'Machine Data',
			content: loadPopoverContent
		};

	$("span[data-serialnumber]").popover({
		title: function(){
			var id = $.now();
			return "<div id='pop-title-" + id + "'>Title</div>";
		},
		html: true,
		content: function() {
			var id = $.now();
			return loadPopoverContent(id, $(this).data("serialnumber"));
		}
	});




	function loadPopoverContent(id, serialNumber)
	{
		var div = $("<div></div>")
			.attr("id", id)
			.text("Loading...")
			.css({
				"height": 225,
				"width": 250
			});

		$.ajax({
			url: window.location.pathname + "/detail/" + serialNumber + ".json",
			success: function(response) {
				formatResponse(response, id);
			},
			error: function(response) {
				$("#" + id).html("<span class='text-error'>Error fetching data</span>");
			}
		})
		return div;
	}




	function formatResponse(response, divId)
	{
		var $div = $("#" + divId),
			$title = $("#pop-title-" + divId);
		$div.empty();
		$title.empty();

		if (Object.prototype.toString.call(response) != '[object Object]')
		{
			$div.text("Error parsing response from server");
			return;
		}

		var img = $("<center>").append($("<img>")
						.attr('src', response.meta.iconURL)
						.css("height", "100px"),
					$("<hr>")
				),
			cpu = generateReportItem(
					"Processor",
					response.machine.current_processor_speed
						+ " " + response.machine.cpu_arch
				),
			ram = generateReportItem("Memory",
				response.machine.physical_memory),
			ser = generateReportItem("Serial Number",
				response.machine.serial_number),
			osx = generateReportItem("Software",
				"OS X " + response.machine.os_version);

		$title.text(response.machine.machine_desc);
		$div.append(img, cpu, ram, ser, osx);
	}




	function generateReportItem(title, value)
	{
		return $("<div></div>").append(
			$("<b></b>").text(title + " "),
			$("<span></span>").text(value)
		);
	}
});