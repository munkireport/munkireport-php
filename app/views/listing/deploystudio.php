<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Deploystudio_model;
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
                <th data-i18n="ds.dstudio-last-workflow" data-colname='deploystudio.dstudio_last_workflow'></th>
		        <th data-i18n="ds.dstudio-last-workflow-duration" data-colname='deploystudio.dstudio_last_workflow_duration'></th>
		        <th data-i18n="ds.dstudio-last-workflow-execution-date" data-colname='deploystudio.dstudio_last_workflow_execution_date'></th>
		        <th data-i18n="ds.dstudio-last-workflow-status" data-colname='deploystudio.dstudio_last_workflow_status'></th>
		        <th data-i18n="ds.dstudio-disabled" data-colname='deploystudio.dstudio_disabled'></th>
		        <th data-i18n="ds.dstudio-group" data-colname='deploystudio.dstudio_group'></th>	
		        <th data-i18n="ds.dstudio-auto-disable" data-colname='deploystudio.dstudio_auto_disable'></th>
		        <th data-i18n="ds.dstudio-auto-reset-workflow" data-colname='deploystudio.dstudio_auto_reset_workflow'></th>
		        <th data-i18n="ds.dstudio-auto-started-workflow" data-colname='deploystudio.dstudio_auto_started_workflow'></th>
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
	        	var link = mr.getClientDetailLink(name, sn, '#tab_deploystudio-tab');
	        	$('td:eq(0)', nRow).html(link);
                
	        	// Workflow Status
                var status=$('td:eq(5)', nRow).html();
                status = status == 'completed' ? '<span class="label label-success">'+i18n.t('ds.dstudio-completed')+'</span>' :
                (status === 'failed' ? '<span class="label label-danger">'+i18n.t('ds.dstudio-failed')+'</span>' : '')
                $('td:eq(5)', nRow).html(status)
                
	        	// Disabled
                var disabled=$('td:eq(6)', nRow).html();
                disabled = disabled == 'YES' ? i18n.t('yes') :
                (disabled === 'NO' ? i18n.t('no') : '')
                $('td:eq(6)', nRow).html(disabled)
                
	        	// Auto-Disabled
                var autodisabled=$('td:eq(8)', nRow).html();
                autodisabled = autodisabled == 'YES' ? i18n.t('yes') :
                (autodisabled === 'NO' ? i18n.t('no') : '')
                $('td:eq(8)', nRow).html(autodisabled)
                
	        	// Auto-Reset
                var autoreset=$('td:eq(9)', nRow).html();
                autoreset = autoreset == 'YES' ? i18n.t('yes') :
                (autoreset === 'NO' ? i18n.t('no') : '')
                $('td:eq(9)', nRow).html(autoreset)

	        }
	    });
	});
</script>

<?php $this->view('partials/foot'); ?>
