<?php $this->view('partials/head'); ?>

<?php
//Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Usage_stats_model;
?>

<div class="container">
  <div class="row">
  	<div class="col-lg-12">
	<h3><span data-i18n="usage_stats.usage_stats_report"></span> <span id="total-count" class='label label-primary'>…</span></h3>
		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="username" data-colname='reportdata.long_username'></th>
		        <th data-i18n="usage_stats.thermal_pressure" data-colname='usage_stats.thermal_pressure'></th>
		        <th data-i18n="usage_stats.ibyte_rate_short" data-colname='usage_stats.ibyte_rate'></th>
		        <th data-i18n="usage_stats.ibytes" data-colname='usage_stats.ibytes'></th>
		        <th data-i18n="usage_stats.ipacket_rate_short" data-colname='usage_stats.ipacket_rate'></th>
		        <th data-i18n="usage_stats.ipackets" data-colname='usage_stats.ipackets'></th>
		        <th data-i18n="usage_stats.obyte_rate_short" data-colname='usage_stats.obyte_rate'></th>
		        <th data-i18n="usage_stats.obytes" data-colname='usage_stats.obytes'></th>
		        <th data-i18n="usage_stats.opacket_rate_short" data-colname='usage_stats.opacket_rate'></th>
		        <th data-i18n="usage_stats.opackets" data-colname='usage_stats.opackets'></th>
		        <th data-i18n="usage_stats.rbytes_per_s_short" data-colname='usage_stats.rbytes_per_s'></th>
		        <th data-i18n="usage_stats.rbytes_diff" data-colname='usage_stats.rbytes_diff'></th>
		        <th data-i18n="usage_stats.rops_per_s_short" data-colname='usage_stats.rops_per_s'></th>
		        <th data-i18n="usage_stats.rops_diff" data-colname='usage_stats.rops_diff'></th>
		        <th data-i18n="usage_stats.wbytes_per_s_short" data-colname='usage_stats.wbytes_per_s'></th>
		        <th data-i18n="usage_stats.wbytes_diff" data-colname='usage_stats.wbytes_diff'></th>
		        <th data-i18n="usage_stats.wops_per_s_short" data-colname='usage_stats.wops_per_s'></th>
		        <th data-i18n="usage_stats.wops_diff" data-colname='usage_stats.wops_diff'></th>
		        <th data-i18n="usage_stats.package_watts" data-colname='usage_stats.package_watts'></th>
		        <th data-i18n="usage_stats.freq_hz_short" data-colname='usage_stats.freq_hz'></th>
		        <th data-i18n="usage_stats.freq_ratio" data-colname='usage_stats.freq_ratio'></th>
		        <th data-i18n="usage_stats.gpu_freq_hz_short" data-colname='usage_stats.gpu_freq_hz'></th>
		        <th data-i18n="usage_stats.gpu_freq_ratio" data-colname='usage_stats.gpu_freq_ratio'></th>
		        <th data-i18n="usage_stats.gpu_busy" data-colname='usage_stats.gpu_busy'></th>
		        <th data-i18n="usage_stats.kern_bootargs" data-colname='usage_stats.kern_bootargs'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="28" class="dataTables_empty"></td>
				</tr>
		    </tbody>
		  </table>
    </div> <!-- /span 13 -->
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
                    d.mrColNotEmpty = "usage_stats.thermal_pressure";

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
	        	var link = mr.getClientDetailLink(name, sn, '#tab_usage_stats-tab');
	        	$('td:eq(0)', nRow).html(link);
                
                // Format ibyte_rate
                var colvar = $('td:eq(4)', nRow).html();
                if (colvar != "" && (colvar)) {
                    $('td:eq(4)', nRow).html(fileSize(parseFloat(colvar), 2)+'/s');
                } else {
                    $('td:eq(4)', nRow).html('');
                }
                
                // Format ibytes
	        	var colvar = $('td:eq(5)', nRow).html();
                if (colvar != "" && (colvar)) {
	        	$('td:eq(5)', nRow).html(fileSize(parseFloat(colvar), 2));
                } else {
                    $('td:eq(5)', nRow).html('');
                }
                
                // Formate ipacket_rate
	        	var colvar=$('td:eq(6)', nRow).html();
                if (colvar != "" && (colvar)) {
	        	$('td:eq(6)', nRow).html(parseFloat(colvar).toFixed(2)+'/s');
                } else {
                    $('td:eq(6)', nRow).html('');
                }
                
                // Format obyte_rate
                var colvar = $('td:eq(8)', nRow).html();
                if (colvar != "" && (colvar)) {
                    $('td:eq(8)', nRow).html(fileSize(parseFloat(colvar), 2)+'/s');
                } else {
                    $('td:eq(8)', nRow).html('');
                }
                
                // Format obytes
	        	var colvar = $('td:eq(9)', nRow).html();
                if (colvar != "" && (colvar)) {
	        	$('td:eq(9)', nRow).html(fileSize(parseFloat(colvar), 2));
                } else {
                    $('td:eq(9)', nRow).html('');
                }
                
                // Format opacket_rate
                var colvar=$('td:eq(10)', nRow).html();
                if (colvar != "" && (colvar)) {
	        	$('td:eq(10)', nRow).html(parseFloat(colvar).toFixed(2)+'/s');
                } else {
                    $('td:eq(10)', nRow).html('');
                }
                
                // Format rbytes_per_s
                var colvar = $('td:eq(12)', nRow).html();
                if (colvar != "" && (colvar)) {
                    $('td:eq(12)', nRow).html(fileSize(parseFloat(colvar), 2)+'/s');
                } else {
                    $('td:eq(12)', nRow).html('');
                }
                
                // Format rbytes_diff
	        	var colvar = $('td:eq(13)', nRow).html();
                if (colvar != "" && (colvar)) {
	        	$('td:eq(13)', nRow).html(fileSize(parseFloat(colvar), 2));
                } else {
                    $('td:eq(13)', nRow).html('');
                }
                
                // Format rops_per_s
                var colvar = $('td:eq(14)', nRow).html();
                if (colvar != "" && (colvar)) {
                    $('td:eq(14)', nRow).html(parseFloat(colvar).toFixed(2)+'/s');
                } else {
                    $('td:eq(14)', nRow).html('');
                }
                
                // Format wbytes_per_s
                var colvar = $('td:eq(16)', nRow).html();
                if (colvar != "" && (colvar)) {
                    $('td:eq(16)', nRow).html(fileSize(parseFloat(colvar), 2)+'/s');
                } else {
                    $('td:eq(16)', nRow).html('');
                }
                
                // Format wbytes_diff
	        	var colvar = $('td:eq(17)', nRow).html();
                if (colvar != "" && (colvar)) {
	        	$('td:eq(17)', nRow).html(fileSize(parseFloat(colvar), 2));
                } else {
                    $('td:eq(17)', nRow).html('');
                }
                
                // Format wops_per_s
                var colvar = $('td:eq(18)', nRow).html();
                if (colvar != "" && (colvar)) {
                    $('td:eq(18)', nRow).html(parseFloat(colvar).toFixed(2)+'/s');
                } else {
                    $('td:eq(18)', nRow).html('');
                }
                
                // Format package_watts
                var colvar = $('td:eq(20)', nRow).html();
                if (colvar != "" && (colvar)) {
                     $('td:eq(20)', nRow).html(parseFloat(colvar).toFixed(2)+' Watts');
                } else {
                    $('td:eq(20)', nRow).html('');
                }
                
                // Format freq_hz
                var colvar = $('td:eq(21)', nRow).html();
                if (colvar != "" && (colvar)) {
                    $('td:eq(21)', nRow).html(((parseFloat(colvar)/1000000000).toFixed(2))+'Ghz');
                } else {
                    $('td:eq(21)', nRow).html('');
                }
                
                // Format freq_ratio
                var colvar = $('td:eq(22)', nRow).html();
                if (colvar != "" && (colvar)) {
                    $('td:eq(22)', nRow).html((parseFloat(colvar)*100).toFixed(2)+'%');
                } else {
                    $('td:eq(22)', nRow).html('');
                }
                
                // Format gpu_freq_hz
                var colvar =$('td:eq(23)', nRow).html();
                if (colvar != "" && (colvar)) {
                    $('td:eq(23)', nRow).html(((parseFloat(colvar)/1000000).toFixed(2))+'Mhz');
                } else {
                    $('td:eq(23)', nRow).html('');
                }
                
                // Format gpu_freq_ratio
                var colvar = $('td:eq(24)', nRow).html();
                if (colvar != "" && (colvar)) {
                    $('td:eq(24)', nRow).html((parseFloat(colvar)*100).toFixed(2)+'%');
                } else {
                    $('td:eq(24)', nRow).html('');
                }
                
                // Format gpu_busy
                var colvar = $('td:eq(25)', nRow).html();
                if (colvar != "" && (colvar)) {
                    $('td:eq(25)', nRow).html((parseFloat(colvar*100)).toFixed(2)+'%');
                } else {
                    $('td:eq(25)', nRow).html('');
                }
		    }
	    } );
	    // Use hash as searchquery
	    if(window.location.hash.substring(1))
	    {
			oTable.fnFilter( decodeURIComponent(window.location.hash.substring(1)) );
	    }

	} );
</script>

<?php $this->view('partials/foot')?>
