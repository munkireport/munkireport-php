<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Devtools_model;
?>

<div class="container">
  <div class="row">
	<div class="col-lg-12">

	  <h3><span data-i18n="devtools.reporttitle"></span> <span id="total-count" class='label label-primary'>…</span></h3>

	  <table class="table table-striped table-condensed table-bordered">

		<thead>
		  <tr>
			<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
			<th data-i18n="serial" data-colname='reportdata.serial_number'></th>
			<th data-i18n="devtools.xcode_version" data-colname='devtools.xcode_version'></th>
			<th data-i18n="devtools.devtools_version_short" data-colname='devtools.devtools_version'></th>
			<th data-i18n="devtools.cli_tools_short" data-colname='devtools.cli_tools'></th>
			<th data-i18n="devtools.xquartz" data-colname='devtools.xquartz'></th>
			<th data-i18n="devtools.ios_sdks" data-colname='devtools.ios_sdks'></th>
			<th data-i18n="devtools.ios_sim_sdks" data-colname='devtools.ios_simulator_sdks'></th>
			<th data-i18n="devtools.macos_sdks" data-colname='devtools.macos_sdks'></th>
			<th data-i18n="devtools.tvos_sdks" data-colname='devtools.tvos_sdks'></th>
			<th data-i18n="devtools.tvos_sim_sdks" data-colname='devtools.tvos_simulator_sdks'></th>
			<th data-i18n="devtools.watchos_sdks" data-colname='devtools.watchos_sdks'></th>
			<th data-i18n="devtools.watchos_sim_sdks" data-colname='devtools.watchos_simulator_sdks'></th>
			<th data-i18n="devtools.devtools_path_short" data-colname='devtools.devtools_path'></th>
		  </tr>
		</thead>

		<tbody>
		  <tr>
			<td data-i18n="listing.loading" colspan="14" class="dataTables_empty"></td>
		  </tr>
		</tbody>

	  </table>
	</div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<script type="text/javascript">

	$(document).on('appUpdate', function(e){

		var oTable = $('.table').DataTable();
		oTable.ajax.reload();
		return;

	});

	$(document).on('appReady', function(e, lang) {

        // Get modifiers from data attribute
        var mySort = [], // Initial sort
            hideThese = [], // Hidden columns
            col = 0, // Column counter
            columnDefs = [{ visible: false, targets: hideThese }]; //Column Definitions

        $('.table th').map(function(){

            columnDefs.push({name: $(this).data('colname'), targets: col});

            if($(this).data('sort')){
              mySort.push([col, $(this).data('sort')])
            }

            if($(this).data('hide')){
              hideThese.push(col);
            }

            col++
        });

	    oTable = $('.table').dataTable( {
            ajax: {
                url: appUrl + '/datatables/data',
                type: "POST",
                data: function(d){
                    d.mrColNotEmpty = "devtools_version";
                    
                    // Check for column in search
                    if(d.search.value){
                        $.each(d.columns, function(index, item){
                            if(item.name == 'devtools.' + d.search.value){
                                d.columns[index].search.value = '> 0';
                            }
                        });

                    }                 
                }
            },
            dom: mr.dt.buttonDom,
            buttons: mr.dt.buttons,
            order: mySort,
            columnDefs: columnDefs,
		    createdRow: function( nRow, aData, iDataIndex ) {
	        	// Update name in first column to link
	        	var name=$('td:eq(0)', nRow).html();
	        	if(name == ''){name = "No Name"};
	        	var sn=$('td:eq(1)', nRow).html();
	        	var link = mr.getClientDetailLink(name, sn, '#tab_devtools-tab');
	        	$('td:eq(0)', nRow).html(link);
                
		    }
	    });

	});
</script>

<?php $this->view('partials/foot'); ?>
