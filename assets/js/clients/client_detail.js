$(document).on('appReady', function(e, lang) {

	// Datatables defaults
	$.extend( true, $.fn.dataTable.defaults, {
		"sDom": "<'row'<'col-xs-6 col-md-8'l r><'col-xs-6 col-md-4'f>>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
		"bStateSave": true,
		"bProcessing": true,
		"bServerSide": false,
		"stateSaveCallback": function (oSettings, oData) {
		    state( oSettings.sTableId, oData);
		},
		"stateLoadCallback": function (oSettings) {
		    return state(oSettings.sTableId);
		},
        "language": {
        	"url": baseUrl + "assets/locales/dataTables/"+lang+".json"
        },
		"fnInitComplete": function(oSettings, json) {

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
        "fnDrawCallback": function( oSettings ) {
			$('#total-count').html(oSettings.fnRecordsTotal());

		},
		"sPaginationType": "bootstrap",
		"oLanguage": {
		 "sProcessing": ' <i class="fa fa-refresh fa fa-spin"></i>'
		} 
	});

	// Modify lengthmenu
	$.fn.dataTable.defaults.aLengthMenu = [[10,25,50,100,-1], [10,25,50,100,i18n.t("all")]];


} );
