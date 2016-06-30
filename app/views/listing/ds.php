<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Ds_model;
?>

<div class="container">
  <div class="row">
  	<div class="col-lg-12">
		  <h3>DeployStudio report <span id="total-count" class='label label-primary'>…</span></h3>
		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
                <th data-i18n="ds.dstudio-last-workflow" data-colname='dstudio-last-workflow' ></th>
		        <th data-i18n="ds.dstudio-last-workflow-duration" data-colname='dstudio-last-workflow-duration'></th>
		        <th data-i18n="ds.dstudio-last-workflow-execution-date" data-colname='dstudio-last-workflow-execution-date'></th>
		        <th data-i18n="ds.dstudio-last-workflow-status" data-colname='dstudio-last-workflow-status'></th>
		        <th data-i18n="ds.dstudio-disabled" data-colname='dstudio-disabled' ></th>
		        <th data-i18n="ds.dstudio-group" data-colname='dstudio-group' ></th>	
		        <th data-i18n="ds.dstudio-auto-disable" data-colname='dstudio-auto-disable' ></th>
		        <th data-i18n="ds.dstudio-auto-reset-workflow" data-colname='dstudio-auto-reset-workflow' ></th>
		        <th data-i18n="ds.dstudio-auto-started-workflow" data-colname='dstudio-auto-started-workflow' ></th>
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
		// Get column names from data attribute
		var columnDefs = [],
            col = 0; // Column counter
		$('.table th').map(function(){
              columnDefs.push({name: $(this).data('colname'), targets: col});
              col++;
		});
	    oTable = $('.table').dataTable( {
	        columnDefs: columnDefs,
	        ajax: {
                url: "<?php echo url('datatables/data'); ?>",
                type: "POST"
            },
            dom: mr.dt.buttonDom,
            buttons: mr.dt.buttons,
	        createdRow: function( nRow, aData, iDataIndex ) {
	        	// Update name in first column to link
	        	var name=$('td:eq(0)', nRow).html();
	        	if(name == ''){name = "No Name"};
	        	var sn=$('td:eq(1)', nRow).html();
	        	var link = get_client_detail_link(name, sn, '<?php echo url(); ?>/');
	        	$('td:eq(0)', nRow).html(link);

	        }
	    });
	});
</script>

<?php $this->view('partials/foot'); ?>
