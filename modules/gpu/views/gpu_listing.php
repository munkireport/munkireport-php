<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Gpu_model;
?>

<div class="container">
  <div class="row">
	<div class="col-lg-12">

	  <h3><span data-i18n="gpu.reporttitle"></span> <span id="total-count" class='label label-primary'>…</span></h3>

	  <table class="table table-striped table-condensed table-bordered">

		<thead>
		  <tr>
			<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
			<th data-i18n="serial" data-colname='reportdata.serial_number'></th>
			<th data-i18n="gpu.model" data-colname='gpu.model'></th>
			<th data-i18n="gpu.vendor" data-colname='gpu.vendor'></th>
			<th data-i18n="gpu.vram_short" data-colname='gpu.vram'></th>
			<th data-i18n="gpu.pcie_width" data-colname='gpu.pcie_width'></th>
			<th data-i18n="gpu.slot_name" data-colname='gpu.slot_name'></th>
			<th data-i18n="gpu.metal" data-colname='gpu.metal'></th>
			<th data-i18n="gpu.device_id" data-colname='gpu.device_id'></th>
			<th data-i18n="gpu.efi_version" data-colname='gpu.efi_version'></th>
			<th data-i18n="gpu.revision_id" data-colname='gpu.revision_id'></th>
			<th data-i18n="gpu.rom_revision" data-colname='gpu.rom_revision'></th>
		  </tr>
		</thead>

		<tbody>
		  <tr>
			<td data-i18n="listing.loading" colspan="12" class="dataTables_empty"></td>
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
                    d.mrColNotEmpty = "model";
                    
                    // Check for column in search
                    if(d.search.value){
                        $.each(d.columns, function(index, item){
                            if(item.name == 'gpu.' + d.search.value){
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
	        	var link = mr.getClientDetailLink(name, sn, '#tab_gpu-tab');
	        	$('td:eq(0)', nRow).html(link);
                
	        	// Metal supported
	        	var metal=$('td:eq(7)', nRow).html();
	        	metal = metal == '4' ? i18n.t('gpu.metal4') :
	        	metal = metal == '3' ? i18n.t('gpu.metal3') :
	        	metal = metal == '2' ? i18n.t('gpu.metal2') :
	        	metal = metal == '1' ? i18n.t('gpu.metal1') :
	        	(metal === '0' ? i18n.t('no') : '')
	        	$('td:eq(7)', nRow).html(metal)
                
		    }
	    });

	});
</script>

<?php $this->view('partials/foot'); ?>
