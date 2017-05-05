<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Smart_stats_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3><span data-i18n="smart_stats.reporttitle"></span> <span id="total-count" class='label label-primary'>…</span></h3>
		  
		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="smart_stats.disk_number" data-colname='smart_stats.disk_number'></th>
		        <th data-i18n="smart_stats.error_count" data-colname='smart_stats.error_count'></th>
		        <th data-i18n="smart_stats.error_poh" data-colname='smart_stats.error_poh'></th>
		        <th data-i18n="smart_stats.model_family" data-colname='smart_stats.model_family'></th>
		        <th data-i18n="smart_stats.device_model" data-colname='smart_stats.device_model'></th>
		        <th data-i18n="smart_stats.serial_number_hdd" data-colname='smart_stats.serial_number_hdd'></th>
		        <th data-i18n="smart_stats.firmware_version" data-colname='smart_stats.firmware_version'></th>
		        <th data-i18n="smart_stats.rotation_rate" data-colname='smart_stats.rotation_rate'></th>
		        <th data-i18n="smart_stats.sector_size" data-colname='smart_stats.sector_size'></th>
		        <th data-i18n="smart_stats.ata_version_is" data-colname='smart_stats.ata_version_is'></th>
		        <th data-i18n="smart_stats.sata_version_is" data-colname='smart_stats.sata_version_is'></th>
		        <th data-i18n="smart_stats.user_capacity" data-colname='smart_stats.user_capacity'></th>
		        <th data-i18n="smart_stats.timestamp" data-colname='smart_stats.timestamp'></th>
		        <th data-i18n="smart_stats.power_on_hours" data-colname='smart_stats.power_on_hours'></th>
		        <th data-i18n="smart_stats.power_cycle_count" data-colname='smart_stats.power_cycle_count'></th>
		        <th data-i18n="smart_stats.reallocated_sector_ct" data-colname='smart_stats.reallocated_sector_ct'></th>
		        <th data-i18n="smart_stats.reported_uncorrect" data-colname='smart_stats.reported_uncorrect'></th>
		        <th data-i18n="smart_stats.uncorrectable_error_cnt" data-colname='smart_stats.uncorrectable_error_cnt'></th>
		        <th data-i18n="smart_stats.command_timeout" data-colname='smart_stats.command_timeout'></th>
		        <th data-i18n="smart_stats.current_pending_sector" data-colname='smart_stats.current_pending_sector'></th>
		      </tr>
		  <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="21" class="dataTables_empty"></td>
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
                    d.mrColNotEmpty = "smart_stats.timestamp";
                    
                    // Check for column in search
                    if(d.search.value){
                        $.each(d.columns, function(index, item){
                            if(item.name == 'smart_stats.' + d.search.value){
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
                var link = mr.getClientDetailLink(name, sn, '#tab_smart_stats-tab');
	        	$('td:eq(0)', nRow).html(link);
                
	        	// Format disk number
	        	var disknum=$('td:eq(2)', nRow).html();
	        	$('td:eq(2)', nRow).html("/dev/disk"+disknum)

                // Format SMART Error Count
	        	var status=$('td:eq(3)', nRow).html();
                if (status != ""){
                    $('td:eq(3)', nRow).addClass('danger').html(status)
                } else {
                    $('td:eq(3)', nRow).html("")
                }
                
                // Format SMART Power On Hours Error Count
	        	var status=$('td:eq(4)', nRow).html();
                if (status != ""){
                    $('td:eq(4)', nRow).addClass('danger').html(status)
                } else {
                    $('td:eq(4)', nRow).html("")
                }
                
                // Format timestamp
                var timestamp = (($('td:eq(14)', nRow).html()) * 1000);
                $('td:eq(14)', nRow).html(moment(timestamp).format("YYYY-MM-DD H:mm:ss"))
                
	        	var status=$('td:eq(15)', nRow).html();
                if (status != null){
                    $('td:eq(15)', nRow).html(status)
                } else {
                    $('td:eq(15)', nRow).html("")
                }
                                
	        	var status=$('td:eq(16)', nRow).html();
                if (status != null){
                    $('td:eq(16)', nRow).html(status)
                } else {
                    $('td:eq(16)', nRow).html("")
                }
                                
	        	var status=$('td:eq(17)', nRow).html();
                if (status != null){
                    $('td:eq(17)', nRow).html(status)
                } else {
                    $('td:eq(17)', nRow).html("")
                }
                                
	        	var status=$('td:eq(18)', nRow).html();
                if (status != null){
                    $('td:eq(18)', nRow).html(status)
                } else {
                    $('td:eq(18)', nRow).html("")
                }
                                
	        	var status=$('td:eq(19)', nRow).html();
                if (status != null){
                    $('td:eq(19)', nRow).html(status)
                } else {
                    $('td:eq(19)', nRow).html("")
                }
                                
	        	var status=$('td:eq(20)', nRow).html();
                if (status != null){
                    $('td:eq(20)', nRow).html(status)
                } else {
                    $('td:eq(20)', nRow).html("")
                }
                                
	        	var status=$('td:eq(21)', nRow).html();
                if (status != null){
                    $('td:eq(21)', nRow).html(status)
                } else {
                    $('td:eq(21)', nRow).html("")
                }
                
		    }
	    });

	});
</script>

<?php $this->view('partials/foot'); ?>
