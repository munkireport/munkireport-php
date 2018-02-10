<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Memory_model;
?>

<div class="container">
  <div class="row">
	<div class="col-lg-12">

	  <h3><span data-i18n="memory.reporttitle"></span> <span id="total-count" class='label label-primary'>…</span></h3>

	  <table class="table table-striped table-condensed table-bordered">

		<thead>
		  <tr>
			<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
			<th data-i18n="serial" data-colname='reportdata.serial_number'></th>
			<th data-i18n="memory.name" data-colname='memory.name'></th>
			<th data-i18n="memory.dimm_size" data-colname='memory.dimm_size'></th>
			<th data-i18n="memory.dimm_speed" data-colname='memory.dimm_speed'></th>
			<th data-i18n="memory.dimm_type" data-colname='memory.dimm_type'></th>
			<th data-i18n="memory.dimm_status" data-colname='memory.dimm_status'></th>
			<th data-i18n="memory.dimm_manufacturer" data-colname='memory.dimm_manufacturer'></th>
			<th data-i18n="memory.global_ecc_state" data-colname='memory.global_ecc_state'></th>
			<th data-i18n="memory.is_memory_upgradeable" data-colname='memory.is_memory_upgradeable'></th>
			<th data-i18n="memory.dimm_ecc_errors" data-colname='memory.dimm_ecc_errors'></th>
		  </tr>
		</thead>

		<tbody>
		  <tr>
			<td data-i18n="listing.loading" colspan="11" class="dataTables_empty"></td>
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
            runtypes = [], // Array for runtype column
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
                     d.mrColNotEmpty = "name";

                    // Check for column in search
                    if(d.search.value){
                        $.each(d.columns, function(index, item){
                            if(item.name == 'memory.' + d.search.value){
                                d.columns[index].search.value = '> 0';
                            }
                        });

                    }
        		    // IDK what this does
                    if(d.search.value.match(/^\d+\.\d+(\.(\d+)?)?$/)){
                        var search = d.search.value.split('.').map(function(x){return ('0'+x).slice(-2)}).join('');
                        d.search.value = search;
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
	        	var link = mr.getClientDetailLink(name, sn, '#tab_memory-tab');
	        	$('td:eq(0)', nRow).html(link);

                // Status
	        	var status=$('td:eq(6)', nRow).html();
	        	status = status == 'empty' ? i18n.t('memory.empty') :
	        	status = status == 'ok' ? i18n.t('memory.ok') :
	        	(status === 'unknown' ? i18n.t('unknown') : '')
	        	$('td:eq(6)', nRow).html(status)
                
	        	// ECC Status
	        	var eccstatus=$('td:eq(8)', nRow).html();
	        	eccstatus = eccstatus == '2' ? i18n.t('memory.ecc_errors') :
	        	eccstatus = eccstatus == '1' ? i18n.t('memory.ecc_enabled') :
	        	(eccstatus === '0' ? i18n.t('memory.ecc_disabled') : '')
	        	$('td:eq(8)', nRow).html(eccstatus)

	        	// upgradable
	        	var upgradable=$('td:eq(9)', nRow).html();
	        	upgradable = upgradable == '1' ? i18n.t('yes') :
	        	(upgradable === '0' ? i18n.t('no') : '')
	        	$('td:eq(9)', nRow).html(upgradable)
		    }
	    });

	});
</script>

<?php $this->view('partials/foot'); ?>